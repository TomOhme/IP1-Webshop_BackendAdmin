<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 20.03.16
 * Time: 16:52
 */

class OrdersTest extends PHPUnit_Framework_TestCase{

    private $soap;

    /**
     * @before
     */
    public function setupSOAPAPI(){
        $orders = new Orders();
        $orders -> openSoap();
        $this -> soap = $orders;
    }

    /**
     * @test
     */
    public function testgetAllProducts(){
        $result = $this -> soap -> getAllOrders();
        $this->assertNotEmpty($result);
        $this->assertGreaterThan(0,$result[0]['increment_id']);
    }

    /**
    * @test
    */
    public function testGetOrder(){
        $orders = $this -> soap -> getAllOrders();
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($order['increment_id'],$orders[0]['increment_id']);
        $this->assertNotEmpty($order);
    }

    /**
    * @test
    */
    public function testCancleOrder(){
        $orders = $this -> soap -> getAllOrders();
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($order['increment_id'],$orders[0]['increment_id']);
        $this->assertNotEmpty($order);
        $this->assertTrue($this -> soap -> cancleOrder($order['increment_id']));
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($this -> soap -> getOrderStatus($order), 'Storniert');
    }

    /**
    * @test
    */
    public function testCloseOrder(){
        $orders = $this -> soap -> getAllOrders();
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($order['increment_id'],$orders[0]['increment_id']);
        $this->assertNotEmpty($order);
        $this->assertTrue($this -> soap -> closeOrder($order['increment_id']));
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($this -> soap -> getOrderStatus($order), 'Abgeschlossen');
    }

    /**
    * @test
    */
    public function testReopenOrder(){
        $orders = $this -> soap -> getAllOrders();
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($order['increment_id'],$orders[0]['increment_id']);
        $this->assertNotEmpty($order);
        $this->assertTrue($this -> soap -> reopenOrder($order['increment_id']));
        $order = $this -> soap -> getOrderByID($orders[0]['increment_id']);
        $this->assertEquals($this -> soap -> getOrderStatus($order), 'Wiedereröffnet');
    }
}