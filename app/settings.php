<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:37
 */
include("../api/settings.php");

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

$soap = new Settings();
$soap -> openSoap();

if(isset($_POST["submit"]))
{
    $img = array_filter($_FILES["uploadImgBtn"]);

    if(empty($img)) {
        $target_dir = "../../skin/frontend/webshop/default/images/";
        $target_file = $target_dir . basename($_FILES["uploadImgBtn"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $errorMsg = "";

        $check = getimagesize($_FILES["uploadImgBtn"]["tmp_name"]);

        if ($check == false) {
            $uploadOk = 0;
            $errorMsg .= "Die Datei ist kein Bild!\n";
        }

        if ($_FILES["uploadImgBtn"]["size"] > 500000) {
            $uploadOk = 0;
            $errorMsg .= "Das Bild ist zu gross.\n";
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $errorMsg .= "Nur JPG, PNG & GIF Dateien sind erlaubt.\n";
        }

        if ($uploadOk == 0) {
            $errorMsg .= "Bild wurde nicht hochgeladen";

            echo '<script language="javascript">';
            echo 'alert(' . $errorMsg . ')';
            echo '</script>';

        } else {
            foreach (glob("../../skin/frontend/webshop/default/images/logo_bh.png") as $file) {
                unlink($file);
            }

            echo '<script language="javascript">';
            echo 'alert("logo removed")';
            echo '</script>';

            move_uploaded_file($_FILES["uploadImgBtn"]["tmp_name"], "../../skin/frontend/webshop/default/images/logo_bh.png");

            echo '<script language="javascript">';
            echo 'alert("neues Logo gespeichert")';
            echo '</script>';
        }
    }

    $color = $_POST["color"];
    $destCss = "../../skin/frontend/webshop/default/css/webshop.css";


    if($color == "blue")
    {
        $targetCss = "../css/blue.css";

        unlink($destCss);

        copy($targetCss, $destCss);

        echo '<script language="javascript">';
        echo 'alert("Copied blue css")';
        echo '</script>';
    }
    else if($color == "red")
    {
        $targetCss = "../css/red.css";

        unlink($destCss);

        copy($targetCss, $destCss);

        echo '<script language="javascript">';
        echo 'alert("Copied red css")';
        echo '</script>';
    }
    else if($color == "green")
    {
        $targetCss = "../css/green.css";

        unlink($destCss);

        copy($targetCss, $destCss);

        echo '<script language="javascript">';
        echo 'alert("Copied green css")';
        echo '</script>';
    }
    else if($color == "beige")
    {
        $targetCss = "../css/beige.css";

        unlink($destCss);

        copy($targetCss, $destCss);

        echo '<script language="javascript">';
        echo 'alert("Copied beige css")';
        echo '</script>';
    }
    else if($color == "gray")
    {
        $targetCss = "../css/gray.css";

        unlink($destCss);

        copy($targetCss, $destCss);

        echo '<script language="javascript">';
        echo 'alert("Copied gray css")';
        echo '</script>';
    }

    foreach (glob("../../var/cache/*", GLOB_ONLYDIR) as $dir)
    {
        foreach(glob($dir . "/*") as $file)
        {
            unlink($file);
        }
        
        rmdir($dir);
    }

    echo '<script language="javascript">';
    echo 'alert("Cleared cache")';
    echo '</script>';
}

?>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <div class="row">
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
            </td>

            <td style="width: 800px;">
                <div id="" class="col-sm-12">
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div id="" class="col-sm-12">
                        <img src="../../skin/frontend/webshop/default/images/logo_bh.png" height="100px" />
                    </div>
                    <label class="col-sm-12 control-label">Logo</label>
                    <div class="col-sm-12">
                        <table>
                            <tr>
                                <td><input type="text" class="file-upload" id="uploadImgPath" name="logo" placeholder="" value=""></td>
                                <td>
                                    <button class="file-upload">
                                        <input type="file" id="uploadImgBtn" class="file-input">Durchsuchen...
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-4 col-sm-offset-3">

                    </div>
                    <label class="col-sm-12 control-label">Farbe</label>
                    <div class="col-sm-12">
                        <form>
                            <table>
                                <tr>
                                    <td style="width: 100px;"><input type="radio" name="color" value="red" checked>Rot</input></td>
                                <td><input type="radio" name="color" value="blue">Blau</input></td>
                                </tr>
                                <tr>
                                    <td style="width: 100px;"><input type="radio" name="color" value="green">Grün</input></td>
                                    <td><input type="radio" name="color" value="beige">Beige</input></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="color" value="gray">Grau</input></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="col-sm-6" style="margin-top: 10px">
                        <input type="submit" name="submit" value="Speichern" >
                    </div>
                    </form>
                </div>
            </td>

        </table>
    </div>
<script type="text/javascript">

document.getElementById("uploadImgBtn").onchange = function() {
    document.getElementById("uploadImgPath").value = this.value;
};

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