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
if (isset($_POST['productId']) && $_POST['product'] == 'updateProduct') {
    //fill product fields for update
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $product = $soapProduct->getProductByID($productId);
    $product['price'] = formatPrice($product['price']);
    if ($product['special_price'] != null) {
        $product['special_price'] = formatPrice($product['special_price']);
    }
    $productImg = $soapProduct->getProductImage($product['product_id']);
    $productStock = $soapProduct->getProductStock($product['product_id']);
    $productStock[0]['qty'] = formatAmount($productStock[0]['qty']);
    $productCategory = array();
    foreach($product['category_ids'] as $categoryId) {
        $productCategory[] = $soapProductGroup->getCategory($categoryId);
    }
    //$productCategory = $soapProductGroup->getCategory(end($product['category_ids']));
    //$allCategory = $soapProductGroup->getTree();
    echo json_encode(array('id' => $productId, 'updateProduct' => $product, 'updateImg' => $productImg, 'updateStock' => $productStock, 'updateCategory' => $productCategory));

} else if (isset($_POST['productId']) && $_POST['product'] == 'delete') {
    //delete product
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    //$product = $soapProduct->getProductByID($productId);
    $soapProduct->deleteProductByID($productId);

} else if (isset($_POST['productUpdateSave'])) {
    if(isset($_FILES['file'])){
        echo "DFDSF";
        print_r(get_headers("http://localhost/magento_BackendAdmin/app/updateProduct.php"));
    }

    //create/update product
    $values = array();
    parse_str($_POST['productData'], $values);
    $values['category_ids'] = $_POST['category_ids'];
    $productId = isset($values['productId']) ? $values['productId'] : null;
    $values['price'] = unformatPrice($values['price']);
    if ($values['specialPrice'] != null) {
        $values['specialPrice'] = unformatPrice($values['specialPrice']);
    }
    $productData = $soapProduct->createCatalogProductEntity($values['category_ids'], $values['unit'], $values['title'], $values['short_description'],
                                                            $values['price'], $values['stock'], $values['specialPrice'], $values['specialFromDate'], $values['specialToDate']);
    if ($productId != null) {
        //update product
        $soapProduct->updateProductByID($productId, $productData);
    } else {
        //create product
        $allProducts = $soapProduct->getAllProducts();
        $sku = $allProducts[count($allProducts) -1]['sku'];
        $sku++;
        $soapProduct->createProduct($sku, $productData);
        //get last element for product Id
        $newProducts = $soapProduct->getAllProducts();
        $product = end($newProducts);
        $productId = $product['product_id'];
    }
    //return update product or last product id
    echo json_encode(array('id' => $productId));

} else if (!empty($_FILES)) {
    //upload image
    $target_dir = "uploads/";  //temp path to folder
    $target_file = $target_dir . basename($_FILES["file"]["tmp_name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if (!empty($_FILES)) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check !== false) {
            //$check{['mime'];
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["file"]["size"] > 500000) {
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            //basename( $_FILES["file"]["name"]);

            //get product Id
            //soap create image file
        }
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
    }
    if (strpos($price, '/[^\p{L}\p{N}\s]/u')) {
        $price = preg_replace('/[^\p{L}\p{N}\s]/u', '', $price);
    }
    return $price;
}

?>