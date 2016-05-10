<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 10.05.2016
 * Time: 09:22
 */

$ini_array = parse_ini_file("../php.ini");
$user = $ini_array["DBUSER"];
$pwd = $ini_array["DBPWD"];

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
//Get Template html
$query = "SELECT template_text FROM newsletter_template WHERE template_code='Webshop Newsletter Template'";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);


$mysqli->close();
