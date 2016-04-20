<?php
$ini_array = parse_ini_file("../php.ini");
//Verbindungseinstellungen für die Datebank
$mysqli = new mysqli("localhost", $ini_array['DBUSER'], $ini_array['DBPWD'], "magento");
//Ausgeben von Fehlermeldung
if ($mysqli ->connect_errno){
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
}
?>