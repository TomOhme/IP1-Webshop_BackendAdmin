<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:37
 */
session_start();
include("../api/dbconnect.php");
include("../api/product.php");

$soapProduct = new Product();
$soapProduct -> openSoap();

$select = "SELECT `content` FROM `cms_page` WHERE `identifier`= 'ueber-uns'; ";
$result = $mysqli->query($select);
$row = mysqli_fetch_assoc($result);
$mysqli->close();
//var_dump($row["content"]);
//$title = $row['Title'];
//$img = $row['Description'];
//$aboutUs = 1;
//$opening = 1;
//$lat = $row['lat'];
//$lng = $row['lng'];

if(isset($_POST['updateDiscount'])){

}

if(isset($_POST['discountCreate'])){
	$discount = $_POST['discountCreate'];
	$threashold = $_POST['threashold'];
	$soapProduct->createDiscount($discount, $threashold);
}

if(isset($_POST['deleteDiscount'])){
	$soapProduct->deleteDiscount($_POST['deleteDiscount']);
}

function formatDiscount($discount){
	return ($discount*100)."%";
}
function formatPrice($price){
	return "Fr. " . number_format($price, 2, ',', "'");
}
?>
<script src="../plugins/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		// Bereiche für TinyMCE
		selector: 'textarea',
		menubar: false,
		statusbar: false,
		// Sprache
		language: 'de'
	});
</script>
<link rel="stylesheet" href="../css/custom.css">

<div id="content" style="padding-left:50px; padding-right:50px;">
	<div class="col-md-8">
			<table>
				<td style="width: 1000px;">
					<form method="post"  role="form" enctype="multipart/form-data" name="contact" id="contact">
					<h1>Kontaktseite</h1>
					<div class="form-group" class="col-sm-7">
						<label class="col-sm-12 control-label">Titel der Kontaktseite</label>
						<div class="col-sm-12">
							<input type="text" class="form-control" name="title" id="title" placeholder="Webshop xy" value="Webshop xy">
						</div>
						<label class="col-sm-12 control-label">Titelbild</label>
						<div class="col-sm-12">
							<input type="file" name="fileToUpload" id="fileToUpload">
						</div>
						<label class="col-sm-12 control-label">Über Uns</label>
						<div class="col-sm-12">
							<textarea rows="5" class="form-control editme" name="aboutUs" id="aboutUs" placeholder="Über Uns">
							</textarea>
						</div>
						<label class="col-sm-12 control-label">Öffnungszeiten</label>
						<div class="col-sm-12">
							<textarea rows="3" class="form-control editme" name="opening" id="opening" placeholder="Öffnungszeiten">
							</textarea>
						</div>
						<label class="col-sm-12 control-label">Standort</label>
						<!--<div id="us2" style="width: 500px; height: 400px; margin-left: 15px;">--><img src="http://maps.googleapis.com/maps/api/staticmap?center=46.9479739,7.447446799999966&amp;zoom=15&amp;size=400x400&amp;markers=color:blue|46.9479739,7.447446799999966&amp;sensor=false" height="400" width="400" style="margin-left: 15px;"/><!--</div>--><br>
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
						<br><button type="button"  onclick="updateContact();"style="margin-left: 15px;" class="btn btn-primary">
					</div>
					</form>
				</td>
			</table>
	</div>
	<div class="col-md-4">
		<h1>Rabatt</h1>
		<div class="text-right">
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
			<tbody>
				<?php
				$rows = $soapProduct->getDiscount();
				foreach ($rows as $row) {
					?>
					<tr>
						<td data-toggle="modal" data-target="#updateDiscount"><?php echo formatDiscount($row[1]); ?></td>
						<td data-toggle="modal" data-target="#updateDiscount"><?php echo formatPrice($row[2]); ?></td>
						<td onclick="deleteDiscount('<?php echo $row[0] ?>');" style="width: 50px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
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
							<label for="uthresholdForm" class="col-sm-2 control-label">Schwelle</label>
							<div class="col-sm-8">
								<input type="number" min="0" class="form-control" id="uthreasholdForm" placeholder="Schwelle in CHF">
							</div>
						</div>
						<button type="button" class="btn btn-primary" name="updateDisc" onclick="updateDiscount();">Speichern</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<script type="text/javascript">

	function updateContact() {

		tinyMCE.triggerSave()

		var title = document.getElementById("title").value;
		var fileToUpload = document.getElementById("fileToUpload").value;
		var aboutUs = document.getElementById("aboutUs").value;
		var opening = document.getElementById("opening").value;
		var lat = document.getElementById("us2-lat").value;
		var lon = document.getElementById("us2-lon").value;

		if (title == '' || fileToUpload == '' || aboutUs == '' || opening == '' || lat == '' || lon == '') {
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

	$('#updateDiscount').on('show.bs.modal', function (e) {
		$("#udiscountForm").val("BASAD");
		$("#uthreasholdForm").val("2313");
	});

	function updateDiscount(id){
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: {updateDiscount: id},
			success: function() {
				alert("Erfolgreich Aktualisiert");
			}
		});
	};

	function deleteDiscount(id){
		$.ajax({
			url: "settings.php",
			type: "POST",
			data: {deleteDiscount: id},
			success: function() {
				alert("Erfolgreich Gelöscht");
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
				alert("Erfolgreich erstellt");
			}
		});
	};
</script>
</div>

<script src="../js/jquery-2.2.2.min.js"></script>
<script type="text/javascript" src='https://maps.google.com/maps/api/js?&libraries=places'></script>
<script src="../plugins/jquery-locationpicker/src/locationpicker.jquery.js"></script>