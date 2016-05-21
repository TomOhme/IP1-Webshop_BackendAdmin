<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 10.05.2016
 * Time: 17:53
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

require_once("../api/product.php");
require_once("../api/ProductGroup.php");
require_once("../api/orders.php");
require_once("../api/users.php");
include("../config.php");

$soapProduct = new Product();
$soapProduct -> openSoap();
$soapProductGroup = new Productgroup();
$soapProductGroup -> openSoap();
$soapOrders = new Orders();
$soapOrders -> openSoap();
$soapUser = new User();
$soapUser->openSoap();


$user = DBUSER;
$pwd = DBPWD;
$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$products = $soapProduct -> getAllProducts();
$orders = $soapOrders -> getAllOrders();
$users = $soapUser->getAllUsers();
?>
<div id="content">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php
        $productStockLow = array();
        foreach($products as $product){
            $productStock = $soapProduct -> getProductStock($product['product_id']);
            if ($productStock[0]['qty'] < 3) {
                $productStockLow[] = $product;
            }
        }
        ?>
        <?php $countProductStock = count($productStockLow); ?>
        <div class="row">
            <div id="dashboardDetails">
                <div class="col-lg-3 col-md-6">
                    <div class="panel <?php if ($countProductStock <= 5) { echo "panel-primary"; } else if ($countProductStock > 5 && $countProductStock <= 10) { echo "panel-yellow"; } else if ($countProductStock > 10) { echo "panel-red"; } ?>">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x">
                                        <img id="prodcutsImage" src="../img/products.png" width="175%" height="175%">
                                        <img id="productStockImage" src="../img/productStock.png" width="175%" height="175%">
                                    </i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $countProductStock; ?></div>
                                    <div>Geringer Produktemenge</div>
                                    <div>Anzahl Produkte: <?php echo count($products); ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#" onclick="changeSite('products');">
                            <div class="panel-footer">
                                <span class="pull-left">Details anzeigen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                $openOrders = array();
                foreach($orders as $order){
                    $orderStatus = $soapOrders->getOrderStatus($order);
                    if ($orderStatus == "Offen") {
                        $openOrders[] = $order;
                    }
                }
                ?>
                <?php $countOrders = count($openOrders); ?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel <?php if ($countOrders <= 5) { echo "panel-primary"; } else if ($countOrders > 5 && $countOrders <= 10) { echo "panel-yellow"; } else if ($countOrders > 10) { echo "panel-red"; } ?>">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x">
                                        <img src="../img/shoppingCard.png" width="250%" height="250%">
                                    </i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $countOrders; ?></div>
                                    <div>Offene Bestellungen</div>
                                    <div>Anzahl Bestellungen: <?php echo count($orders); ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#" onclick="changeSite('orders');">
                            <div class="panel-footer">
                                <span class="pull-left">Details anzeigen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                $hasNewsletter = array();
                foreach($users as $user){
                    $newsletter = "SELECT `subscriber_id` FROM `newsletter_subscriber` WHERE `subscriber_id` = (".$user['customer_id'].")";
                    $sid = $mysqli->query($newsletter);
                    $sidr = mysqli_fetch_assoc($sid);
                    if (isset($sidr['subscriber_id'])) {
                        $hasNewsletter[] = $user;
                    }
                }
                $mysqli->close();
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x">
                                        <img src="../img/users.png" width="250%" height="250%">
                                    </i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo count($hasNewsletter); ?></div>
                                    <div>Abonnierte Newsletter</div>
                                    <div>Anzahl Benutzer: <?php echo count($users); ?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#" onclick="changeSite('users');">
                            <div class="panel-footer">
                                <span class="pull-left">Details anzeigen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

