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
$query = "SELECT template_id, template_text FROM newsletter_template WHERE template_code='Webshop Template'";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);

$templateid = $row["template_id"];
$template = $row["template_text"];
//$timeelem = explode(' ',$_POST["datetime"]);
$timeelem = explode(' ','2016 05 10 13 15 10');
$timetosend = new DateTime();
$timetosend->setTimezone(new DateTimeZone('Europe/Berlin'));
$timetosend->setDate($timeelem[0],$timeelem[1],$timeelem[2]);
$timetosend->setTime($timeelem[3],$timeelem[4],$timeelem[5]);
$timetosend->setTimezone(new DateTimeZone('UTC'));
$time = $timetosend->format('Y-m-d H:i:s');
//$title = $_POST["title"];
//$content = $_POST["content"];
$title = 'adad';
$content = 'adadadaddadadada';
$conreplace = explode('h1>', $template);
var_dump($conreplace);
$html = rtrim($conreplace[0], '<')."<h1>".$title."</h1>\n".$content."\n".$conreplace[2];

$insert = "INSERT INTO magento.newsletter_queue(`queue_id`, `template_id`, `newsletter_type`, `newsletter_text`, `newsletter_styles`, `newsletter_subject`, `newsletter_sender_name`, `newsletter_sender_email`, `queue_status`, `queue_start_at`, `queue_finish_at`) VALUES (NULL,$templateid,NULL,$html,NULL,$title,'Test','noreply@fhnw.ch','0',$time,NULL)";
$mysqli->query($insert);
echo $mysqli->error;
$mysqli->close();
