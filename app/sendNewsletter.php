<?php
/**
 * Created by IntelliJ IDEA.
 * User: Althaus Althaus
 * Date: 10.05.2016
 * Time: 09:22
 */
include("../config.php");
$user = DBUSER;
$pwd = DBPWD;

$mysqli = new mysqli("localhost", $user, $pwd, "magento");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");

//Get Template html
$query = "SELECT template_id, template_text FROM newsletter_template WHERE template_code='Webshop Template'";
$result = $mysqli->query($query);
$row = mysqli_fetch_assoc($result);

$templateid = $row["template_id"];
$template = $row["template_text"];
$timeelem = explode('-',$_POST["datetime"]);
$date = explode('.',rtrim($timeelem[0]));
$time = explode('.',ltrim($timeelem[1]));
$timetosend = new DateTime();
$timetosend->setTimezone(new DateTimeZone('Europe/Berlin'));
$timetosend->setDate($date[2],$date[1],$date[0]);
$timetosend->setTime($time[0],$time[1],$time[2]);
$timetosend->setTimezone(new DateTimeZone('UTC'));
$ftime = $timetosend->format('Y-m-d H:i:s');
$title = $_POST["title"];
if( $_POST["content"] == 'NULL' || !isset($_POST["content"]) ){
    $content = '';
} else { $content = $_POST["content"];
}
$conreplace = explode('h1>', $template);
if($_POST["specialpr"] == true){
    //bla
} else {
    $html = rtrim($conreplace[0], '<')."<h1>".$title."</h1>".$content."<br>".$conreplace[2];
}

$insert = "INSERT INTO newsletter_queue(queue_id, template_id, newsletter_type, newsletter_text, newsletter_styles, newsletter_subject, newsletter_sender_name, newsletter_sender_email, queue_status, queue_start_at, queue_finish_at) VALUES (NULL,".$templateid.",NULL,'".$html."',NULL,'".$title."','Test','noreply@fhnw.ch','0','".$ftime."',NULL)";
$mysqli->query($insert);

$getid = "SELECT queue_id FROM newsletter_queue WHERE queue_start_at='".$ftime."'";
$qid = $mysqli->query($getid);
$qidr = mysqli_fetch_assoc($qid);
$q = $qidr["queue_id"];

$subid = "SELECT `subscriber_id` FROM `newsletter_subscriber`";
$sid = $mysqli->query($subid);
$sidr = resultToArray($sid);

foreach($sidr as $subscriber){
    $sub = $subscriber['subscriber_id'];
    $inssub = "INSERT INTO `newsletter_queue_link`(`queue_link_id`, `queue_id`, `subscriber_id`, `letter_sent_at`) VALUES (NULL,".$q.",".$sub.",NULL)";
    $mysqli->query($inssub);
}

$insstore = "INSERT INTO `newsletter_queue_store_link`(`queue_id`, `store_id`) VALUES (".$q.", 1)";
$mysqli->query($insstore);
$mysqli->close();

function resultToArray($result) {
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}