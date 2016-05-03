<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
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
    private $ini_array;

    public function __construct()
    {
        if(file_exists("../php.ini")){
            $this->ini_array = parse_ini_file("../php.ini");
        } else {
            $this->ini_array = parse_ini_file("./php.ini");
        }
    }

    public function openSoap()
    {      
        $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => $this->ini_array['SOAPURL'],
            'api_user' => $this->ini_array['SOAPUSER'],
            'api_key'  => $this->ini_array['SOAPPWD'],
        ));
    }

    public function getShopInfo()
    {
        return $this -> client -> call('store.info', array());
    }
}