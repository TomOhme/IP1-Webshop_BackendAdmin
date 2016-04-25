<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 18.04.2016
 * Time: 17:11
 */

include('');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class User
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
     * Get all Users
     * @return array of users
     * More: http://devdocs.magento.com/guides/m1x/api/soap/customer/customer.list.html
     */
    public function getAllUsers()
    {
        return $this -> client -> call('customer_adress.list', array());
    }

    /**
     * Get all information of a specific user by it's id
     * @param $ID of customers
     * @return array of customers with the id
     */
    public function getUserByID($ID)
    {
        return $this -> client -> call('customer_adress.info', array($ID));
    }

    /**
     * Delete the user by id
     * @param $ID of the user
     * @return boolean
     */
    public function deleteUserByID($ID)
    {
        return $this -> client -> call('customer.delete', array($ID));
    }

}