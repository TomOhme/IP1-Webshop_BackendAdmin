<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 01.05.2016
 * Time: 11:15
 */
var_dump($_POST["aboutUs"]);
$content = '<h1>'.$_POST["title"].'</h1>
            <p><img alt="" src="{{media url="wysiwyg/kontaktseite.jpg"}}" /></p>'
            .$_POST["aboutUs"].
            '<h1 dir="ltr" style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;"><span style="font-size: medium;"><strong><span style="font-family: Arial; color: #2f2f2f; background-color: transparent; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;">&Ouml;ffnungszeiten</span></strong></span></h1>'
            .$_POST["opening"].
            '<p><span style="font-size: 12px; font-family: Arial; color: #2f2f2f; background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;"><span style="font-size: medium;"><strong>Standort</strong></span></span></p>
            <p><span style="font-size: 12px; font-family: Arial; color: #2f2f2f; background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline;"><span style="font-size: medium;"><strong><img alt="" src="http://maps.googleapis.com/maps/api/staticmap?center='.$_POST["lat"].','.$_POST["lon"].'&amp;zoom=15&amp;size=400x400&amp;markers=color:blue|'.$_POST["lat"].','.$_POST["lon"].'&amp;sensor=false" height="400" width="400" /></strong></span></span></p>';

uploadImg($_POST["fileToUpload"]);

$ini_array = parse_ini_file("../php.ini");
$user = $ini_array["DBUSER"];
$pwd = $ini_array["DBPWD"];

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$query = "UPDATE `cms_page` SET `content`='".$content."' WHERE `identifier`='ueber-uns'";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);
$mysqli->close();

function uploadImg($img){

    $target_dir = "../img/kontaktseite/"; // Verzeichnis, in welches die Dateien hochgeladen werden sollen
    $file_name = "kontaktseite"; // Name, mit der die Datei hochgeladen werden soll

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); // Pfad zum Verzeichnis + Dateiname
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); // Dateityp des Bildes
    $save_path = $target_dir . $file_name; // Pfad zur Datei, wie sie gespeichert werden soll ohne Dateiendung
    $save_file = $save_path . "." . $imageFileType; // Pfad zur Datei, wie sie gespeichert werden soll
// Überprüfung, ob Datei wirklich ein Bild ist
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
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

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $save_file)) {
            //echo nl2br("Die Datei ". basename( $_FILES["fileToUpload"]["name"]). " wurde hochgeladen.\n Sie werden automatisch weitergeleitet...");
            header( "refresh:5;url=index.php" );
        } else {
            //echo "Es gab einen Fehler beim Hochladen Ihrer Datei, bitte versuchen Sie es erneut.";
            header( "refresh:5;url=index.php" );
        }
    }
}