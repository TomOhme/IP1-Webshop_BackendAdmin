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

    $target_dir = "../../magento/media/wysiwyg/"; // Verzeichnis, in welches die Dateien hochgeladen werden sollen
    $file_name = "kontaktseite"; // Name, mit der die Datei hochgeladen werden soll

    $target_file = $target_dir . basename($_FILES["file-0"]["name"]); // Pfad zum Verzeichnis + Dateiname
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); // Dateityp des Bildes
    $save_path = $target_dir . $file_name; // Pfad zur Datei, wie sie gespeichert werden soll ohne Dateiendung
    $save_file = $save_path . "." . $imageFileType; // Pfad zur Datei, wie sie gespeichert werden soll
// Überprüfung, ob Datei wirklich ein Bild ist
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file-0"]["tmp_name"]);
        if($check !== false) {
            //echo "Datei ist ein Bild. ";
            $uploadOk = 1;
        } else {
            //echo "Datei ist kein Bild. ";
            $uploadOk = 0;
        }
    }
// Bildformate auf .jpg, .png und .jpeg beschränken
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        //echo "Bitte nur ein Bild im Format .jpg, .jpeg oder .png hochladen.";
        $uploadOk = 0;
    }
// Überprüfen, ob $uploadOk durch einen Fehler auf 0 gesetzt wurde
    if ($uploadOk == 0) {
        //echo "Die Datei konnte nicht hochgeladen werden.";
// Wenn alles ok ist, Datei versuchen hochzuladen
    } else {
        // checken, ob eine Datei bereits existiert, wenn ja -> löschen, mit allen Endungen
        foreach (glob("{$save_path}.*") as $delete) {
            unlink($delete);
        }

        if (move_uploaded_file($_FILES["file-0"]["tmp_name"], $save_file)) {
            return $file_name.".".$imageFileType;
        } else {

        }
    }
}