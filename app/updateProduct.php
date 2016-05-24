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
    /*$product['price'] = formatPrice($product['price']);
    if ($product['special_price'] != null) {
        $product['special_price'] = formatPrice($product['special_price']);
    }*/
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
    //create/update product
    $values = array();
    parse_str($_POST['productData'], $values);
    $values['category_ids'] = $_POST['category_ids'];
    $productId = isset($values['productId']) ? $values['productId'] : null;
    $values['price'] = unformatPrice($values['price']);

    //get all parent Category ids
    /*$category = $soapProductGroup->getCategory($values['category_ids']);
    $path = $category->getPath();
    $ids = explode('/', $path);
    unset($ids[0]);*/

    if ($values['specialPrice'] != null) {
        $values['specialPrice'] = unformatPrice($values['specialPrice']);
    }
    $productData = $soapProduct->createCatalogProductEntity($ids, $values['unit'], $values['title'], $values['short_description'],
                                                            $values['price'], $values['stock'], $values['specialPrice'], $values['specialFromDate'], $values['specialToDate']);
    if ($productId != null) {
        //update product
        $soapProduct->updateProductByID($productId, $productData);
    } else {
        //create product
        $allProducts = $soapProduct->getAllProducts();
        $sku = $allProducts[count($allProducts) -1]['sku'];
        $sku++;
        $productId = $soapProduct->createProduct($sku, $productData);
    }
    //return update product id or last product id from create product
    echo json_encode(array('id' => $productId));

} else if (!empty($_FILES)) {
    //upload image
    $filename = $_FILES['file']['tmp_name'];
    $mime = $_FILES['file']['type'];
    $name = $_FILES['file']['name'];
    $productId = $_POST['id'];
    $images = $soapProduct->getProductImage($productId);
    foreach ($images as $image) {
        $soapProduct->removeProductImage($productId,$image['file']);
    }
    $soapProduct->createProductImage($filename, $mime, $name,$productId);
}

function formatDate($date){
    return  date_format(date_create($date), "d.m.Y");
}

/*function formatPrice($price){
    return number_format($price, 2, ',', "'");
}*/

function formatAmount($amount){
    setlocale(LC_ALL, "de_CH");
    return number_format($amount,0, ".", "'");
}

function unformatPrice($price) {
    /*if (strpos($price, 'Fr.') !== false) {
        $price = str_replace('Fr.', '', $price);
    }*/
    if (strpos($price, '/[^\p{L}\p{N}\s]/u')) {
        $price = preg_replace('/[^\p{L}\p{N}\s]/u', '', $price);
    }
    return $price;
}

?>