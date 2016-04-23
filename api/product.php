<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 21.03.16
 * Time: 12:18
 */

include('../vendor/autoload.php');
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class product {
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
     * Get all Products
     * @return array of catalogProductEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.list.html
     */
    public function getAllProducts()
    {
        return $this->client->call('product.list', array());
    }

    /**
     * Get all information of a specific product by it's id
     * @param $ID
     * @return array with all product information
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.info.html
     */
    public function getProductByID($ID)
    {
        return $this->client->call('catalog_product.info', array($ID));
    }

    public function getProductStock($ID)
    {
        return $this->client->call('cataloginventory_stock_item.list', array($ID));
    }
    /**
     * Get all product images of a specific product by it's id
     * @param $ProductID
     * @return array of of catalogProductImageEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.list.html
     */
    public function getProductImage($ProductID)
    {
        return $this->client->call('catalog_product_attribute_media.list', array($ProductID));
    }

    /**
     * Create new Product by SKU and an Array of catalogProductEntity
     * @param $sku string
     * @param $productData Array
     * @return ID of the created product
     */
    public function createProduct($sku, $productData)
    {
        $attributeSets = $this->client->call('product_attribute_set.list');
        $attributeSet = current($attributeSets);
        $categoryIDs = array();
        foreach ($productData['category_ids'] as $category) {
            $stmt = $this -> mysqli->prepare("SELECT DISTINCT catalog_category_entity_varchar.entity_id FROM catalog_category_entity_varchar WHERE VALUE=?;");
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $stmt->bind_result($categoryID);
            $stmt->fetch();
            array_push($categoryIDs, $categoryID);
            $stmt->close();
        }
        $productData['category_ids'] = $categoryIDs;
        return $this->client->call('catalog_product.create', array('simple', $attributeSet['set_id'], $sku, $productData));
    }

    /**
     * Creates a new product image an assignes it to the according product
     * @param $filename
     * @param $mime (this can be image/jpeg, image/jpg, image/png, image/gif)
     * @param $name (display name in magento)
     * @param $productId
     * @return string stored image file name with path
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.create.html
     */
    public function createProductImage($filename, $mime, $name, $productId)
    {
        if (pathinfo(urldecode($filename), PATHINFO_EXTENSION) == "jpg") {
            $img = imagecreatefromstring(file_get_contents(urldecode($filename)));
            imagepng($img, $name . ".png");
            $content = base64_encode($name . ".png");
            $mime = "image/png";
        } else {
            $content = base64_encode(file_get_contents(urldecode($filename)));
        }

        $file = array(
            'content' => $content,
            'mime' => $mime,
            'name' => $name
        );

        return $this->client->call(
            'catalog_product_attribute_media.create',
            array(
                $productId,
                array('file'=>$file, 'label'=>$name, 'position'=>'1', 'types'=>array('image', 'small_image', 'thumbnail'), 'exclude'=>null)
            )
        );
    }

    /**
     * Updates specific product by it's IDxs
     * @param $ID
     * @param $productData
     * @return boolean
     */
    public function updateProductByID($ID, $productData)
    {
        return $this->client->call('catalog_product.update', array($ID, $productData));
    }

    /**
     * Creates a new product image an assignes it to the according product
     * @param $newFilename
     * @param $mime (this can be image/jpeg, image/jpg, image/png, image/gif)
     * @param $name (display name in magento)
     * @param $oldFilename (Magento stored path and filename)
     * @param $productId
     * @return string stored image file name with path
     */
    public function updateProductImage($newFilename, $mime, $name, $oldFilename, $productId)
    {
        $this->removeProductImage($productId, $oldFilename);
        return $this->createProductImage($newFilename, $mime, $name, $productId);
    }

    /**
     * Delete product by it's ID
     * @param $ID
     * @return boolean
     */
    public function deleteProductByID($ID)
    {
        return $this->client->call('catalog_product.delete', array($ID));
    }

    /**
     * Delete product image by it's magento filename (+path) and it's product id
     * @param $productID
     * @param $filename
     * @return boolean / int
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.remove.html
     */
    public function removeProductImage($productID, $filename)
    {
        return $this->client->call('catalog_product_attribute_media.remove', array($productID, $filename));
    }

    /**
     * Setup product attributes
     * @param $categories       Array of Categories (String)
     * @param $websites         Array of Website ID's (Int) visible Website ID (Default: array("1"))
     * @param $prodName         String
     * @param $description      String
     * @param $shortDescription String
     * @param $weight           String
     * @param $status           String (Default: 1)
     * @param $visibility       String (Default 4)
     * @param $price            String
     * @param $special_price    String Optional
     * @param $special_from_date String Optional
     * @param $special_to_date  String Optional
     * @param $stock            String
     * @return array with all product values
     */
    public function createCatalogProductEntity($categories, $unit, $prodName, $shortDescription, $price, $stock,
        $weight = "1", $websites = array("1"), $description = '', $status = '1', $visibility = "4", $special_price = '', $special_from_date = '', $special_to_date = '')
    {
        $prodNameURL = strtolower($prodName);
        return array(
            'category_ids' => $categories,
            'unit' => $unit,
            'websites' => $websites,
            'name' => $prodName,
            'description' => $description,
            'short_description' => $shortDescription,
            'weight' => $weight,
            'status' => $status,
            'url_key' => $prodNameURL,
            'visibility' => $visibility,
            'price' => $price,
            'special_price' => $special_price,
            'special_from_date' => $special_from_date,
            'special_to_date' => $special_to_date,
            'tax_class_id' => 1,
            'meta_title' => $prodName,
            'meta_keyword' => $prodName,
            'meta_description' => $shortDescription,
            'stock_data' => array(
                'qty' => $stock,
                'is_in_stock' => 1,
                'manage_stock' => 1,
                'use_config_manage_stock' => 1,
                'min_qty' => 0,
                'use_config_min_qty' => 1,
                'min_sale_qty' => 0,
                'use_config_min_sale_qty' => 1,
                'max_sale_qty' => 100000000,
                'use_config_max_sale_qty' => 1,
                'is_qty_decimal' => 0,
                'backorders' => 0,
                'use_config_backorders' => 1,
                'notify_stock_qty' => 1,
                'use_config_notify_stock_qty' => 1
            )
        );
    }
}
