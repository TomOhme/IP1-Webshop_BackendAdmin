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
if (isset($_POST['productId']) && $_POST['product'] == 'loadProduct') {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $product = $soapProduct->getProductByID($productId);
    $product['price'] = formatPrice($product['price']);
    if ($product['special_price'] != null) {
        $product['special_price'] = formatPrice($product['special_price']);
    }
    $productImg = $soapProduct->getProductImage($product['product_id']);
    $productStock = $soapProduct->getProductStock($product['product_id']);
    $productStock[0]['qty'] = formatAmount($productStock[0]['qty']);
    //more category_ids foreach
    foreach($product['category_ids'] as $categoryId) {
        $productCategory[] = $soapProductGroup->getCategory($categoryId);
    }
    //$productCategory = $soapProductGroup->getCategory(end($product['category_ids']));
    //$allCategory = $soapProductGroup->getTree();
    echo json_encode(array('id' => $productId, 'updateProduct' => $product, 'updateImg' => $productImg, 'updateStock' => $productStock, 'updateCategory' => $productCategory)); //allCategory' => $allCategory
} else if (isset($_POST['productId']) && $_POST['product'] == 'delete') {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    //$product = $soapProduct->getProductByID($productId);
    $soapProduct->deleteProductByID($productId);
} else if (isset($_POST['productUpdateSave'])) {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $productData = array($_POST['category'], $_POST['unit'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['stock']); //TODO $_POST['picture'] and special Price Date From To
    if ($productId != null) {
        $soapProduct->updateProductByID($productId, $productData);
    } else {
        $allProducts = $soapProduct->getAllProducts(); //for sku
        $product = $soapProduct->getProductByID(count($allProducts)-1); //for sku
        $soapProduct->createProduct($product['id']+1, $productData);
    }
}

function formatDate($date){
    return  date_format(date_create($date), "d.m.Y");
}

function formatPrice($price){
    setlocale(LC_MONETARY,"de_CH");
    if(function_exists('money_format')){
        return money_format("%.2n", $price);
    } else {
        return "Fr. ". sprintf('%01.2f', $price);
    }
}

function formatAmount($amount){
    return number_format($amount,0);
}
?>