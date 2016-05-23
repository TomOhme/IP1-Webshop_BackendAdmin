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

    /**
     * Design constructor.
     */
    public function __construct(){
        if(file_exists("../config.php"))
        {
            include("../config.php");
        } else
        {
            include("./config.php");
        }
        $this->mysqli = new mysqli("localhost",  DBUSER,  DBPWD, "magento");
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

    /**
     * Get the image path from the databse
     * @param $logoJumb String "logo" or "jumbotron"
     * @return path to the image
     */
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

    /**
     * Update the image path in the database
     * @param $img the target image
     * @param $imgPath path to the file in magetno
     * @param $fileName name of the file
     * @param $pathStart the path start
     * @param $time current time
     * @param $logoJumb "logo" or "jumbotron"
     * @return string errormsg
     */
    public function updatePicture($img, $imgPath, $fileName, $pathStart)
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
        
        if($fileName == "jumbotron")
        {
            $imgFilePath = $imgPath . $fileName . ".png";

            $content = "<div class=\"page-title\"><h2>Home Page</h2><p><img src=\"{{media url=\"wysiwyg/" . $fileName . ".png }}\" /></p> <p>{{widget type=\"catalog/product_widget_new\" display_type=\"all_products\" products_count=\"4\" template=\"catalog/product/widget/new/content/new_grid.phtml\"}}</p></div>";
            
            $title = "home";
            
            $stmt = $this -> mysqli->prepare("UPDATE cms_page SET content=? WHERE identifier=?");
            $stmt->bind_param('ss',$content, $title);
            $stmt->execute();
            $stmt->close();
            
            $stmt = $this -> mysqli -> prepare ("UPDATE store_design SET jumbotron =? WHERE 1");
            $stmt -> bind_param('s', $imgFilePath);
            $stmt -> execute();
            $stmt -> close();
        }

        foreach (glob($pathStart . "magento/" . $imgPath . $fileName . ".png") as $file)
        {
            unlink ($file);
        }

        move_uploaded_file($img['tmp_name'], $pathStart . "magento/" . $imgFilePath);

        if($fileName == "logo")
        {
            $imgFilePath = $pathStart . "magento/media/email/logo/default/" . $fileName . ".png";

            foreach (glob($imgFilePath) as $file)
            {
                unlink($file);
            }

            copy($pathStart . "magento/" . $imgPath . $fileName . ".png", $imgFilePath);
        }
    }

    public function resetImg($imgPath, $imgName, $pathStart)
    {
        foreach(glob($pathStart . $imgPath . "logo.png") as $file)
        {
            unlink ($file);
        }

        copy($pathStart . "magento/" . $imgPath . "blank.png", $pathStart . "magento/" . $imgPath . $imgName);

        if($imgName = "logo.png")
        {
            foreach(glob($pathStart . "magento/media/email/logo/default/". $imgName) as $file)
            {
                unlink($file);
            }

            copy($pathStart . "magento/" . $imgPath . "blank.png", $pathStart . "magento/media/email/logo/default/" . $imgName);
        }
    }

    /**
     * Cleans the magentocache
     */
    public function cleanCache($pathStart)
    {
        foreach (glob($pathStart . "magento/var/cache/*", GLOB_ONLYDIR) as $dir)
        {
            foreach(glob($dir . "/*") as $file)
            {
                unlink($file);
            }

            rmdir($dir);
        }
    }
}