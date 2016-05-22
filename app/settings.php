<?php
/**
 * Created by IntelliJ IDEA
 * User: Patrick Althaus, Yanick Schraner, Janis Angst
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

$pathStart = "../../magento/";

$mysqli->set_charset("utf8");
$select = "SELECT `content` FROM `cms_page` WHERE `identifier`= 'ueber-uns'; ";
$result = $mysqli->query($select);
$row = mysqli_fetch_assoc($result);

$split1 = explode('h1', $row["content"]);
$split2 = explode('}}" />', $split1[2]);
$split3 = explode('span', ltrim($split1[4], '>'));
$split3 = explode('<p>',$split3[0]);
$split4 = explode('wysiwyg', $split2[0]);
$split5 = explode('geocode=&q=', $split3[1]);
$split5 = explode('+', $split5[1]);
$tmp = explode('&output=', $split5[3]);
$split5[3] = $tmp[0];
$title = rtrim(ltrim($split1[1], '>'), '</');
$img = rtrim($split4[1], ' "');
$aboutUs = rtrim(ltrim($split2[1], '>'), '<');
$opening = rtrim($split3[0], '<');
$street = $split5[0];
$streetnumber = $split5[1];
$plz = $split5[2];
$village = $split5[3];

$contact = $settingsSoap->getContact();

$info = $settingsSoap->getShopName();

$phone = $settingsSoap -> getPhone();

$emailSender = $settingsSoap -> getEmailSender();

$email = $settingsSoap -> getEmail();

$capchaState = $settingsSoap->getCapchaState();

$discountRows = $soapProduct->getDiscount();
$shippment = $settingsSoap->getShippingSettings();
$pickUp = $settingsSoap->getPickUpSettings();

if(isset($_POST['updateDiscount'])){
	$id = $_POST['updateDiscount'];
	$discount = $_POST['discount'];
	$threshold = $_POST['threshold'];
	$valid = true;
	foreach ($discountRows as $row) {
		if($threshold < $row['setAfter'] && $discount > $row['discount']){
			http_response_code(403);
			$valid = false;
		} else if($threshold > $row['setAfter'] && $discount < $row['discount']){
			http_response_code(403);
			$valid = false;
		}
	}
	if($valid){
		$soapProduct->updateDiscount($id,$discount,$threshold);
	}
}

if(isset($_POST['discountCreate'])){
	$discount = $_POST['discountCreate'];
	$threshold = $_POST['threshold'];
	$soapProduct->createDiscount($discount, $threshold);
}

if(isset($_POST['deleteDiscount'])){
	$soapProduct->deleteDiscount($_POST['deleteDiscount']);
}

if(isset($_POST['shippingActiv'])){
	if($_POST['shippingActiv'] == "true"){
		$settingsSoap->activateShipping();
		if($_POST['shippingName'] != "undefined"){
			$settingsSoap->setShippingSettings($_POST['shippingName'], $_POST['shipinCost'], $_POST['shipingInstructions']);
		}
	} else{
		$settingsSoap->deactivateShipping();
		if($_POST['shippingName'] != "undefined"){
			$settingsSoap->setShippingSettings($_POST['shippingName'], $_POST['shipinCost'], $_POST['shipingInstructions']);
		}
	}
	if($_POST['pickUpActiv'] == "true"){
		$settingsSoap->activatePickUp();
		if($_POST['pickupDestination'] != "undefined"){
			$settingsSoap->setPickUpSettings($_POST['pickupDestination'], $_POST['pickupTime']);
		}
	} else{
		$settingsSoap->deactivatePickUp();
		if($_POST['pickupDestination'] != "undefined"){
			$settingsSoap->setPickUpSettings($_POST['pickupDestination'], $_POST['pickupTime']);
		}
	}
}

if(isset($_POST['capcha'])){
	$settingsSoap->setCapchaState($_POST['capcha']);
}

if(isset($_POST['shopname']))
{
	$shopName = $_POST["shopname"];

	$settingsSoap->setShopname($shopName);
}

if(isset($_POST['contactFooter']))
{
	$contactFooter = $_POST["contactFooter"];
	
	$settingsSoap->setContact($contactFooter);
}

if(isset($_POST['telefon']))
{
	$nr = $_POST["telefon"];

	$settingsSoap->setPhone($nr);
}

if(isset($_POST['emailSender']))
{
	$emailSender = $_POST['emailSender'];
	
	$settingsSoap -> setEmailSender($emailSender);
}

if(isset($_POST['email']))
{
	$email = $_POST['email'];
	
	$settingsSoap -> setEmail($email);
}

function formatDiscount($discount){
	return ($discount*100)."%";
}
function formatPrice($price){
	return "Fr. " . number_format($price, 2, ',', "'");
}

if(isset($_POST['submit']))
{
	$settingsSoap -> cleanCache($pathStart);
}

?>

<!-- include summernote css/js-->
<link href="../plugins/dist/summernote.css" rel="stylesheet">
<script src="../plugins/dist/summernote.js"></script>
<link rel="stylesheet" href="../css/custom.css">
	<!-- Alerts -->
    <div class="alert alert-success alert-dismissible" role="alert" style="display: none;" id="alertSuccessfulSafe">
    <span class="glyphicon glyphicon glyphicon-ok-sign" aria-hidden="true"></span><p style="display:inline;"> Die Einstellungen wurden erfolgreich gespeichert!</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <!-- Fertig mit Allerts -->

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingNameAdressH" data-toggle="collapse" data-parent="#accordion" href="#headingNameAdress" aria-expanded="true" aria-controls="headingNameAdress">
				<h4 class="panel-title">
					<a role="button">
					  Shopkonfiguration
					</a>
				</h4>
			</div>
			<div id="headingNameAdress" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingNameAdressH">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form method="post" role="form" enctype="multipart/form-data" name="webshopInfo">
								<div class="col-md-6">
									<label class="col-sm-12 control-label">Shopname</label>
									<div class="col-sm-12">
										<input type="text" required="true" maxlength="50" class="form-control" id="shopname" value="<?php echo $info ?>">
										<p class="help-block">In diesem Feld k&ouml;nnen Sie den angezeigten Webshop-Namen auf Ihrem Webshop ver&auml;ndern.</p>
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-sm-12 control-label">Kontakt</label>
									<div class="col-sm-12">
										<textarea class="form-control" required="true" id="contact" rows="5"><?php echo $contact; ?></textarea>
										<p class="help-block">In diesem Feld k&ouml;nnen Sie Ihre Adresse in der Fusszeile Ihres Webshops ver&auml;ndern.</p>
									</div>
								</div>
								
								<div class="col-md-6">
									<label class="col-sm-12 control-label">Telefon Nr.</label>
									<div class="col-sm-12">
										<input type="number" id="telefonnr" min="1000000000" max="9999999999" class="form-control" value="<?php echo $phone ?>"/>
										<p class="help-block">In diesem Feld k&ouml;nnen Sie Ihre Telefonnummer angeben, welche in den E-Mails angezeigt wird.</p>
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-sm-12 control-label">E-Mail Absendername</label>
									<div class="col-sm-12">
										<input type="text" required="true" maxlength="50" class="form-control" id="emailSender" value="<?php echo $emailSender ?>">
										<p class="help-block">In diesem Feld k&ouml;nnen Sie Ihren Namen angeben, welcher als Absender bei E-Mails angezeigt wird.</p>
									</div>
								</div>
								<div class="col-md-6">
									<label class="col-sm-12 control-label">E-Mail</label>
									<div class="col-sm-12">
										<input type="text" required="true" maxlength="50" class="form-control" id="email" value="<?php echo $email ?>">
										<p class="help-block">In diesem Feld k&ouml;nnen Sie Ihre E-Mail Adresse angeben, welche in den E-Mails als Absenderadresse verwendet wird.</p>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="col-sm-12">
										<div class="checkbox">
											<label>
												<input type="checkbox" <?php if($capchaState){ echo 'checked="1"';}?> id="capchaActiv">Captcha aktiv
											</label>
											<p class="help-block">Das Captcha verhindert, dass Personen mit schlechten Absichten ung&uuml;ltige Bestellungen automatisiert ausl&ouml;sen k&ouml;nnen. Deaktivieren Sie das Captcha, verschwindet das "Ich bin kein Roboter"-Feld bei der Registrierung und Bestellung als Gast.</p>
										</div>
									</div>
								</div>
								<button type="button" onclick="updateWebshop();" style="margin-top: 20px; margin-left: 30px" class="btn btn-primary">Speichern</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- Fertig Webshop Name und Adresse-->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingContactH" data-toggle="collapse" data-parent="#accordion" href="#headingContact" aria-expanded="false" aria-controls="headingContact">
					<h4 class="panel-title">
						<a class="collapsed" role="button">
						  Kontaktseite
						</a>
					</h4>
				</div>
				<div id="headingContact" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingContactH">
					<div class="panel-body">
						<form method="post"  role="form" enctype="multipart/form-data" name="contactSite">
							<div class="form-group">
								<label for="title">Titel der Kontaktseite</label>
								<input type="text" class="form-control" required="true" maxlength="50" name="title" id="title" placeholder="Webshop xy" value="<?php echo $title; ?>">
							</div>
							<div class="form-group img1">
								<label>Titelbild</label><br><br>
								<img style="max-width: 200px; max-height: 200px; width: auto; height: auto; margin-left: 15px; margin-bottom: 15px;" alt="Kontaktbild" src="../../media/wysiwyg<?php echo $img; ?>"/>
								<input type="file" name="fileToUpload" id="fileToUpload">
							</div>
							<div class="form-group">
								<label for="aboutUs" id="aboutUsError">Über Uns</label>
								<textarea rows="10" maxlength="10000" id="aboutUs"><?php echo $aboutUs; ?></textarea>
							</div>
							<div class="form-group">
								<label for="opening" id="openingTimeError">Öffnungszeiten</label>
								<textarea rows="10" id="opening" maxlength="1000"><?php echo $opening; ?></textarea>
							</div>
							<h2>Standort f&uuml;r Google Maps</h2>
							<div class="form-group">
								<label for="street">Strasse</label>
								<input type="text" class="form-control" required="true" maxlength="100" name="street" id="street" placeholder="Musterweg" value="<?php echo $street; ?>">
								<label for="streetnumber">Hausnummer</label>
								<input type="text" class="form-control" required="true" maxlength="50" name="streetnumber" id="streetnumber" placeholder="0" value="<?php echo $streetnumber; ?>">
								<label for="plz">Postleitzahl</label>
								<input type="number" class="form-control" required="true" max="9999" min="1000" name="plz" id="plz" placeholder="1000" value="<?php echo $plz; ?>">
								<label for="village">Ort</label>
								<input type="text" class="form-control" required="true" maxlength="100" name="village" id="village" placeholder="Bern" value="<?php echo $village; ?>">
							</div>
							<button type="button" onclick="updateContact();" class="btn btn-primary">Speichern</button>
						</form>
					</div>
				</div>
			</div><!-- Fertig Kontaktseite-->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingDiscountH" data-toggle="collapse" data-parent="#accordion" href="#headingDiscount" aria-expanded="false" aria-controls="headingDiscount">
					<h4 class="panel-title">
						<a class="collapsed" role="button">
						  Rabatt
						</a>
				 	</h4>
				</div>
				<div id="headingDiscount" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingDiscountH">
					<div class="panel-body">
						<div class="row">
							<div class="text-top">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDiscount">Rabatt hinzuf&uuml;gen</button>
							</div>
							<table class="table table-responsive table-hover table-striped table-bordered dataTable no-footer" id="data-table" style="width: 100%;" role="grid" aria-describedby="data-table_info">
								<thead class="tablebold">
									<tr role="row">
										<td>Rabatt</td>
										<td>Schwelle</td>
										<td>L&ouml;schen</td>
									</tr>
								</thead>
								<tbody id="discountValues">
									<?php
									foreach ($discountRows as $row) {
										?>
										<tr>
											<td id="discount-<?php echo $row[0]; ?>" onclick="editUpdateForm('<?php echo $row[0] ?>');"><?php echo formatDiscount($row[1]); ?></td>
											<td id="threshold-<?php echo $row[0]; ?>" onclick="editUpdateForm('<?php echo $row[0] ?>');"><?php echo formatPrice($row[2]); ?></td>
											<td onclick="deleteDiscount('<?php echo $row[0] ?>');" style="width: 50px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> <!-- Fertig mit Discount -->

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingShippingH" data-toggle="collapse" data-parent="#accordion" href="#headingShipping" aria-expanded="false" aria-controls="headingShipping">
					<h4 class="panel-title">
						<a class="collapsed" role="button">
						  Versand und Zahlung
						</a>
				 	</h4>
				</div>
				<div id="headingShipping" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingShippingH">
					<div class="panel-body">
				<div class="row">
					<form class="form-horizontal" id="shippmentPaymentForm">
						<div id="shippingDiv" <?php if(!isset($shippment['title'])){echo "style='display: none;'";} ?>>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<div class="checkbox">
										<label>
											<input type="checkbox" checked="1" id="shippingActiv">Postversand aktiv
										</label>
									</div>
								</div>
							</div>
							<input type="checkbox" id="shippingFormValidation" <?php if(isset($shippment['title'])){ echo "checked='1'"; } ?> style="display:none;">
							<div class="form-group">
								<label for="inputShipping" class="col-sm-3 control-label">Versandmethode</label>
								<div class="col-sm-9">
								  <input type="text" class="form-control" maxlength="100" id="inputShipping" placeholder="Versandart" value="<?php if(isset($shippment['title'])){ echo $shippment['title'];}?>">
								  <p class="help-block">Wird dem Kunden während einer Bestellung im Schritt &quot;Versandart&quot; angezeigt.</p>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPrice" class="col-sm-3 control-label">Versandkosten</label>
								<div class="col-sm-9">
									<div class="input-group">
										<div class="input-group-addon">CHF</div>
										<input type="number" min="0" step="0.10" max="10000" class="form-control" id="inputPrice" placeholder="Versandkostenpauschale" value="<?php if(isset($shippment['price'])){ echo $shippment['price'];} ?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="inputShippingInstruction" class="col-sm-3 control-label">Zahlungsinstruktionen</label>
								<div class="col-sm-9">
								<textarea class="form-control" rows="5" maxlength="1000" id="inputShippingInstruction" placeholder="Zahlungsinstruktionen"><?php if(isset($shippment['instructions'])){ echo $shippment['instructions'];}?></textarea>
								<p class="help-block">Die Zahlungsinstruktionen für die Vorauskasse werden dem Kunden erst nach abgeschlossener Bestellung via E-Mail zugestellt.</p>
								</div>
							</div>
						</div>
						<div id="shippingInactivDiv" <?php if(isset($shippment['title'])){echo "style='display: none;'";} ?>" >
							<h4>Der Postversand ist momentan deaktiviert.</h4>
							<div class="form-group">
								<div class="col-sm-offset-0 col-sm-9">
									<div class="checkbox">
										<label>
											<input type="checkbox" id="shippingActiv2">Postversand aktiv
										</label>
									</div>
								</div>
							</div>
						</div>
						<div id="pickupDiv" <?php if(!isset($pickUp['pickupDestination'])){echo "style='display: none;'";} ?>>
							<hr>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<div class="checkbox">
										<label>
											<input type="checkbox" checked="1" id="pickUpActiv">Abholung aktiv
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPickup" class="col-sm-3 control-label">Abholungsort</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputPickup" placeholder="Abholungsort" maxlength="200" value="<?php if(isset($pickUp['pickupDestination'])){ echo $pickUp['pickupDestination'];}?>">
									<p class="help-block">Geben Sie hier an, wo die Bestellung abgeholt werden kann. Dieses Feld wird bei der Bestellung im Schritt &quot;Versandart&quot; angezeigt.</p>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPickupTime" class="col-sm-3 control-label">Abholzeiten</label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="3" id="inputPickupTime" maxlength="500" placeholder="Abholzeiten"><?php if(isset($pickUp['pickupTime'])){ echo $pickUp['pickupTime'];}?></textarea>
									<p class="help-block">Geben Sie hier an, zu welchen Zeiten die Bestellung abgeholt werden kann. Dieses Feld wird bei der Bestellung nach Auswählen von "Barbezahlung bei Abholung" angezeigt.</p>
								</div>
							</div>
							<input type="checkbox" id="pickupFormValidation" <?php if(isset($pickUp['pickupDestination'])){ echo "checked='1'"; } ?> style="display:none;">
						</div>
						<div id="pickUpInactivDiv" <?php if(isset($pickUp['pickupDestination'])){echo "style='display: none;'";} ?>>
							<hr>
							<h4>Die Abholung ist momentan deaktiviert.</h4>
							<div class="form-group">
								<div class="col-sm-offset-0 col-sm-9">
									<div class="checkbox">
										<label>
											<input type="checkbox" id="pickUpActiv2">Abholung aktiv
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button type="button" class="btn btn-primary" onclick="updateShippmentPayment();">Speichern</button>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- Fertig mit Versand und Zahlung -->


	<div class="modal fade" id="addDiscount" tabindex="-1" role="dialog" aria-hidden="true">
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
								<div class="input-group">
									<div class="input-group-addon">%</div>
									<input type="number" min="0" max="100" class="form-control" id="discountForm" required="true" placeholder="Rabatt in %">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="thresholdForm" class="col-sm-2 control-label">Schwelle</label>
							<div class="col-sm-8">
								<div class="input-group">
									<div class="input-group-addon">CHF</div>
									<input type="number" min="0" class="form-control" id="thresholdForm" required="true" placeholder="Ab diesem Wert wird der Rabatt gew&auml;hrt">
								</div>
							</div>
						</div>
						<p class="help-block">Der Rabatt wird in Prozent auf die Bestellung gew&auml;hrt. Damit ein registrierter Kunde von einem Rabatt profitieren kann muss er innerhalb eines Kalenderjahres f&uuml;r eine Gesamtsumme einkaufen, welche den Schwellenwert &uuml;berschreitet. Nach abgelaufenem Kalenderjahr wird die Gesamtsumme der Eink&auml;ufe des Kunden wieder auf 0 zur&uuml;ckgesetzt.</p>
						<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
						<button type="button" class="btn btn-primary" name="createDiscount" onclick="addDiscount();">Speichern</button>
					</form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="updateDiscount" tabindex="-1" role="dialog" aria-hidden="true">
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
								<div class="input-group">
									<div class="input-group-addon">%</div>
									<input type="number" min="0" max="100" class="form-control" id="udiscountForm" placeholder="Rabatt in %">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="uthresholdForm" class="col-sm-2 control-label">Schwelle</label>
							<div class="col-sm-8">
								<div class="input-group">
									<div class="input-group-addon">CHF</div>
									<input type="number" min="0" class="form-control" id="uthresholdForm" required="true" placeholder="Ab diesem Wert wird der Rabatt gew&auml;hrt">
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
						<button id="saveUpdateDiscount" type="button" class="btn btn-primary" name="updateDisc">Speichern</button>
					</form>
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

		//Animate shipping and payment
		$("#shippingActiv").change(function() {
			$('#shippingDiv').toggle('slow');
			$('#shippingInactivDiv').toggle('slow');
		});
		$("#pickUpActiv").change(function() {
			$('#pickupDiv').toggle('slow');
			$('#pickUpInactivDiv').toggle('slow');
		});
		$("#shippingActiv2").change(function() {
			$("#shippingActiv").prop( "checked", true );
			$('#shippingDiv').toggle('slow');
			$('#shippingInactivDiv').toggle('slow');
		});
		$("#pickUpActiv2").change(function() {
			$("#pickUpActiv").prop( "checked", true );
			$('#pickupDiv').toggle('slow');
			$('#pickUpInactivDiv').toggle('slow');
		});
	});

	function updateContact(oldImg) {
		//prepare ajax data
		oldImg = $(".img1 img").attr("src");
		oldImg = oldImg.split("/");
		oldImg = oldImg[oldImg.length-1];
		var data = new FormData();
		jQuery.each(jQuery('#fileToUpload')[0].files, function(i, file) {
			data.append('file-'+i, file);
		});
		data.append("title",$("#title").val());
		data.append("aboutUs",$("#aboutUs").val());
		data.append("opening",$("#opening").val());
		data.append("street",$("#street").val());
		data.append("streetnumber",$("#streetnumber").val());
		data.append("plz",$("#plz").val());
		data.append("village",$("#village").val());
		data.append("oldImg", oldImg);

		//validate data
		title = $("#title").val();
		aboutUs = $("#aboutUs").val();
		opening = $("#opening").val();
		street = $("#street").val();
		streetnumber = $("#streetnumber").val();
		plz = $("#plz").val();
		village = $("#village").val();
		if (title == '') {
			$("#title").notify("Der Titel der Kontaktseite darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(title.length > 50){
			$("#title").notify("Der Titel der Kontaktseite darf nicht länger als 50 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else if(aboutUs == ''){
			$("#aboutUsError").notify("Der Über uns Text darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(aboutUs.length > 10000){
			$("#aboutUsError").notify("Der Über uns Text darf nicht länger als 10'000 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else if(opening == ''){
			$("#openingTimeError").notify("Bitte geben Sie Öffnungszeiten an.", {
				position: "top",
				className: "error"
			});
		} else if(opening.length > 1000){
			$("#openingTimeError").notify("Der Öffnungszeiten Text darf nicht länger als 1000 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else if(street == ''){
			$("#street").notify("Die Strasse darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(street.length > 100){
			$("#street").notify("Der Strassenname darf nicht länger als 100 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else if(streetnumber == ''){
			$("#streetnumber").notify("Die Stressennummer darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(streetnumber.length > 50){
			$("#streetnumber").notify("Die Stressennummer darf nicht länger als 50 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else if(plz == ''){
			$("#plz").notify("Die Postleitzahl darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(plz < 1000 || plz > 9999){
			$("#plz").notify("Die eingegebene Postleitzahl ist ungültig.", {
				position: "top",
				className: "error"
			});
		} else if(village == ''){
			$("#village").notify("Der Ort darf nicht leer sein.", {
				position: "top",
				className: "error"
			});
		} else if(village.length > 100){
			$("#village").notify("Der Ort darf nicht länger als 100 Zeichen lang sein.", {
				position: "top",
				className: "error"
			});
		} else {
			$.ajax({
				url: "updateContact.php",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				success: function() {
					$("#alertSuccessfulSafe").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertSuccessfulSafe").hide();
                    });
				}
			});
		}
		return false;
	};

	function updateWebshop() {
		var title = "Kontakt";
		var contentContact = document.getElementById("contact").value;
		var contentShopname = document.getElementById("shopname").value;
		var telefonnr = document.getElementById("telefonnr").value;
		var emailSender = document.getElementById("emailSender").value;
		var email = document.getElementById("email").value;
		var capcha = $("#capchaActiv").is(':checked');

		//prepare ajax data
		var data = new FormData();
		data.append('submit', 'submitted');
		data.append('contactFooter', contentContact);
		data.append('shopname', contentShopname);
		data.append('telefon', telefonnr);
		data.append('emailSender', emailSender);
		data.append('email', email);
		data.append('capcha', capcha);

		//validate data
		if(contentContact == '') {
			$("#contact").notify("Die Kontaktadresse darf nicht leer sein.", {
				position:"top",
				className: "error"}
			);
		} else if(contentContact.length > 300) {
			$("#shopname").notify("Die Kontaktadresse darf nicht länger als 300 Zeichen lang sein.", {
				position:"top",
				className: "error"}
			);
		} else if(contentShopname == '') {
			$("#shopname").notify("Der Shopname darf nicht leer sein.", {
				position:"top",
				className: "error"}
			);
		} else if(contentShopname.length > 50) {
			$("#shopname").notify("Der Shopname darf nicht länger als 50 Zeichen lang sein.", {
				position:"top",
				className: "error"}
			);
		} else {
			$.ajax({
				url: 'settings.php',
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				success: function(data){
					$("#alertSuccessfulSafe").fadeTo(10000, 500).slideUp(500, function(){
                        $("#alertSuccessfulSafe").hide();
                    });
				},
				error: function(data) {
					alert('Ein unbekanter Fehler ist aufgetretten.');
				}
			});
		}
	};

	function editUpdateForm(id){
		$('#updateDiscount').modal('show');
		var discount = $("#discount-"+id).text();
		var threshold = $("#threshold-"+id).text();
		discount = discount.split("%");
		threshold = threshold.split("Fr. ");
		threshold = threshold[1].replace(",",".");
		$("#udiscountForm").val(discount[0]);
		$("#uthresholdForm").val(threshold);
		$('#saveUpdateDiscount').attr("onclick", "updateDiscount("+id+")");
	};

	function updateDiscount(id){
		var id = id;
		$("#discountValues")
		var discount = $("#udiscountForm").val();
		var threshold = $("#uthresholdForm").val();

		if(discount > 100 || discount < 0){
			 $("#udiscountForm").notify("Ungültiger Rabattwert. Der Wert darf nicht grösser als 100 sein.", {
				position:"top",
				className: "error"}
			);
		} else if(threshold < 0) {
			$("#uthresholdForm").notify("Ungültiger Schwellenwert. Der Wert darf nicht kleiner als 0 sein.", {
				position:"top",
				className: "error"}
			);
		} else{
			discount = discount/100;
			$.ajax({
			url: "settings.php",
			type: "POST",
			data: {"updateDiscount": id, "discount": discount, "threshold": threshold},
			success: function() {
				$("#updateDiscount").modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				changeSiteUpdate("settings");
			}, error: function() {
				alert("Ungültig");
			}
		});
		}
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
		var threshold = $("#thresholdForm").val();
		if(discount > 100 || discount < 0){
			 $("#discountForm").notify("Ungültiger Rabattwert. Der Wert darf nicht grösser als 100 sein.", {
				position:"top",
				className: "error"}
			);
		} else if(threshold < 0) {
			$("#thresholdForm").notify("Ungültiger Schwellenwert. Der Wert darf nicht kleiner als 0 sein.", {
				position:"top",
				className: "error"}
			);
		} else {
			discount = discount/100;
			$.ajax({
				url: "settings.php",
				type: "POST",
				data: {"discountCreate": discount, "threshold": threshold},
				success: function() {
					$("#addDiscount").modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					changeSiteUpdate("settings");
				}
			});
		}
	};

	function updateShippmentPayment(){
		shippingActiv = $("#shippingActiv").is(':checked');
		pickUpActiv = $("#pickUpActiv").is(':checked');
		shippingValidation = $("#shippingFormValidation").is(':checked');
		pickupValidation = $("#pickupFormValidation").is(':checked');
		shippingName = $("#inputShipping").val();
		shipinCost = $("#inputPrice").val();
		shipingInstructions = $("#inputShippingInstruction").val();
		pickupDestination = $("#inputPickup").val();
		pickupTime = $("#inputPickupTime").val();
		var data = new FormData();
		data.append('shippingActiv', shippingActiv);
		data.append('pickUpActiv', pickUpActiv);
		data.append('shippingName', shippingName);
		data.append('shipinCost', shipinCost);
		data.append('shipingInstructions', shipingInstructions);
		data.append('pickupDestination', pickupDestination);
		data.append('pickupTime', pickupTime);

		if(!shippingActiv && !pickUpActiv){
			$("#shippingActiv2").notify("Es dürfen nicht beide Versandarten deaktiviert werden.", {
				position: "right",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shippingName == ''){
			$("#inputShipping").notify("Der Name des Packetdienst darf nicht leer sein.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shippingName.length > 100){
			$("#inputShipping").notify("Der Name des Packetdienst darf nicht mehr als 100 Zeichen lang sein.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shipinCost == ''){
			$("#inputPrice").notify("Die Versandkosten dürfen nicht leer sein.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shipinCost < 0){
			$("#inputPrice").notify("Die Versandkosten dürfen nicht negativ sein.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shipinCost > 10000){
			$("#inputPrice").notify("Die Versandkosten dürfen nicht 10'000 CHF überschreitten.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shipingInstructions == ''){
			$("#inputShippingInstruction").notify("Bitte geben Sie Zahlungsinstruktionen an.", {
				position: "left",
				className: "error"
			});
		} else if(shippingValidation && shippingActiv && shipingInstructions.length > 1000){
			$("#inputShippingInstruction").notify("Die Zahlungsinstruktionen dürfen nicht länger als 1000 Zeichen sein.", {
				position: "left",
				className: "error"
			});
		} else if(pickupValidation && pickUpActiv && pickupDestination == ''){
			$("#inputPickup").notify("Bitte geben Sie einen Abholungsort an.", {
				position: "left",
				className: "error"
			});
		} else if(pickupValidation && pickUpActiv && pickupDestination.length > 200){
			$("#inputPickup").notify("Der Abholungsort darf nicht länger als 200 Zeichen lang sein.", {
				position: "left",
				className: "error"
			});
		} else if(pickupValidation && pickUpActiv && pickupTime == ''){
			$("#inputPickupTime").notify("Bitte geben Sie Abholzeiten an.", {
				position: "left",
				className: "error"
			});
		} else if(pickupValidation && pickUpActiv && pickupTime.length > 500){
			$("#inputPickupTime").notify("Die Abholzeiten dürfen nicht länger als 500 Zeichen lang sein.", {
				position: "left",
				className: "error"
			});
		} else {
			$.ajax({
				url: "settings.php",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				success: function() {
					changeSiteUpdate("settings");
				}
			});
		}
	};
</script>
</div>