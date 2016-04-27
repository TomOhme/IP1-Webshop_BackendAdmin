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
if (isset($_POST['productId']) && $_POST['product'] == 'update') {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
    $product = $soapProduct->getProductByID($productId);
    $productImg = $soapProduct->getProductImage($product['product_id']);
    $productStock = $soapProduct->getProductStock($product['product_id']);
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
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    //picture = $_POST['picture'];
    $productData = array($category, $title, $description, $price, $stock); //TODO other fields empty
    if ($productId != null) {
        $soapProduct->updateProductByID($productId, $productData);
    } else {
        $soapProduct->createProduct(9999999, $productData); //TODO new id - ongoing primary key id
    }
}
?>