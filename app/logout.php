<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 03.05.2016
 * Time: 10:21
 */

session_start();

$_SESSION = array();

$params = session_get_cookie_params();

setcookie( session_name(), '', time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
);

session_destroy();

?>
