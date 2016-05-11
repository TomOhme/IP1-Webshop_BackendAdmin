<?php
/**
 * Created by IntelliJ IDEA
 * User: Patrick Althaus, Yanick Schraner
 * Date: 07.04.2016
 * Time: 15:37
 */
session_start();
include("../api/dbconnect.php");
include("../api/product.php");
include("../api/settings.php");

$soapProduct = new Product();
$soapProduct -> openSoap();
$settingsSoap = new Settings();
$settingsSoap -> openSoap();

$select = "SELECT `content` FROM `cms_page` WHERE `identifier`= 'ueber-uns'; ";
$result = $mysqli->query($select);
$row = mysqli_fetch_assoc($result);

$split1 = explode('h1', $row["content"]);
$split2 = explode('}}" />', $split1[2]);
$split3 = explode('span', ltrim($split1[4], '>'));
$split4 = explode('wysiwyg', $split2[0]);
$title = rtrim(ltrim($split1[1], '>'), '</');
//var_dump($split4[1]);
$img = rtrim($split4[1], ' "');
$aboutUs = rtrim(ltrim($split2[1], '>'), '<');
$opening = rtrim($split3[0], '<');
//$lat = $row['lat'];
//$lng = $row['lng'];




$contact = $settingsSoap->getContact();

$info = $settingsSoap->getShopName();

if(isset($_POST['updateDiscount'])){
	$id = $_POST['updateDiscount'];
	$discount = $_POST['discount'];
	$threashold = $_POST['threashold'];
	$soapProduct->updateDiscount($id,$discount,$threashold);
}

if(isset($_POST['discountCreate'])){
	$discount = $_POST['discountCreate'];
	$threashold = $_POST['threashold'];
	$soapProduct->createDiscount($discount, $threashold);
}

if(isset($_POST['deleteDiscount'])){
	$soapProduct->deleteDiscount($_POST['deleteDiscount']);
}

if(isset($_POST['shippingActiv'])){
	if($_POST['shippingActiv']){
		$settingsSoap->activateShipping();
		$settingsSoap->setShippingSettings($_POST['shippingName'], $_POST['shipinCost'], $_POST['shipingInstructions']);
	} else{
		$settingsSoap->deactivateShipping();
		$settingsSoap->setShippingSettings($_POST['shippingName'], $_POST['shipinCost'], $_POST['shipingInstructions']);
	}
	if($_POST['pickUpActiv']){
		$settingsSoap->activatePickUp();
		$settingsSoap->setPickUpSettings($_POST['pickupDestination'], $_POST['pickupTime']);
	} else{
		$settingsSoap->deactivatePickUp();
		$settingsSoap->setPickUpSettings($_POST['pickupDestination'], $_POST['pickupTime']);
	}
}

if(isset($_POST['contact']))
{
	$contactFooter = $_POST["contact"];
}

if(isset($_POST['shopname']))
{
	$shopName = $_POST["shopname"];

	$settingsSoap->setShopname($shopName);
}

function formatDiscount($discount){
	return ($discount*100)."%";
}
function formatPrice($price){
	return "Fr. " . number_format($price, 2, ',', "'");
}
?>
<!-- include summernote css/js-->
<link href="../plugins/dist/summernote.css" rel="stylesheet">
<script src="../plugins/dist/summernote.js"></script>
<link rel="stylesheet" href="../css/custom.css">

