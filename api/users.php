<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 18.04.2016
 * Time: 17:11
 */

include('../vendor/autoload.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class user
{
    private $client;
    private $ini_array;

    public function __construct()
    {
        $this->ini_array = parse_ini_file("../php.ini");
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
        $user = $this->ini_array["DBUSER"];
        $pwd = $this->ini_array["DBPWD"];
        $mysqli = new mysqli("localhost", $user, $pwd, "magento");

        if ($mysqli ->connect_errno){
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
        }

        $allusers = array();
        $data = $this->client ->call('customer.list', array());
        foreach($data as $line){

            $user = $this->client->call('customer_address.list', array($line['customer_id']));
            array_push($line, $user[0]['street'], $user[0]['postcode'], $user[0]['city'], $user[0]['telephone']);

            $query1 = "SELECT `value` FROM `customer_entity_datetime` WHERE `entity_id` = ".$line['customer_id'];
            $result = $mysqli->query($query1);
            $row = mysqli_fetch_assoc($result);
            array_push($line, $row["value"]);

            $query2 = "SELECT `subscriber_status` FROM `newsletter_subscriber` WHERE `customer_id` = ".$line['customer_id'];
            $result2 = $mysqli->query($query2);
            $row2 = mysqli_fetch_assoc($result2);
            array_push($line, $row2["subscriber_status"]);

            array_push ($allusers, $line);

        }
        //var_dump($allusers);
        return $allusers;
    }

    /**
     * Get all information of a specific user by it's id
     * @param $ID of customers
     * @return array of customers with the id
     */
    public function getUserByID($ID)
    {
        return $this -> client -> call('customer_address.info', array($ID));
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