<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 13.04.2016
 * Time: 11:49
 */

include("../api/product.php");

$soap = new Product();
$soap -> openSoap();
if (isset($_POST['articleId'])) {
    $articleId = isset($_POST['articleId']) ? $_POST['articleId'] : null;
    $update_article = $soap->getProductByID($articleId);
    $update_img = $soap->getProductImage($update_article['product_id']);
    $update_stock = $soap->getProductStock($update_article['product_id']);
    echo json_encode(array('id' => $articleId, 'update_article' => $update_article, 'update_img' => $update_img, 'update_stock' => $update_stock));
}
?>