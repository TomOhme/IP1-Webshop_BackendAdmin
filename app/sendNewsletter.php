<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 10.05.2016
 * Time: 09:22
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$ini_array = parse_ini_file("../php.ini");
$user = $ini_array["DBUSER"];
$pwd = $ini_array["DBPWD"];

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
//Get Template html
$query = "SELECT template_id, template_text FROM newsletter_template WHERE template_code='Webshop Template'";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);

$templateid = $row["template_id"];
$template = $row["template_text"];
//$timeelem = explode('-',$_POST["datetime"]);
$timeelem = explode('-','12.05.2016 - 13.48.59');
$date = explode('.',rtrim($timeelem[0]));
$time = explode('.',ltrim($timeelem[1]));
$timetosend = new DateTime();
$timetosend->setTimezone(new DateTimeZone('Europe/Berlin'));
$timetosend->setDate($date[2],$date[1],$date[0]);
$timetosend->setTime($time[0],$time[1],$time[2]);
$timetosend->setTimezone(new DateTimeZone('UTC'));
$ftime = $timetosend->format('Y-m-d H:i:s');
$title = $_POST["title"];
$content = $_POST["content"];
$conreplace = explode('h1>', $template);
$html = rtrim($conreplace[0], '<')."<h1>".$title."</h1>".$content."<br>".$conreplace[2];

$insert = "INSERT INTO newsletter_queue(queue_id, template_id, newsletter_type, newsletter_text, newsletter_styles, newsletter_subject, newsletter_sender_name, newsletter_sender_email, queue_status, queue_start_at, queue_finish_at) VALUES (NULL,".$templateid.",NULL,'".$html."',NULL,'".$title."','Test','noreply@fhnw.ch','0','".$ftime."',NULL)";
$mysqli->query($insert);
$mysqli->close();
