<?php
//Verbindungseinstellungen für die Datebank
$mysqli = new mysqli("localhost", "root", "", "magento");
//Ausgeben von Fehlermeldung
if ($mysqli ->connect_errno){
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}
?>