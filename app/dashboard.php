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

include("../api/product.php");
include("../api/ProductGroup.php");
include("../api/orders.php");
include("../api/users.php");

$soapProduct = new Product();
$soapProduct -> openSoap();
$soapProductGroup = new Productgroup();
$soapProductGroup -> openSoap();
$soapOrders = new Orders();
$soapOrders -> openSoap();
$soapUser = new User();
$soapUser->openSoap();

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
                if (isset($user['5']) && $user['5'] == "Ja") {
                    $hasNewsletter[] = $user;
                }
            }
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


            <div class="col-lg-3 col-md-6">
                <div class="panel tile-stats">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="icon">
                                <i class="fa fa-check-square-o">
                                    <img id="newSignUpsImage" src="../img/newSignUps.png" width="30%" height="30%">
                                </i>
                            </div>
                            <div class="count">179</div>
                            <h3>New Sign ups</h3>
                            <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i>
                Statistic Diagram
            </div>
            <div class="panel-body">
                <div class="list-group">

                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i>
                History
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="#">
                        <i class="fa fa-comment fa-fw"></i>
                        New Comment
                        <span class="pull-right text-muted small">
                        <em>4 minutes ago</em>
                        </span>
                    </a>
                    <a class="list-group-item" href="#">
                        <i class="fa fa-comment fa-fw"></i>
                        New User
                        <span class="pull-right text-muted small">
                        <em>5 minutes ago</em>
                        </span>
                    </a>
                    <a class="list-group-item" href="#">
                        <i class="fa fa-comment fa-fw"></i>
                        New Product
                        <span class="pull-right text-muted small">
                        <em>7 minutes ago</em>
                        </span>
                    </a>
                    <a class="list-group-item" href="#"></a>
                    <a class="list-group-item" href="#"></a>
                    <a class="list-group-item" href="#"></a>
                    <a class="list-group-item" href="#"></a>
                    <a class="list-group-item" href="#"></a>
                    <a class="list-group-item" href="#"></a>
                </div>

            </div>
        </div>
    </div>


</div>

