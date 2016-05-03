<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:38
 */
include("../api/orders.php");

session_start();

if(!isset($_SESSION['username'])) {
	return header('Location: index.php');
}
$soap = new Orders();
$soap -> openSoap();

if(isset($_POST['cancleOrderID'])){
	$soap->cancleOrder($_POST['cancleOrderID']);
}
if(isset($_POST['reopenOrderID'])){
	$soap->reopenOrder($_POST['reopenOrderID']);
}

if(isset($_POST['closeOrderID'])){
	echo $soap->closeOrder($_POST['closeOrderID']);
}

function formatDate($date){
	return 	date_format(date_create($date), "d.m.Y");
}

function formatPrice($price){
    setlocale(LC_MONETARY,"de_CH");
    if(function_exists('money_format')){
        return money_format("%.2n", $price);
    } else {
        return "Fr. ". sprintf('%01.2f', $price);
    }
}

function formatAmount($amount){
    setlocale(LC_ALL, "de_CH");
    return number_format($amount,0, ".", "'");
}

$orders = $soap -> getAllOrders();
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
	<!-- Alerts -->
    <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertOrderSuccess">
    <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span> <p style="display:inline;" id="orderSuccess"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <!-- Fertig mit Alerts -->

	<div class="row">
		<div id="content_table" class="col-md-6">
			<div class="table-responsive rwd-article">
				<div id="data-table-sales_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<div class="row">
						<div class="col-sm-10">
							<div id="data-table-sales_filter" class="dataTables_filter">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sd-10">
							<table class="table table-hover table-striped table-bordered dataTable no-footer" id="data-table-sales" style="width: 100%;" role="grid" aria-describedby="data-table-sales_info">
								<thead class="tablebold">
									<tr role="row">
										<td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Bestellnummer" style="width: 275px;">Bestellnummer</td>
										<td class="sorting_asc" tabindex="0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Datum und Zeit: aktivieren, um Spalte absteigend zu sortieren" style="width: 250px;">Datum</td>
										<td class="sorting" tabindex="0" aria-controls="data-table-sales" rowspan="1" colspan="1" aria-label="Käufer: aktivieren, um Spalte aufsteigend zu sortieren" style="width: 250px;">Käufer</td>
										<td class="sorting_disabled" rowspan="1" colspan="1" aria-label="Gesamtbetrag" style="width: 200px;">Betrag</td>
										<td class="sorting" tabindex="0" aria-controls="data-table-sales" rowspan="1" colspan="1" aria-label="Bestellstatus" style="width: 220px;">Bestellstatus</td>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach($orders as $order){?>
									<tr onclick="loadItem(<?php echo $order['increment_id'];?>);" role="row">
										<td><?php echo $order['increment_id']; ?></td>
										<td><?php echo formatDate($order['created_at']); ?></td>
										<td><?php echo $order['billing_firstname']. " " .$order['billing_lastname']; ?></td>
										<td><?php echo formatPrice($order['base_grand_total']); ?></td>
										<td><?php echo $soap->getOrderStatus($order); ?></td>
									</tr>
								<?php
									}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="content_pane" class="col-md-6">
		</div>
		<?php
		if(!is_null($orders)){
			foreach ($orders as $order) {
				$order = $soap -> getOrderByID($order['increment_id']);
				$orderStatus = $soap->getOrderStatus($order);
		?>
		<div id="order_store">
			<div class="panel panel-default" id="order_<?php echo $order['increment_id'];?>" style="display: none;">
				<!-- Default panel contents -->
				<div class="panel-heading">Bestellnummer: <?php echo $order['increment_id']; ?></div>
				<div class="panel-body">
					<p><label style="width:70px; font-weight:normal;">Käufer:</label><label style="text-indent: 5em;"><?php echo $order['customer_firstname']. " " .$order['customer_lastname'] ?></label></p>
					<p><label style="width:70px; font-weight:normal;">Email:</label><label style="text-indent: 5em;"><?php echo $order['customer_email']; ?></label></p>
					<p><label style="width:70px; font-weight:normal;">Datum und Zeit:</label><label style="text-indent: 5em;"><?php echo formatDate($order['created_at']); ?></label></p>
					<p><label style="width:70px; font-weight:normal;">Status:</label><label style="text-indent: 5em;"><?php echo $orderStatus; ?></label></p>
					<?php
					if($orderStatus != "Abgeschlossen" && $orderStatus != "Storniert"){
					?>
					<button type="button" class="btn btn-default btn-sm" onclick="cancleOrder(<?php echo $order['increment_id'];?>);">Stornieren</button>
					<button type="button" class="btn btn-default btn-sm" onclick="closeOrder(<?php echo $order['increment_id'];?>);">Abschliessen</button>
					<?php 
					}
					if($orderStatus == "Abgeschlossen" || $orderStatus == "Storniert"){
					?>
					<button type="button" class="btn btn-default btn-sm" onclick="reopenOrder(<?php echo $order['increment_id'];?>);">Wiederer&ouml;ffnen</button>
					<?php }?>
				</div>

				<!-- Table -->
				<table class="table">
					<thead class="tablebold">
					<tr>
						<td>Artikel Name</td>
						<td>Anzahl</td>
						<td>Einzelpreis</td>
						<td>Gesamtpreis</td>
					</tr>
					</thead>
					<tbody>
					<?php
					$items = $order['items'];
					foreach ($items as $item) {
					?>
						<tr>
							<td><?php echo $item['name']; ?></td>
							<td><?php echo formatAmount($item['qty_ordered']); ?></td>
							<td><?php echo formatPrice($item['base_price']); ?></td>
							<td><?php echo formatPrice($item['base_row_total']); ?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
			}
		}
		?>
	</div>
</div>


<script type="text/javascript">
function loadItem(id){
	$("#order_store").append($("#content_pane").children().hide());
	$("#content_pane").append($("#order_"+id));
	$("#order_"+id).show();
}

function cancleOrder(id){
	$.ajax({
		url : 'orders.php',
		type: 'POST',
		data: {"cancleOrderID": id},
		success: function (data) {
			changeSite("orders");
			$('#orderSuccess').empty();
			$('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde storniert.");
            $("#alertOrderSuccess").toggle();
            $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                $("#alertOrderSuccess").hide();
            });
		}
	});
}

