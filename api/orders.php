<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 18.04.2016
 * Time: 17:36
 */

include('../vendor/autoload.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Orders
{
    private $client;
    private $mysqli;
    private $ini_array;

    public function __construct()
    {
        $this->ini_array = parse_ini_file("../php.ini");
        $this->mysqli = new mysqli("localhost", $this->ini_array['DBUSER'], $this->ini_array['DBPWD'], "magento");
    }

    public function openSoap()
    {
        $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => $this->ini_array['SOAPURL'],
            'api_user' => $this->ini_array['SOAPUSER'],
            'api_key'  => $this->ini_array['SOAPPWD'],
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