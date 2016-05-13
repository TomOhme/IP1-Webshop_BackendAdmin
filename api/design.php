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
            $query = "SELECT color FROM store_design WHERE 1";

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
        $colorPath = "";
        
        if($selectedColor == "blue") 
        {
            $colorPath = "skin/frontend/webshop/default/css/blue.css";
        }
        else if($selectedColor == "red")
        {
            $colorPath = "skin/frontend/webshop/default/css/red.css";
        }
        else if($selectedColor == "green")
        {
            $colorPath = "skin/frontend/webshop/default/css/green.css";
        }
        else if($selectedColor == "beige")
        {
            $colorPath = "skin/frontend/webshop/default/css/beige.css";
        }
        else if($selectedColor == "gray")
        {
            $colorPath = "skin/frontend/webshop/default/css/gray.css";
        }
        
        $stmt = $this -> mysqli->prepare("UPDATE store_design SET color=?, path=? WHERE 1");
        $stmt->bind_param('ss',$selectedColor, $colorPath);
        $stmt->execute();
        $stmt->close();
    }

    public function getImage($logoJumb)
    {
        if($logoJumb == "logo")
        {
            $query = "SELECT value FROM core_config_data WHERE path=?";
            $path = "design/header/logo_src";
            
            $stmt = $this->mysqli->prepare("SELECT value FROM core_config_data WHERE path =?");
            $stmt->bind_param('s', $path);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();

            return $result;
        }
        else if($logoJumb == "jumbotron")
        {
            $query = "SELECT jumbotron FROM store_design WHERE 1";

           $stmt = $this->mysqli->prepare($query);
            
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();

            return $result;
        }
    }

    public function updatePicture($img, $imgPath, $fileName, $pathStart, $time, $logoJumb)
    {
        $target_file = $imgPath . basename($img['name']);

        $errorMsg = "";

        $imgFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $check = getimagesize($img['tmp_name']);

        if($check == false)
        {
            return $errorMsg = "Die Datei ist kein Bild";
        }

        if($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "jpeg" && $imgFileType != "gif")
        {
            return $errorMsg = "Nur JPG, PNG oder GIF Dateien sind erlaubt";
        }

        if($img['size'] > 500000)
        {
            return $errorMsg = "Das Bild ist zu gross";
        }
        
        if($logoJumb == "logo")
        {
            $imgFilePath = "images/" . $fileName . $time . ".png";
            
            $path = "design/header/logo_src";
            
            $stmt = $this -> mysqli->prepare("UPDATE core_config_data SET value=? WHERE path =?");
            $stmt->bind_param('ss',$imgFilePath, $path);
            $stmt->execute();
            $stmt->close();
            
            $imgFilePath = $imgPath . $fileName . $time . ".png";
        }
        else if($logoJumb == "jumbotron")
        {
            $imgFilePath = $imgPath . $fileName . $time . ".png";
            $content = "<div class=\"page-title\"><h2>Home Page</h2><p><img src=\"{{media url=\"wysiwyg/" . $fileName . $time . ".png }}\" /></p></div>";
            
            $title = "Home page";
            
            $stmt = $this -> mysqli->prepare("UPDATE cms_page SET content=? WHERE title=?");
            $stmt->bind_param('ss',$content, $title);
            $stmt->execute();
            $stmt->close();
            
            $stmt = $this -> mysqli -> prepare ("UPDATE store_design SET jumbotron =? WHERE 1");
            $stmt -> bind_param('s', $imgFilePath);
            $stmt -> execute();
            $stmt -> close();
        }
        
        move_uploaded_file($img['tmp_name'], $pathStart . $imgFilePath);
    }

    public function cleanCache()
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