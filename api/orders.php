<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 18.04.2016
 * Time: 17:36
 */

include('../vendor/autoload.php');
include('../config.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Orders
{
    private $client;

    public function openSoap()
    {
        $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => constant("soapURL"),
            'base_user' => constant("soapUser"),
            'api_key' => constant("soapwd")
        ));
    }

    /**
     * Get all orders
     * @return Array of all orderd
     */
    public function getAllOrders()
    {
        return $this -> client -> call('order.list', array());
    }

    public function getOrderByID($ID)
    {
        return $this -> client -> call('sales_order.info', array($ID));
    }
}