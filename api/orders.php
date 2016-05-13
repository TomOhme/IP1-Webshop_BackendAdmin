<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick Schraner, Janis
 * Date: 18.04.2016
 * Time: 17:36
 */

if(file_exists("../vendor/autoload.php")){
    require_once('../vendor/autoload.php');
}else{
    require_once('./vendor/autoload.php');
}
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Orders
{
    private $client;

    public function __construct() {
        if(file_exists("../config.php")){
            require_once("../config.php");
        } else{
            require_once("./config.php");
        }
    }

    public function openSoap() {      
        $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => SOAPURL,
            'api_user' => SOAPUSER,
            'api_key'  => SOAPPWD,
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
        $orderStatus = 'complete';
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