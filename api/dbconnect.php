<?php
include("../config.php");

//Verbindungseinstellungen für die Datebank
$mysqli = new mysqli("localhost", DBUSER, DBPWD, "magento");
//Ausgeben von Fehlermeldung
if ($mysqli ->connect_errno){
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}
?>