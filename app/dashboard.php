<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 10.05.2016
 * Time: 17:53
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}

?>
<div id="content">


</div>

