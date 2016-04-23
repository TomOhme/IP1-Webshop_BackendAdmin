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
if (isset($_POST['productId'])) {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $updateProduct = $soapProduct->getProductByID($productId);
    $updateImg = $soapProduct->getProductImage($updateProduct['product_id']);
    $updateStock = $soapProduct->getProductStock($updateProduct['product_id']);
    $updateCategory = $soapProductGroup->getCategory(end($updateProduct['category_ids']));
    $allCategory = $soapProductGroup->getTree();
    echo json_encode(array('id' => $productId, 'updateProduct' => $updateProduct, 'updateImg' => $updateImg, 'updateStock' => $updateStock, 'updateCategory' => $updateCategory, 'allCategory' => $allCategory));
}
?>