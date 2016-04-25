<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 19.04.2016
 * Time: 20:56
 */

include('../vendor/autoload.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Settings
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
        $this->client = MagentoXmlrpcClient::factory(array(
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