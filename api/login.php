<?php
/**
 * Created by IntelliJ IDEA.
 * Author: Yanick Schraner, Tom Ohme
 * Date: 04.04.16
 * Time: 07:31
 */
require_once '../vendor/autoload.php';
require_once '../api/dbconnect.php';
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

session_start();
session_destroy();
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['username']) && isset($data['password'])) {
    $api_user = $data['username'];
    $api_key = $data['password'];
    $result = $mysqli->query("SELECT * FROM magento.admin_user WHERE `username` = '$api_user'");
    $customer = $result->fetch_array();
    $hash = $customer['password'];
   	$hashArr = explode(':', $hash);
    if(!($hashArr[0] === md5($hashArr[1].$api_key))){
   		http_response_code(403);
   	}
    $_SESSION['username'] = $customer['username'];
}