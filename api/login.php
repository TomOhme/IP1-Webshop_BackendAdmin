<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 28.03.16
 * Time: 07:31
 */
require_once '../vendor/autoload.php';
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

//$_SESSION.start;
session_start();

session_destroy();

session_start();

if(isset($_POST['submit'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $api_user = $data['login'];
    $api_key = $data['password'];

    $client = MagentoXmlrpcClient::factory(array(
        'base_url' => 'http://127.0.0.1/magento/',
        'api_user' => $api_user,
        'api_key' => $api_key,
    ));
    $_SESSION['client'] = $client;
}