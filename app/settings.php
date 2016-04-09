<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:37
 */
?>

<div id="content" style="padding-left:10px; padding-right:10px;"><div class="row">
	<div id="" class="col-sm-3">
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
	</div>

	<div class="form-group" class="col-sm-7">
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
	</div>

	<div id="" class="col-sm-2">
        <label class="col-sm-12 control-label">Logo</label>
        <div class="col-sm-12">
            <input type="text" class="form-control" name="logo" placeholder="" value="">
        </div>
        <div class="col-sm-4 col-sm-offset-3">
            <button type="file" class="btn" name="logo" accept="image">Durchsuchen...</button>
        </div>
        <label class="col-sm-12 control-label">Farbe</label>
        <div class="col-sm-12">
            <form>
                <input type="radio" name="color" value="red" checked>Rot</input>
                <input type="radio" name="color" value="blue">Blau</input><br />
                <input type="radio" name="color" value="green">Grün</input>
                <input type="radio" name="color" value="yellow">Gelb</input><br />
                <input type="radio" name="color" value="gray">Grau</input>
            </form>
        </div>
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

function updateSetting(){

document.getElementById('description').value = descriptionEditor.getData();

 var payload = JSON.stringify($('form').serializeObject());

$.ajax({
        url : 'rest/put_setting/',
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