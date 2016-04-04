<?php
//Verbindungseinstellungen für die Datebank
$mysqli = new mysqli("localhost", "magento", "webshop12", "magento");
//Ausgeben von Fehlermeldung
if ($mysqli ->connect_errno){
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}
?>