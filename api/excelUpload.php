<?php
/**
 * Author: Yanick Schraner
 * Date: 16.4.16
 * Purpose: Handle excel file upload
 */
require_once("ProductGroup.php");
require_once("product.php");
require_once("../vendor/autoload.php");

class SampleReadFilter implements PHPExcel_Reader_IReadFilter {
    public function readCell($column, $row, $worksheetName = '') {
        // Read afte 1st row and columns A to F only
        if ($row >= 1) {
           if (in_array($column,range('A','F'))) {
             return true;
           }
        }
        return false;
    }
}

$prodGrpAPI = new Productgroup();
$prodGrpAPI -> openSoap();
$prodAPI = new Product();
$prodAPI -> openSoap();

//import handle
if (isset($_FILES['file-0'])) {
    //check if file has the correct ending
    $allowedExts = array("xlsx");
    $temp        = explode(".", $_FILES["file-0"]["name"]);
    $extension   = end($temp);
    if (($_FILES["file-0"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
        && in_array($extension, $allowedExts)) {
        //Store the file or print an error if validation failed
        if ($_FILES["file-0"]["error"] > 0) {
            http_response_code(403);
            echo "Error: " . $_FILES["file-0"]["error"] . "<br>";
        } else {
            if (file_exists("upload/" . $_FILES["file-0"]["name"])) {
                http_response_code(403);
                echo $_FILES["file-0"]["name"] . " already exists. ";
            } else {
                move_uploaded_file($_FILES["file-0"]["tmp_name"], "upload/" . $_FILES["file-0"]["name"]);
            }

            //Prepare Excel File
            $filename = ("./upload/" . $_FILES["file-0"]["name"]);
            $excelReader = PHPExcel_IOFactory::createReaderForFile($filename);
            $excelReader->setReadDataOnly();
            $excelReader->setReadFilter(new SampleReadFilter());

            $loadSheets = array('Import');
            $excelReader->setLoadSheetsOnly($loadSheets);
            $excelObj = $excelReader->load($filename);
            $excelArray = $excelObj->getActiveSheet()->toArray(null, true,true,true);

            //Validate excel file and upload data
            $valid = validateData($excelArray, $prodAPI, $prodGrpAPI);
            if($valid !== true){
                unlink('upload/' . $_FILES['file-0']['name']);
                http_response_code(403);
                echo $valid;
            } else{
                uploadData($excelArray, $prodAPI, $prodGrpAPI);    
                echo "Alle Produkte wurden erfolgreich importiert!";
                unlink('upload/' . $_FILES['file-0']['name']);
            }
        }
    } else {
        http_response_code(403);
        echo "Ung&uuml;ltiger Dateityp";
    }
}

/**
* checks if the imported products are valid
* @param: products
* @return: error Message or true
*/
function validateData($products, $prodAPI, $prodGrpAPI){
    $valid = false;
    for($i = 2; $i < count($products)+1; $i++){
        $name = $products[$i]["A"];
        $description = $products[$i]["B"];
        $unit = $products[$i]["C"];
        $price = $products[$i]["D"];
        $amount = $products[$i]["E"];
        $categories = $products[$i]["F"];

        //validate name:
        $valid = preg_match("/^[a-zA-ZäöüÄÖÜ ]+$/", $name);
        if(!$valid){ return "Das Produkt ".$name." enth&auml;lt nicht nur Buchstaben";}
        $valid = (strlen($name) < 50 && strlen($name) > 1);
        if(!$valid){ return "Das Produkt ".$name." erf&uuml;llt nicht die vorgegebene Zeichenl&auml;nge";}
        $magentoProducts = $prodAPI -> getAllProducts();
        $valid = (!in_array_r($name, $magentoProducts));
        if(!$valid){ return "Das Produkt ".$name." ist bereits vorhanden";}
        //validate description
        $valid = preg_match("/^[a-zA-ZäöüÄÖÜ0-9 .,!?]+$/", $description);
        if(!$valid){ return "Die Beschreibung ".$description." enth&auml;lt nicht nur Buchstaben und Satzzeichen";}
        $valid = (strlen($description) < 250);
        if(!$valid){ return "Die Beschreibung ".$description." darf nicht l&auml;ger wie 250 Zeichen lang sein";}
        //validate unit
        $valid = preg_match("/^[a-zA-Z ]+$/", $unit);
        if(!$valid){ return "Die Einheit ".$unit." enth&auml;lt nicht nur Buchstaben";}
        $valid = (strlen($name) < 20 && strlen($unit) > 0);
        if(!$valid){ return "Die Einheit ".$unit." ist entweder mehr als 20 Zeichen lang oder leer";}
        //validate price
        $valid = filter_var($price, FILTER_VALIDATE_FLOAT);
        if(!$valid){ return "Der Preis ".$price." entspricht nicht einer Zahl";}
        $valid = ($price > 0 && $price < 100000000);
        if(!$valid){ return "Der Preis ".$price." ist nicht gr&ouml;sser als 0 oder nicht kleiner wie 100'000'000";}
        //validate amount
        $valid = filter_var($amount, FILTER_VALIDATE_FLOAT);
        if(!$valid){ return "Die Menge ".$amount." entspricht nicht einer Zahl";}
        $valid = ($amount > 0 && $amount < 100000000);
        if(!$valid){ return "Die Menge ".$amount." ist nicht gr&ouml;sser als 0 oder nicht kleiner wie 100'000'000";}
        //validate categories
        $categories = explode(",", $categories);
        $magentoCategories = $prodGrpAPI -> getTree();
        foreach ($categories as $categorie){
            $categorie = trim($categorie);
            $valid = preg_match("/^[a-zA-ZäöüÄÖÜ ]+$/", $categorie);
            if(!$valid){ return "Die Kategorie ".$categorie." enth&auml;lt nicht nur Buchstaben";}
            $valid = (in_array_r($categorie, $magentoCategories));
            if(!$valid){ return "Die Kategorie ".$categorie." ist nicht vorhanden";}
        }
    }
    return $valid;
}

/**
* Creates the new products
* @param: product
*/
function uploadData($products, $prodAPI, $prodGrpAPI){
    $magentoProducts = $prodAPI -> getAllProducts();
    $sku = $magentoProducts[count($magentoProducts)-1]['sku'];
    if(file_exists("../config.php")){
        include("../config.php");
    } else{
        include("./config.php");
    }
    $mysqli = new mysqli("localhost",  DBUSER,  DBPWD, "magento");
    for($i = 2; $i < count($products)+1; $i++){
        $name = $products[$i]["A"];
        $description = $products[$i]["B"];
        $unit = $products[$i]["C"];
        $price = $products[$i]["D"];
        $amount = $products[$i]["E"];
        $categories = $products[$i]["F"];
        $categories = explode(",", $categories);
        array_walk($categories, 'trim_value');
        $categoryIDs = array();
        foreach ($categories as $category) {
            $stmt = $mysqli->prepare("SELECT DISTINCT catalog_category_entity_varchar.entity_id FROM catalog_category_entity_varchar WHERE VALUE=?;");
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $stmt->bind_result($categoryID);
            $stmt->fetch();
            array_push($categoryIDs, $categoryID);
            $stmt->close();
        }
        $attributeSet = $prodAPI -> createCatalogProductEntity($categoryIDs,$unit, $name, $description, $price, $amount);
        $sku++;
        $pID = $prodAPI -> createProduct($sku, $attributeSet);
        $prodAPI -> updateProductByID($pID, $attributeSet);
    }
}

//Some helper functions
function trim_value(&$value){
 $value = trim($value);
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}