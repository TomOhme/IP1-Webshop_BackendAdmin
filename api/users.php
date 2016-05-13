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

    public function __construct()
    {
        if(file_exists("../config.php")){
            include("../config.php");
        } else{
            include("./config.php");
        }
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
     * Get all Users
     * @return array of users
     * More: http://devdocs.magento.com/guides/m1x/api/soap/customer/customer.list.html
     */
    public function getAllUsers()
    {
        $user = DBUSER;
        $pwd = DBPWD;
        $mysqli = new mysqli("localhost", $user, $pwd, "magento");

        if ($mysqli ->connect_errno){
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
        }

        $allusers = array();
        $data = $this->client ->call('customer.list', array());
        foreach($data as $line){

            $user = $this->client->call('customer_address.list', array($line['customer_id']));
            $addressinfos = array($user[0]['street'], $user[0]['postcode'], $user[0]['city'], $user[0]['telephone']);
            foreach($addressinfos as $addressinfo){
                if(!is_null($addressinfo)){
                    array_push($line,$addressinfo);
                }
            }

            $query1 = "SELECT `value` FROM `customer_entity_datetime` WHERE `entity_id` = ".$line['customer_id'];
            $result = $mysqli->query($query1);
            $row = mysqli_fetch_assoc($result);
            if(!is_null($row["value"])){
                array_push($line,$row["value"]);
            }

            $query2 = "SELECT `subscriber_status` FROM `newsletter_subscriber` WHERE `customer_id` = ".$line['customer_id'];
            $result2 = $mysqli->query($query2);
            $row2 = mysqli_fetch_assoc($result2);
            if(!is_null($row2["subscriber_status"])) {
                array_push($line, $row2["subscriber_status"]);
            }

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