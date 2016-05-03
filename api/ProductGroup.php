<?php
/**
 * Created by IntelliJ IDEA.
 * Author: Yanick Schraner
 * Date: 01.04.16
 * Time: 11:59
 */

if(file_exists("../vendor/autoload.php")){
    include('../vendor/autoload.php');
}else{
    include('./vendor/autoload.php');
}
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class Productgroup {
    private $client;
    private $mysqli;
    private $ini_array;

    public function __construct()
    {
        if(file_exists("../php.ini")){
            $this->ini_array = parse_ini_file("../php.ini");
        } else {
            $this->ini_array = parse_ini_file("./php.ini");
        }
        $this->mysqli = new mysqli("localhost", $this->ini_array['DBUSER'], $this->ini_array['DBPWD'], "magento");
    }

    public function openSoap(){
         $this -> client = MagentoXmlrpcClient::factory(array(
            'base_url' => $this->ini_array['SOAPURL'],
            'api_user' => $this->ini_array['SOAPUSER'],
            'api_key'  => $this->ini_array['SOAPPWD'],
        ));
    }

    /**
     * Creates a new Category
     * @param $name
     * @param $parentID
     * @return categoryID
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.create.html
     */
    public function createCategory($name, $parentID){
        return $this->client->call('catalog_category.create', array($parentID, array(
            'name' => $name,
            'is_active' => 1,
            'position' => 1,
            'available_sort_by' => array('position','name','price'),
            'default_sort_by' => 'position',
            'description' => $name,
            'include_in_menu' => 1,
        )));
    }

    /**
     * Allows you to retrieve information about the required category
     * @param $id
     * @return Array of catalogCategoryInfo
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.info.html
     */
    public function getCategory($id){
        return $this->client->call('catalog_category.info', array($id));
    }

    /**
     * Allows you to move the required category in the category tree.
     * @param $id
     * @param $parentID
     * @return boolean
     */
    public function moveCategory($id, $parentID){
        return $this->client->call('catalog_category.move', array($id, $parentID));
    }

    /**
     * Update the required category Name
     * @param $id
     * @param $name
     * @return boolean
     */
    public function updateCategory($id, $name){
        return $this->client->call('catalog_category.update',array($id, array('name' => $name,)));
    }

    /**
     * Allows you to retrieve the hierarchical tree of categories
     * @return Array of catalogCategoryTree
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.tree.html
     */
    public function getTree(){
        return $this->client->call('catalog_category.tree', array('2'));
    }

    /**
     * Deletes a category by its id
     * @param $id
     * @return boolean
     */
    public function deleteCategory($id){
        return $this->client->call('catalog_category.delete', array($id));
    }

    /**
    * Assigns a category a product
    * @param $categoryID
    * @param $productID
    * @return boolean
    */
    public function assignProduct($categoryID, $productID){
        return $this ->client->call('catalog_category.assignProduct', array('categoryId' => $categoryID, 'product' => $productID));
    }

}