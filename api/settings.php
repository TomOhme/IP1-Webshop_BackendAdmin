<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis Angst, Yanick Schraner
 * Date: 19.04.2016
 * Time: 20:56
 */

if(file_exists("../vendor/autoload.php")){
    include('../vendor/autoload.php');
}else{
    include('./vendor/autoload.php');
}
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Settings
{
    private $client;
    private $mysqli;
    private $ini_array;

    public function __construct()
    {
        if(file_exists("../php.ini")){
            $this->ini_array = parse_ini_file("../php.ini");
        } else {
            $this->ini_array = parse_ini_file("./php.ini");
        }
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

    public function getShopName()
    {
        $query = "SELECT `name` FROM `store_title` WHERE 1";

        $result = $this->mysqli->query($query);
        $shopname = mysqli_fetch_assoc($result);

        return $shopname["name"];
    }

    /**
    * gets the shipping and banktransfer settings if active 
    * @return array with settings 
    */
    public function getShippingSettings(){
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/flatrate/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active = $rows[0][0];
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/banktransfer/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active += $rows[0][0];
        $settings = array();
        if($active == 2){
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/flatrate/title'";
            $result = $this->mysqli->query($query);
            $title = $result->fetch_all();
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/flatrate/price'";
            $result = $this->mysqli->query($query);
            $price = $result->fetch_all();
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/banktransfer/instructions'";
            $result = $this->mysqli->query($query);
            $instructions = $result->fetch_all();   
            $settings = array('title' => $title[0][0], 'price' => $price[0][0], 'instructions' => $instructions[0][0]); 
        }
        $result->free();
        return $settings;
    }

    /**
    * sets the shipping and banktransfer settings if active 
    * @param shipping title
    * @param price for shipping
    * @param instructions for payment
    */
    public function setShippingSettings($title, $price, $instructions){
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/flatrate/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active = $rows[0][0];
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/banktransfer/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active += $rows[0][0];
        $settings = array();
        if($active == 2){
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/flatrate/title';");
            $stmt->bind_param("s",$title);
            $stmt->execute();
            $stmt->close();
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/flatrate/price';");
            $stmt->bind_param("s",$price);
            $stmt->execute();
            $stmt->close();
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'payment/banktransfer/instructions';");
            $stmt->bind_param("s",$instructions);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
    * activates the shipping and banktransfer availability
    * @return void
    */
    public function activateShipping(){
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=1   WHERE core_config_data.path LIKE 'carriers/flatrate/active';");
        $stmt->execute();
        $stmt->close();
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=1   WHERE core_config_data.path LIKE 'payment/banktransfer/active';");
        $stmt->execute();
        $stmt->close();
    }

    /**
    * deactivates the shipping and banktransfer availability
    * @return void
    */
    public function deactivateShipping(){
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=0   WHERE core_config_data.path LIKE 'carriers/flatrate/active';");
        $stmt->execute();
        $stmt->close();
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=0   WHERE core_config_data.path LIKE 'payment/banktransfer/active';");
        $stmt->execute();
        $stmt->close();
    }

    /**
    * gets the pickup and pay on pickup settings if active 
    * @return array with pickupDestination and pickupTime
    */
    public function getPickUpSettings(){
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/pickup/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active = $rows[0][0];
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/pickup/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active += $rows[0][0];
        $settings = array();
        if($active == 2){
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/pickup/name'";
            $result = $this->mysqli->query($query);
            $destination = $result->fetch_all();
            $this->mysqli->set_charset("utf8");
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/pickup/custom_form_text'";
            $result = $this->mysqli->query($query);
            $pickupTime = $result->fetch_all();
            $settings = array('pickupDestination' => $destination[0][0], 'pickupTime' => $pickupTime[0][0]); 
        }
        $result->free();
        return $settings;
    }

    /**
    * sets the pickup and pay on pickup settings if active 
    * @param pickupDestination
    * @param pickupTime
    */
    public function setPickUpSettings($pickupDestination, $pickupTime){
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/pickup/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active = $rows[0][0];
        $query = "SELECT value FROM core_config_data WHERE path LIKE 'payment/pickup/active'";
        $result = $this->mysqli->query($query);
        $rows = $result->fetch_all();
        $active += $rows[0][0];
        $settings = array();
        if($active == 2){
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/pickup/name';");
            $stmt->bind_param("s",$pickupDestination);
            $stmt->execute();
            $stmt->close();
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'payment/pickup/custom_form_text';");
            $stmt->bind_param("s",$pickupTime);
            $stmt->execute();
            $stmt->close();
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'payment/pickup/custom_info_text';");
            $stmt->bind_param("s",$pickupTime);
            $stmt->execute();
            $stmt->close();
        }
    }

    /**
    * activates the pickup and pay on pickup module
    * @param void
    */
    public function activatePickUp(){
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=1   WHERE core_config_data.path LIKE 'payment/pickup/active';");
        $stmt->execute();
        $stmt->close();
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=1   WHERE core_config_data.path LIKE 'carriers/pickup/active';");
        $stmt->execute();
        $stmt->close();
    }

    /**
    * deactivates the pickup and pay on pickup module
    * @param void
    */
    public function deactivatePickUp(){
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=0   WHERE core_config_data.path LIKE 'payment/pickup/active';");
        $stmt->execute();
        $stmt->close();
        $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=0   WHERE core_config_data.path LIKE 'carriers/pickup/active';");
        $stmt->execute();
        $stmt->close();
    }
}