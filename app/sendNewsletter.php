<?php
/**
 * Created by IntelliJ IDEA.
 * User: Patrick Althaus
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
} else {
    $content = $_POST["content"];
}
$contentbackup = $content;
$conreplace = explode('h1>', $template);

if($_POST["specialpr"] == true) {
    $counter = 0;
    $content = $content.'<br><br> Die folgenden Produkte sind momentan im Sonderangebot:<br><ul style="list-style-type:square">';
    //get special products
    include("../api/product.php");
    $soapProduct = new Product();
    $soapProduct -> openSoap();
    $specialproducts = $soapProduct->getAllProducts();
    foreach($specialproducts as $specialproduct){
        $id = $specialproduct['product_id'];
        $productinfo = $soapProduct->getProductByID($id);
        if(!is_null($productinfo['special_price'])){
            $content = $content.'<li><a href="http://pub121.cs.technik.fhnw.ch/'.$productinfo['url_path'].'">'.$productinfo['name'].'</a></li>';
            $counter++;
        }
    }
    if($counter == 0){
        $content = $contentbackup.'<br><br>Momentan sind keine Sonderangebote vorhanden.';
    } else {
        $content = $content.'</ul>';
    }
}

$html = rtrim($conreplace[0], '<')."<h1>".$title."</h1>".$content."<br>".$conreplace[2];

$query2 = 'SELECT value FROM core_config_data WHERE path = "web/secure/base_url"';
$result2 = $mysqli->query($query2);
$row2 = mysqli_fetch_assoc($result2);

$baseUrl = $row2["value"];

$newsletter_text = "<p>" .
"{{template config_path=\"design/email/header\"}} {{inlinecss file=\"email-inline.css\"}}" .
"</p>" .
"<table style=\"width: 600px;\" border=\"0\"><tbody><tr><td class=\"full\"><table class=\"columns\" style=\"width: 595px;\">" .
"<tbody><tr><td class=\"email-heading\" colspan=\"2\"><h1>".$title."</h1></td></tr></tbody></table></td></tr><tr>" .
"<td class=\"full\"><table class=\"columns\" style=\"width: 600px; height: 200px;\"><tbody><tr><td>" .
"<img class=\"main-image\" src=\"". $baseUrl ."media/wysiwyg/jumbotron.png\" /></td>" .
"<td class=\"expander\">&nbsp;</td></tr></tbody></table>" .
$content .
"</td></tr><tr><td><table class=\"row\" style=\"width: 600px;\"><tbody><tr>" .
"<td class=\"half left wrapper\">{{widget type=\"catalog/product_widget_new\" display_type=\"all_products\" products_count=\"4\" template=\"catalog/product/widget/new/content/new_list.phtml\"}}</td>" .
"<td class=\"half right wrapper\">" .
"<h6><strong><span style=\"font-size: small;\">Kontakt:</span></strong></h6>{{depend store_phone}}" .
"<p><b>Telefon:</b>&nbsp;<a href=\"tel:{{var phone}}\">{{var store_phone}}</a></p>{{/depend}} {{depend store_hours}}" .
"<p><span class=\"no-link\">{{var store_hours}}</span></p>{{/depend}} {{depend store_email}}" .
"<p><b>E-Mail:</b>&nbsp;<a href=\"mailto:{{var store_email}}\">{{var store_email}}</a></p>" .
"{{/depend}}</td></tr></tbody></table><table class=\"row\"><tbody><tr><td class=\"full wrapper last\">" .
"<table class=\"columns\" style=\"width: 600px;\"><tbody><tr><td>" .
"<p><a href=\"{{var subscriber.getUnsubscriptionLink()}}\">Newsletter abmelden</a></p>" .
"</td><td class=\"expander\">&nbsp;</td></tr></tbody></table></td></tr></tbody></table></td>" .
"</tr></tbody></table><p>{{template config_path=\"design/email/footer\"}}</p>";

$query3 = "SELECT value FROM core_config_data WHERE path = 'trans_email/ident_general/name'";
$result3 = $mysqli->query($query3);
$row3 = mysqli_fetch_assoc($result3);

$sender = $row3["value"];

$query4 = "SELECT value FROM core_config_data WHERE path = 'trans_email/ident_general/email'";
$result4 = $mysqli->query($query4);
$row4 = mysqli_fetch_assoc($result4);

$email = $row4["value"];

$insert = "INSERT INTO newsletter_queue(queue_id, template_id, newsletter_type, newsletter_text, newsletter_styles, newsletter_subject, newsletter_sender_name, newsletter_sender_email, queue_status, queue_start_at, queue_finish_at) VALUES (NULL,".$templateid.",NULL,'".$newsletter_text."',NULL,'".$title."','".$sender."','".$email."','0','".$ftime."',NULL)";
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