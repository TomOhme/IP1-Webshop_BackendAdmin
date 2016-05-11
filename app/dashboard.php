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

$soapProduct = new Product();
$soapProduct -> openSoap();
$soapProductGroup = new Productgroup();
$soapProductGroup -> openSoap();
$soapOrders = new Orders();
$soapOrders -> openSoap();

$orders = $soapOrders -> getAllOrders();
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
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-comments fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">26</div>
                                <div>New Comments!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">12</div>
                                <div>New Tasks!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
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
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-5x">
                                    <img src="../img/shoppingCard.png" width="200%" height="200%">
                                </i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo count($openOrders); ?></div>
                                <div>Offene Bestellungen</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Details anzeigen</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-support fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">13</div>
                                <div>Support Tickets!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i>
                Notifications Panel
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
                        <a class="list-group-item" href="#">
                            <a class="list-group-item" href="#">
                                <a class="list-group-item" href="#">
                                    <a class="list-group-item" href="#">
                                        <a class="list-group-item" href="#">
                                            <a class="list-group-item" href="#">
                                                <a class="list-group-item" href="#">
                </div>
                <a class="btn btn-default btn-block" href="#">View All Alerts</a>
            </div>
        </div>
    </div>


</div>

