<?php
/**
 * Created by IntelliJ IDEA.
 * User: Janis
 * Date: 09.05.2016
 * Time: 12:57
 */

use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Design
{
    private $mysqli;
    private $ini_array;

    /**
     * Design constructor.
     */
    public function __construct()
    {
        if(file_exists("../php.ini"))
        {
            $this->ini_array = parse_ini_file("../php.ini");
        } else
        {
            $this->ini_array = parse_ini_file("./php.ini");
        }
        $this->mysqli = new mysqli("localhost",  $this->ini_array['DBUSER'],  $this->ini_array['DBPWD'], "magento");
    }

    /**
     * Get the selected color from the database
     * @return the color in string
     */
    public function getSelectedColor()
    {
        if(isset($this->mysqli))
        {
            $query = "SELECT color FROM css_color WHERE 1";

            if($stmt = $this->mysqli->prepare($query))
            {
                $stmt->execute();
                $stmt->bind_result($result);
                $stmt->fetch();
                $stmt->close();

                return $result;
            }
            return "";
        }
        return "";
    }

    /**
     * Save the selected color in the database
     * @param $selectedColor from the radiobutton
     */
    public function setSelectedColor($selectedColor)
    {
        $stmt = $this -> mysqli->prepare("UPDATE css_color SET color=? WHERE 1");
        $stmt->bind_param('s',$selectedColor);
        $stmt->execute();
        $stmt->close();
    }
}