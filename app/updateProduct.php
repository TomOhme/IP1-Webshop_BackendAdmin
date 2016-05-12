<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 13.04.2016
 * Time: 11:49
 */

include("../api/product.php");
include("../api/ProductGroup.php");

$soapProduct = new Product();
$soapProduct -> openSoap();
$soapProductGroup = new Productgroup();
$soapProductGroup -> openSoap();
//$tempFile = $_FILES['picture']['tmp_name'];
var_dump($_FILES);
//var_dump($tempFile);
//$tempFile2 = $_FILES['fileToUpload']['tmp_name'];
//var_dump($tempFile2);
//$tempFile3 = $_FILES['file']['tmp_name'];
//var_dump($tempFile3);
if (isset($_POST['productId']) && $_POST['product'] == 'updateProduct') {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $product = $soapProduct->getProductByID($productId);
    $product['price'] = formatPrice($product['price']);
    if ($product['special_price'] != null) {
        $product['special_price'] = formatPrice($product['special_price']);
    }
    $productImg = $soapProduct->getProductImage($product['product_id']);
    $productStock = $soapProduct->getProductStock($product['product_id']);
    $productStock[0]['qty'] = formatAmount($productStock[0]['qty']);
    //var_dump($productStock);
    $productCategory = array();
    foreach($product['category_ids'] as $categoryId) {
        $productCategory[] = $soapProductGroup->getCategory($categoryId);
    }
    //$productCategory = $soapProductGroup->getCategory(end($product['category_ids']));
    //$allCategory = $soapProductGroup->getTree();
    echo json_encode(array('id' => $productId, 'updateProduct' => $product, 'updateImg' => $productImg, 'updateStock' => $productStock, 'updateCategory' => $productCategory));
} else if (isset($_POST['productId']) && $_POST['product'] == 'delete') {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    //$product = $soapProduct->getProductByID($productId);
    $soapProduct->deleteProductByID($productId);
} else if (isset($_POST['productUpdateSave'])) {
    $values = array();
    parse_str($_POST['productData'], $values);
    $values['category_ids'] = $_POST['category_ids'];
    $productId = isset($values['productId']) ? $values['productId'] : null;
    $values['price'] = unformatPrice($values['price']);
    if ($values['specialPrice'] != null) {
        $values['specialPrice'] = unformatPrice($values['specialPrice']);
    }
    $productData = $soapProduct->createCatalogProductEntity($values['category_ids'], $values['unit'], $values['title'], $values['short_description'],
                                                            $values['price'], $values['stock'], $values['specialPrice'], $values['specialFromDate'], $values['specialFromTo']);
    if ($productId != null) {
        $soapProduct->updateProductByID($productId, $productData);
        //TODO update Image
        //call function image_upload
        //$soapProduct->createProductImage()
    } else {
        $allProducts = $soapProduct->getAllProducts();
        $sku = $allProducts[count($allProducts) -1]['sku'];
        $sku++;
        $soapProduct->createProduct($sku, $productData);
        //TODO create Image
        //call function image_upload
        //$soapProduct->createProductImage()
    }
}

function formatDate($date){
    return  date_format(date_create($date), "d.m.Y");
}

function formatPrice($price){
    return "Fr. " . number_format($price, 2, ',', "'");
}

function formatAmount($amount){
    setlocale(LC_ALL, "de_CH");
    return number_format($amount,0, ".", "'");
}

function unformatPrice($price) {
    if (strpos($price, 'Fr.') !== false) {
        $price = str_replace('Fr.', '', $price);
        var_dump($price);
    }
    if (strpos($price, '/[^\p{L}\p{N}\s]/u')) {
        $price = preg_replace('/[^\p{L}\p{N}\s]/u', '', $price);
        var_dump($price);
    }
    return $price;
}

//TODO in work
//$_FILES["fileToUpload"]["name"] = filename
function image_upload($filename) {
    $ds          = DIRECTORY_SEPARATOR;  //1

    $storeFolder = 'uploads';   //2

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];          //3

        $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

        $targetFile =  $targetPath. $_FILES['file']['name'];  //5

        move_uploaded_file($tempFile,$targetFile); //6

    }



    $target_dir = "uploads/";  //temp path to folder
    $target_file = $target_dir . basename($filename);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if (!empty($_FILES)) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) { //TODO set also in html tag
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>