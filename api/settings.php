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

    public function __construct()
    {
        if(file_exists("../config.php")){
            include("../config.php");
        } else{
            include("./config.php");
        }
        $this->mysqli = new mysqli("localhost",  DBUSER,  DBPWD, "magento");
    }

    public function openSoap()
    {      
        $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => SOAPURL,
            'api_user' => SOAPUSER,
            'api_key'  => SOAPPWD,
        ));
    }

    /**
     * Get the shopname
     * @return shopname
     */
    public function getShopName()
    {     
        $this->mysqli->set_charset("utf8");
        
        $query = "SELECT name FROM core_store_group WHERE website_id = 1";

        $result = $this->mysqli->query($query);
        $shopname = mysqli_fetch_assoc($result);

        return $shopname["name"];
    }

    /**
     * Set the shopname
     * @param $shopName
     */
    public function setShopname($shopName)
    {       
        $this->mysqli->set_charset("utf8");
        $stmt = $this -> mysqli->prepare("UPDATE core_store_group SET name=? WHERE website_id = 1");
        $stmt->bind_param('s',$shopName);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Get the content from the contact block in footer
     * @return string
     */
    public function getContact()
    {
        $contact = "";
        
        $identifier = "footer_contact";
        
        $stmt = $this -> mysqli->prepare("SELECT content FROM cms_block WHERE identifier=?");
        $stmt->bind_param('s', $identifier);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();

        $doc = new DOMDocument();
        $doc->loadHTML($result);
        foreach($doc->getElementsByTagName('p') as $para) {
            $contact .= $para->textContent;
            $contact .= "\r\n";
        }

        return $contact;
    }
    
    /**
     * Set the content from the contact block in footer
     * @param $contactContent
     */
    public function setContact($contactContent)
    {
        $split = explode("\r\n", $contactContent);
		
        $content = "<div class=\"links\">";
        $content .= "<div class=\"block-title\" style=\"text-align: left;\"><strong><span>Kontakt</span></strong></div>";
        
        for($i = 0 ; $i < count($split) ; $i++)
        {
            $content .= "<p style=\"text-align: left;\">";
            $content .= $split[$i];
            $content .= "</p>";
        }
        
        $content .= "</div>";
        
        $identifier = "footer_contact";
        
        $this->mysqli->set_charset("utf8");
        $stmt = $this -> mysqli->prepare("UPDATE cms_block SET content=? WHERE identifier=?");
        $stmt->bind_param('ss',$content, $identifier);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Get the phone number from database
     * @return Phone number
     */
    public function getPhone()
    {
        $path = "general/store_information/phone";

        $stmt = $this -> mysqli -> prepare("SELECT value FROM core_config_data WHERE path =?");
        $stmt -> bind_param('s', $path);
        $stmt -> execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        
        return $result;
    }

    /**
     * Set the phone number in the databse
     * @param $phoneNr
     */
    public function setPhone($phoneNr)
    {
        $path = "general/store_information/phone";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $phoneNr, $path);
        $stmt -> execute();
        $stmt -> close();
    }

    /**
     * Get the email sender from the database
     * @return email sender
     */
    public function getEmailSender()
    {
        $path = "trans_email/ident_general/name";
        
        $stmt = $this -> mysqli -> prepare("SELECT value FROM core_config_data WHERE path =?");
        $stmt -> bind_param('s', $path);
        $stmt -> execute();
        $stmt -> bind_result($result);
        $stmt -> fetch();
        $stmt -> close();
        
        return $result;
    }

    /**
     * Set the email sender in the database
     * @param $emailSender
     */
    public function setEmailSender($emailSender)
    {        
        $path = "trans_email/ident_general/name";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $emailSender, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_sales/name";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $emailSender, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_support/name";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $emailSender, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_custom1/name";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $emailSender, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_custom2/name";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $emailSender, $path);
        $stmt -> execute();
        $stmt -> close();
    }

    /**
     * Get the email from the database
     * @return email
     */
    public function getEmail()
    {
        $path = "trans_email/ident_general/email";
        
        $stmt = $this -> mysqli -> prepare("SELECT value FROM core_config_data WHERE path =?");
        $stmt -> bind_param('s', $path);
        $stmt -> execute();
        $stmt -> bind_result($result);
        $stmt -> fetch();
        $stmt -> close();
        
        return $result;
    }

    /**
     * Set the email in the database
     * @param $email
     */
    public function setEmail($email)
    {        
        $path = "trans_email/ident_general/email";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $email, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_sales/email";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $email, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_support/email";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $email, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_custom1/email";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $email, $path);
        $stmt -> execute();
        $stmt -> close();
        
        $path = "trans_email/ident_custom2/email";
        
        $stmt = $this -> mysqli -> prepare("UPDATE core_config_data SET value =? WHERE path =?");
        $stmt -> bind_param('ss', $email, $path);
        $stmt -> execute();
        $stmt -> close();
    }

    /**
    * returns the actual capcha state
    * @return boolean
    */
    public function getCapchaState(){
        $result = $this->mysqli->query("SELECT value FROM magento.core_config_data WHERE path LIKE 'customer/captcha/enable';");
        $state = $result->fetch_all();
        if($state[0][0] == "1"){
            return true;
        }
        return false;
    }

    /**
    * sets capcha state
    * @param boolean
    */
    public function setCapchaState($state){
        $value = 0;
        if($state == "true"){
            $value = 1;
        }
        $this->mysqli->query("UPDATE magento.core_config_data SET value=$value WHERE path LIKE 'customer/captcha/enable';");
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
            $this->mysqli->set_charset("utf8");
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
            $this->mysqli->set_charset("utf8");
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/flatrate/title';");
            $stmt->bind_param("s",$title);
            $stmt->execute();
            $stmt->close();
            $this->mysqli->set_charset("utf8");
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/flatrate/price';");
            $stmt->bind_param("s",$price);
            $stmt->execute();
            $stmt->close();
            $this->mysqli->set_charset("utf8");
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
            $this->mysqli->set_charset("utf8");
            $query = "SELECT value FROM core_config_data WHERE path LIKE 'carriers/pickup/name'";
            $result = $this->mysqli->query($query);
            $destination = $result->fetch_all();
            $this->mysqli->set_charset("utf8");
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
            $this->mysqli->set_charset("utf8");
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'carriers/pickup/name';");
            $stmt->bind_param("s",$pickupDestination);
            $stmt->execute();
            $stmt->close();
            $this->mysqli->set_charset("utf8");
            $stmt = $this -> mysqli->prepare("UPDATE magento.core_config_data SET  value=?   WHERE core_config_data.path LIKE 'payment/pickup/custom_form_text';");
            $stmt->bind_param("s",$pickupTime);
            $stmt->execute();
            $stmt->close();
            $this->mysqli->set_charset("utf8");
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
    
     /**
     * Cleans the magentocache
     */
    public function cleanCache($pathStart)
    {
        foreach (glob($pathStart . "var/cache/*", GLOB_ONLYDIR) as $dir)
        {
            foreach(glob($dir . "/*") as $file)
            {
                unlink($file);
            }

            rmdir($dir);
        }
    }
}