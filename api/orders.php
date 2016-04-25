<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick Schraner, Janis
 * Date: 18.04.2016
 * Time: 17:36
 */

include('../vendor/autoload.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Orders
{
    private $client;
    private $ini_array;

    public function __construct() {
        $this->ini_array = parse_ini_file("../php.ini");
    }

    public function openSoap() {      
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
    public function getAllOrders() {
        return $this -> client -> call('order.list', array());
    }

    /**
    * Get a specific order by its id
    * @param Order ID
    * @return Array of salesOrderEntity
    */
    public function getOrderByID($ID) {
        return $this -> client -> call('sales_order.info', array($ID));
    }

    /**
    * cancel a specific order
    * @param Order ID
    * @return bool
    */
    public function cancleOrder($ID) {
        return $this -> client -> call($session, 'sales_order.cancel', $ID);
    }

    public function getOrderStatus($ID) {
        $order = $this -> client -> call($session, 'sales_order.cancel', $ID);
        return $order['status'];
    }

    public function closeOrder($ID){
        return $this -> client -> call($session, 'sales_order_shipment.create', $ID);
    }
}