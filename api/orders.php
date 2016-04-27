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
        $orderStatus = 'cancelled';
        $comment = 'Die Bestellung wurde durch easy admin storniert.';
        $sendEmailToCustomer = true;
        return $this -> client -> call('sales_order.addComment', array($ID, $orderStatus, $comment, $sendEmailToCustomer));
    }

    /**
    * Currently not activ!
    * cancel a specific order
    * @param Order ID
    * @return bool
    */
    public function reopenOrder($ID) {
        $orderStatus = 'reopen';
        $comment = 'Die Bestellung wurde durch easy admin wieder eröffnet.';
        $sendEmailToCustomer = true;
        return $this -> client -> call('sales_order.addComment', array($ID, $orderStatus, $comment, $sendEmailToCustomer));
    }

    /**
    * close a specific order
    * @param Order ID
    * @return bool
    */
    public function closeOrder($ID){
        $orderStatus = 'closed';
        $comment = 'Die Bestellung wurde durch easy admin geschlossen.';
        $sendEmailToCustomer = true;
        return $this -> client -> call('sales_order.addComment', array($ID, $orderStatus, $comment, $sendEmailToCustomer));
    }

    /**
    * Translates the order status and adjusts it to match the simple order process
    * @param Order Array
    * @return german order status
    */
    public function getOrderStatus($order) {
        $status = $order['status'];
        switch ($order['status']) {
            case "pending":
                $status = "Offen";
                break;
            case "processing":
                $status = "Offen";
                break;
            case "reopen":
                $status = "Wiedereröffnet";
                break;
            case "complete":
                $status = "Abgeschlossen";
                break;
            case "closed":
                $status = "Abgeschlossen";
                break;
            case "cancelled":
                $status = "Storniert";
                break;
        }
        return $status;
    }
}