<div id="content" style="padding-left:50px; padding-right:50px;">
    <div class="col-md-6">
		<div class="row">

			<div class="col-md-12">
				<form method="post" role="form" enctype="multipart/form-data" name="webshopInfo">
					<div class="col-md-6">
						<label class="col-sm-12 control-label">Shopname</label>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="shopname" value="<?php echo $info ?>">
						</div>
					</div>
					<div class="col-md-6">
						<label class="col-sm-12 control-label">Kontakt</label>
						<div class="col-sm-12">
							<textarea class="form-control" id="contact" rows="5"><?php echo $contact; ?></textarea>
						</div>
					</div>
					<div class="col-md-9">

					</div>
					<div class="col-md-3">
						<button type="button" onclick="updateWebshop();" style="margin-top: 20px;" class="btn btn-primary">Speichern</button>
					</div>
				</form>
			</div>


            <table>
                <td style="width: 1000px;">
                    <form method="post"  role="form" enctype="multipart/form-data" name="contact">
                    <h1>Kontaktseite</h1>
                    <div class="form-group" class="col-sm-7">
                        <label class="col-sm-12 control-label">Titel der Kontaktseite</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Webshop xy" value="<?php echo $title; ?>">
                        </div>
                        <label class="col-sm-12 control-label">Titelbild</label>
                        <img style="max-width: 200px; max-height: 200px; width: auto; height: auto; margin-left: 15px; margin-bottom: 15px;" alt="Kontaktbild" src="../img/<?php echo $img; ?>"/>
                        <div class="col-sm-12">
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                        <label class="col-sm-12 control-label">Über Uns</label>
                        <div class="col-sm-12">
							<textarea rows="10" id="aboutUs"><?php echo $aboutUs; ?></textarea>
                        </div>
                        <label class="col-sm-12 control-label">Öffnungszeiten</label>
                        <div class="col-sm-12">
							<textarea rows="10" id="opening"><?php echo $opening; ?></textarea>
                        </div>

                        <label class="col-sm-12 control-label">Standort</label>
                        <!--<div id="us2" style="width: 500px; height: 400px; margin-left: 15px;">--><div id="stayheredoggy"><img src="http://maps.googleapis.com/maps/api/staticmap?center=46.9479739,7.447446799999966&amp;zoom=15&amp;size=400x400&amp;markers=color:blue|46.9479739,7.447446799999966&amp;sensor=false" height="400" width="400" style="margin-left: 15px;"/></div><!--</div>--><br>
                        <!--<script>
                            $('#us2').locationpicker({
                                location: {latitude: 46.9479739, longitude: 7.447446799999966},
                                zoom: 10,
                                inputBinding: {
                                    latitudeInput: $('#us2-lat'),
                                    longitudeInput: $('#us2-lon')
                                }
                            });
                        </script><br>-->
                        <input type="hidden" id="us2-lat" value="46.9479739"/>
                        <input type="hidden" id="us2-lon" value="7.447446799999966"/>
                        <br><button type="button" onclick="updateContact();" style="margin-left: 15px;" class="btn btn-primary">Speichern</button>
                    </div>
                    </form>
                </td>
            </table>
		</div>
    </div>
   	<div class="col-md-6">
	   	<div class="row">
			<h1>Rabatt</h1>
			<div class="text-right">
				<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDiscount">Rabatt hinzuf&uuml;gen</button>-->
				<button type="button" class="btn btn-primary" onclick="$('#addDiscount').modal('show')">Rabatt hinzuf&uuml;gen</button>
			</div>
			<table class="table table-responsive table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
				<thead class="tablebold">
					<tr role="row">
						<td>Rabatt</td>
						<td>Schwelle</td>
						<td>L&ouml;schen</td>
					</tr>
				</thead>
				<tbody>
					<?php
					$rows = $soapProduct->getDiscount();
					foreach ($rows as $row) {
						?>
						<tr>
							<td id="discount-<?php echo $row[0]; ?>" onclick="editUpdateForm('<?php echo $row[0] ?>');"><?php echo formatDiscount($row[1]); ?></td>
							<td id="threashold-<?php echo $row[0]; ?>" onclick="editUpdateForm('<?php echo $row[0] ?>');"><?php echo formatPrice($row[2]); ?></td>
							<td onclick="deleteDiscount('<?php echo $row[0] ?>');" style="width: 50px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<h1>Versand und Zahlung</h1>
			<form class="form-horizontal" id="shippmentPaymentForm">
			<?php
			$shippment = $settingsSoap->getShippingSettings();
			$pickUp = $settingsSoap->getPickUpSettings();
			if(isset($shippment['title'])){
				?>
					<div class="form-group">
					    <div class="col-sm-offset-3 col-sm-9">
					    	<div class="checkbox">
					        	<label>
					        		<input type="checkbox" checked="1" id="shippingActiv">Postversand aktiv
					        	</label>
					    	</div>
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputShipping" class="col-sm-3 control-label">Packetdienst</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" maxlength="100" id="inputShipping" placeholder="Versandart" value="<?php echo $shippment['title'];?>">
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputShippingInstruction" class="col-sm-3 control-label">Zahlungsinstruktionen</label>
					    <div class="col-sm-9">
					    <textarea class="form-control" rows="5" maxlength="1000" id="inputShippingInstruction" placeholder="Zahlungsinstruktionen"><?php echo $shippment['instructions']; ?></textarea>
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputPrice" class="col-sm-3 control-label">Versandkosten</label>
					    <div class="col-sm-9">
					    	<input type="number" min="0" step="0.10" max="10000" class="form-control" id="inputPrice" placeholder="Versandkostenpauschale" value="<?php echo $shippment['price']; ?>">
					    </div>
					</div>
				<?php
			} else{
				?>
				<h4>Der Postversand ist momentan deaktiviert.</h4>
				<div class="form-group">
				    <div class="col-sm-offset-3 col-sm-9">
				    	<div class="checkbox">
				        	<label>
				        		<input type="checkbox" checked="0" id="shippingInactiv">Postversand aktiv
				        	</label>
				    	</div>
				    </div>
				</div>
			<?php
			} if(isset($pickUp['pickupDestination'])){
				?>
				<div class="form-group">
					    <label for="inputPickup" class="col-sm-3 control-label">Abholungsort</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="inputPickup" placeholder="Abholungsort" maxlength="200" value="<?php echo $pickUp['pickupDestination'];?>">
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputPickupTime" class="col-sm-3 control-label">Abholzeiten</label>
					    <div class="col-sm-9">
					    	<textarea class="form-control" rows="3" id="inputPickupTime" maxlength="500" placeholder="Abholzeiten"><?php echo $pickUp['pickupTime']; ?></textarea>
					    </div>
					</div>
					<div class="form-group">
					    <div class="col-sm-offset-3 col-sm-9">
					    	<div class="checkbox">
					        	<label>
					        		<input type="checkbox" checked="1" id="pickUpActiv">Abholung aktiv
					        	</label>
					    	</div>
					    </div>
					</div>
				<?php
			} else {
				?>
				<h4>Die Abholung ist momentan deaktiviert.</h4>
				<div class="form-group">
				    <div class="col-sm-offset-3 col-sm-9">
				    	<div class="checkbox">
				        	<label>
				        		<input type="checkbox" checked="0" id="pickUpInactiv">Abholung aktiv
				        	</label>
				    	</div>
				    </div>
				</div>
				<?php
			}
			?>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
					    <button type="button" class="btn btn-primary" onclick="updateShippmentPayment();">Speichern</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="addDiscount" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Neue Rabattstufe hinzuf&uuml;gen</h4>
				</div>
				<div class="modal-body">
					<p>Hier k&ouml;nnen Sie eine neue Rabattstufe hinzuf&uuml;gen:</p>
					<form class="form-horizontal" id="createDiscountForm" method="post" name="createDiscountForm">
						<div class="form-group">
							<label for="discountForm" class="col-sm-2 control-label">Rabatt</label>
							<div class="col-sm-8">
							  <input type="number" step="0.01" min="0" max="1" class="form-control" id="discountForm" placeholder="Rabatt in %">
							</div>
						</div>
						<div class="form-group">
							<label for="thresholdForm" class="col-sm-2 control-label">Schwelle</label>
							<div class="col-sm-8">
								<input type="number" min="0" class="form-control" id="threasholdForm" placeholder="Schwelle in CHF">
							</div>
						</div>
						<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
						<button type="button" class="btn btn-primary" name="createDiscount" onclick="addDiscount();">Speichern</button>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="updateDiscount" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Rabattstufe bearbeiten</h4>
				</div>
				<div class="modal-body">
					<p>Hier k&ouml;nnen Sie die bestehende Rabattstufe anpassen:</p>
					<form class="form-horizontal" id="updateDiscountForm" method="post" name="updateDiscountForm">
						<div class="form-group">
							<label for="udiscountForm" class="col-sm-2 control-label">Rabatt</label>
							<div class="col-sm-8">
							  <input type="number" step="0.01" min="0" max="1" class="form-control" id="udiscountForm" placeholder="Rabatt in %">
							</div>
						</div>
						<div class="form-group">
							<label for="uthreasholdForm" class="col-sm-2 control-label">Schwelle</label>
							<div class="col-sm-8">
								<input type="number" min="0" class="form-control" id="uthreasholdForm" placeholder="Schwelle in CHF">
							</div>
						</div>
						<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
						<button id="saveUpdateDiscount" type="button" class="btn btn-primary" name="updateDisc">Speichern</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<script type="text/javascript">

	$(document).ready(function() {
		$('#opening').summernote({
			toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline', 'clear']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']]
			]
		});
		$('#aboutUs').summernote({
			toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline', 'clear']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']]
			]
		});
	});

	function updateContact() {

		var title = document.getElementById("title").value;
		var fileToUpload = document.getElementById("fileToUpload").value;
		var aboutUs = document.getElementById("aboutUs").value;
		var opening = document.getElementById("opening").value;
		var lat = document.getElementById("us2-lat").value;
		var lon = document.getElementById("us2-lon").value;

        if (title == '' || aboutUs == '' || opening == '' || lat == '' || lon == '') {
            alert("Please Fill All Fields");
        } else {
        // AJAX code to submit form.
            $.ajax({
                url: "updateContact.php",
                type: "POST",
                data: {title: title, fileToUpload: fileToUpload, aboutUs: aboutUs, opening: opening, lat: lat, lon: lon},
                success: function() {
                    alert("Erfolgreich geändert!");
                }
            });
        }
        return false;
	};

	function updateWebshop()
	{
		var title = "Kontakt"
		var contentContact = document.getElementById("contact").value;
		var contentShopname = document.getElementById("shopname").value;

		var data = new FormData();
		data.append('contact', contentContact);
		data.append('shopname', contentShopname);

		if(contentContact == '' || contentShopname == '')
		{
			alert("Bitte alle Felder ausfüllen");
		}
		else
		{
			$.ajax{(
				url: 'settings.php',
				type: 'POST',
				cache: false;
				contentType: false;
				processData: false;
				data: data,
				success: function(data)
				{
					alert("erfolgreich");
				},
				error: function(data)
				{

				}
			)};
		}
	}

	function editUpdateForm(id){
		$('#updateDiscount').modal('show')
		var discount = $("#discount-"+id).text();
		var threashold = $("#threashold-"+id).text();
		discount = discount.split("%");
		discount = discount[0]/100;
		$("#udiscountForm").val(discount);
		$('#saveUpdateDiscount').attr("onclick", "updateDiscount("+id+")");
	};

	function updateDiscount(id){
		var id = id;
		var discount = $("#udiscountForm").val();
		var threashold = $("#uthreasholdForm").val();
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: {"updateDiscount": id, "discount": discount, "threashold": threashold},
			success: function() {
				$("#updateDiscount").modal('toggle');
				changeSiteUpdate("settings");
			}
		});
	};

	function deleteDiscount(id){
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: {deleteDiscount: id},
			success: function() {
				changeSiteUpdate("settings");
			}
		});
	};
	
	function addDiscount(){
		var discount = $("#discountForm").val();
		var threashold = $("#threasholdForm").val();
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: {"discountCreate": discount, "threashold": threashold},
			success: function() {
				$("#addDiscount").modal('toggle');
				changeSiteUpdate("settings");
			}
		});
	};

	function updateShippmentPayment(){
		shippingActiv = false;
		pickUpActiv = false;
		if($("#shippingActiv").checked && !$("#shippingInactiv").checked){
			shippingActiv = true;
		}
		if($("#pickUpActiv").checked && !$("#pickUpInactiv").checked){
			pickUpActiv = true;
		}
		shippingName = $("#inputShipping").val();
		shipinCost = $("#inputPrice").val();
		shipingInstructions = $("#inputShippingInstruction").val();
		pickupDestination = $("#inputPickup").val();
		pickupTime = $("#inputPickupTime").val();
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: { "shippingActiv": shippingActiv,
					"pickUpActiv": pickUpActiv,
					"shippingName": shippingName,
					"shipinCost": shipinCost,
					"shipingInstructions": shipingInstructions,
					"pickupDestination": pickupDestination,
					"pickupTime": pickupTime},
			success: function() {
				changeSiteUpdate("settings");
			}
		});
	};
</script>
</div>
<script type="text/javascript" src='https://maps.google.com/maps/api/js?&libraries=places'></script>
<script src="../plugins/jquery-locationpicker/src/locationpicker.jquery.js"></script>