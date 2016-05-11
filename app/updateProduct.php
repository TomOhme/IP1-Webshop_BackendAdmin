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
    } else {
        $allProducts = $soapProduct->getAllProducts();
        $sku = $allProducts[count($allProducts) -1]['sku'];
        $sku++;
        $soapProduct->createProduct($sku, $productData);
        //TODO create Image
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
?>