function closeOrder(id){
	$.ajax({
		url : 'orders.php',
		type: 'POST',
		data: {"closeOrderID": id},
		success: function (data) {
			changeSite("orders");
			$('#orderSuccess').empty();
			$('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde abgeschlossen.");
            $("#alertOrderSuccess").toggle();
            $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                $("#alertOrderSuccess").hide();
            });
		}
	});
}

function reopenOrder(id){
	$.ajax({
		url : 'orders.php',
		type: 'POST',
		data: {"reopenOrderID": id},
		success: function (data) {
			changeSite("orders");
			$('#orderSuccess').empty();
			$('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde erneut er&ouml;ffnet.");
            $("#alertOrderSuccess").toggle();
            $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                $("#alertOrderSuccess").hide();
            });
		}
	});
}

$(document).ready(function() {
	loadItem(100000001);

	$('#data-table-sales').DataTable({
		"language": {
				"sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
				"sInfo":            "_START_ bis _END_ von _TOTAL_ Eintr&auml;gen",
				"sInfoEmpty":       "0 bis 0 von 0 Einträgen",
				"sInfoFiltered":    "(gefiltert von _MAX_ Eintr&auml;gen)",
				"sInfoPostFix":     "",
				"sInfoThousands":   ".",
				"sLengthMenu":      "_MENU_ Eintr&auml;ge anzeigen",
				"sLoadingRecords":  "Wird geladen...",
				"sProcessing":      "Bitte warten...",
				"sSearch":          "Suchen",
				"sZeroRecords":     "Keine Eintr&auml;ge vorhanden.",
				 "oLanguage": {
		  "sProcessing": "loading data..."
   },
				"oPaginate": {
					"sFirst":       "Erste",
					"sPrevious":    "Zur&uuml;ck",
					"sNext":        "N&auml;chste",
					"sLast":        "Letzte"
				},
				"oAria": {
					"sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
					"sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
				}
		},
		"bLengthChange": false,
		"pageLength": 15,
		"aoColumnDefs": [
		{ "bSortable": false, "aTargets": [ 2 ] } ]
	 });
} );

</script>