<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 19.04.2016
 * Time: 20:56
 */

include('../vendor/autoload.php');
include('../config.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Settings
{
    private $client;

    public function openSoap()
    {
        $this->client = MagentoXmlrpcClient::factory(array(
            'base_url' => constant("soapURL"),
            'api_key' => constant("soapUser"),
            'api_key' => constant("soapwd")
        ));
    }

    public function getShopInfo()
    {
        return $this -> client -> call('store.info', array());
    }
}