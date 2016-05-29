i<?php
/**
 * Created by IntelliJ IDEA.
 * User: Patrick Althaus, François Martin
 * Date: 01.05.2016
 * Time: 11:15
 */
require_once("../config.php");
$filename = $_POST['oldImg'];
if(isset($_FILES["file-0"])){
    $filename = uploadImg($_FILES["file-0"]);
}

$content = '<h1>'.$_POST["title"].'</h1>
            <p><img alt="" src="{{media url="wysiwyg/'.$filename.'"}}" /></p>'
            .$_POST["aboutUs"].
            '<h1 dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;"><span style="font-size: medium;"><strong><span style="font-family: Arial; color: #2f2f2f; background-color: transparent; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;">&Ouml;ffnungszeiten</span></strong></span></h1>'
            .$_POST["opening"].
            '<p><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=de&geocode=&q='.$_POST['street'].'+'.$_POST['streetnumber'].'+'.$_POST['plz'].'+'.$_POST['village'].'&output=embed" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></p>';

$user = DBUSER;
$pwd = DBPWD;

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
$query = "UPDATE `cms_page` SET `content`='".$content."' WHERE `identifier`='ueber-uns'";
$result = $mysqli->query($query);
$mysqli->close();

function uploadImg($img){

    $target_dir = "../../magento/media/wysiwyg/"; // folder, in which the files should be uploaded
    $file_name = "kontaktseite"; // name of the file to be uploaded without extension

    $target_file = $target_dir . basename($_FILES["file-0"]["name"]); // path to the folder concatenated with file name of the uploaded file
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); // file type of the image
    $save_path = $target_dir . $file_name; // path to the folder concatenated with the defined file name above
    $save_file = $save_path . "." . $imageFileType; // path to file including the extension
// Check if file is really an image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file-0"]["tmp_name"]);
        if($check !== false) {
            //echo "Datei ist ein Bild.";
            $uploadOk = 1;
        } else {
            //echo "Datei ist kein Bild.";
            $uploadOk = 0;
        }
    }
// Restrict file formats to .jpg, .png and .jpeg
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        //echo "Bitte nur ein Bild im Format .jpg, .jpeg oder .png hochladen.";
        $uploadOk = 0;
    }
// Check, if uploadOk was put to 0 by an error
    if ($uploadOk == 0) {
        //echo "Die Datei konnte nicht hochgeladen werden.";
// If everything is ok, try to upload the file
    } else {
        // check if file already exists, if so, delete the file, with all extensions
        foreach (glob("{$save_path}.*") as $delete) {
            unlink($delete);
        }
        // finally upload the file
        if (move_uploaded_file($_FILES["file-0"]["tmp_name"], $save_file)) {
            return $file_name.".".$imageFileType;
        } else {

        }
    }
}