<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:37
 */

?>
<!DOCTYPE html>
<!-- Auf UTF8 setzen, damit Umlaute korrekt dargestellt werden -->
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<html>
<head>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script src="../plugins/jquery-locationpicker/src/locationpicker.jquery.js"></script>
    <!-- TinyMCE einbinden -->
    <script src="../plugins/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            // Bereiche für TinyMCE
            selector: 'textarea#aboutUs',
            menubar: false,
            statusbar: false,
            // Sprache
            language: 'de'
        });
    </script>
</head>
<body>

<?php

include("../api/settings.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$soap = new Settings();
$soap -> openSoap();
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <div class="row">
        <form method="post">
            <table>
                <td style="width: 800px;">
                    <label class="col-sm-3 control-label">Shopname</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="title" placeholder="Webshop Name" value="Mein Bauernhof">
                    </div>
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" placeholder="Max Mustermann" value="Max Mustermann">
                    </div>
                    <label class="col-sm-3 control-label">Strasse</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="street" placeholder="Musterstrasse 1" value="Musterstrasse 1">
                    </div>
                    <label class="col-sm-3 control-label">PLZ</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="plz" placeholder="1234" value="1234" onkeydown="return isNumberKey(event)">
                    </div>
                    <label class="col-sm-3 control-label">Ort</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="city" placeholder="Musteren" value="Musteren">
                    </div>
                </td>

                <td style="width: 1000px;">
                    <form onsubmit="updateContact()" method="post" name="contact">
                    <h1>Kontaktseite</h1>
                    <div class="form-group" class="col-sm-7">
                        <label class="col-sm-12 control-label">Titel der Kontaktseite</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="titleContact" placeholder="Webshop xy" value="Webshop xy">
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
                        <input type="hidden" id="us2-lat"/>
                        <input type="hidden" id="us2-lon"/>
                        <div id="us2" style="width: 500px; height: 400px; margin-left: 15px;"></div>
                        <script>
                            $('#us2').locationpicker({
                                location: {latitude: 46.9479739, longitude: 7.447446799999966},
                                zoom: 10,
                                inputBinding: {
                                    latitudeInput: $('#us2-lat'),
                                    longitudeInput: $('#us2-lon')
                                }
                            });
                        </script>
                        <input type="submit" name="submitContact" value="Speichern">
                    </div>
                    </form>
                </td>
            </table>
        </form>
    </div>
<script type="text/javascript">

    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


CKEDITOR.config.toolbar = [
   ['Styles','Format','Font','FontSize'],
   ['Bold','Italic','Underline','StrikeThrough','-','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   ['Table','-','Link','TextColor','BGColor','Source']
] ;


var descriptionEditor = CKEDITOR.replace( 'description' );
Dropzone.autoDiscover = false;

var dz = $('#picture').dropzone({
    url: "upload.php",
    clickable:'#clickZone',
    createImageThumbnails: true,
    acceptedFiles: 'image/*',
    addRemoveLinks: false,
    thumbnailWidth: "120",
    thumbnailHeight: "120",
    maxFiles: 1,

    init: function() {

   	  this.on('error', function(file) {
   	  	Dropzone.forElement('#picture').removeFile(file);
   	  	if(!isImage(file.name)){
        	$.notify("Falscher Datentyp", "error");
		}
      });

      this.on('maxfilesexceeded', function(file) {
        Dropzone.forElement('#picture').removeAllFiles(true);
        this.addFile(file);
      });

      this.on('success', function(file, json) {
        document.getElementById("logo").value = json;
      });
    }
  });

  Dropzone.forElement('#picture').removeAllFiles(true);
  var mockFile = { name: "Logo", size: 12345, status: 'success', accepted: true, bytesSent: 12345};

  Dropzone.forElement('#picture').emit("addedfile", mockFile);
  Dropzone.forElement('#picture').emit("thumbnail", mockFile, 'http://linux203.cs.technik.fhnw.ch/magento/skin/frontend/rwd/default/images/logo.gif?time=' + Math.random().toString(36).substr(2, 5));
  Dropzone.forElement('#picture').files.push( mockFile );
  Dropzone.forElement("#picture").emit("complete", mockFile);

function updateContact() {

    alert("Submit button clicked!");
    return true;

}

function updateSetting(){

document.getElementById('description').value = descriptionEditor.getData();

 var payload = JSON.stringify($('form').serializeObject());
    alert(payload;

$.ajax({
        url : 'updateSetting.php',
        type: 'POST',
        data: payload,
        success: function(data){
            $.notify("Gespeichert", "success");
        },
        error: function(){
        	$.notify("Error", "error");
        }
    });

};

    $(function(){
        $('.picker').colorpicker().on('changeColor.colorpicker', function(event){
        document.getElementById('color').value = event.color.toHex();
      });
    });

</script>
</div>
</body>
