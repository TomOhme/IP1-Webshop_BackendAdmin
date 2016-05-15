<?php
/**
 * Created by IntelliJ IDEA.
 * User: Evenus
 * Date: 24.04.2016
 * Time: 16:38
 */
//echo "hi ich bin in deleteUser";
require_once("../api/users.php");

$soap = new User();
$soap -> openSoap();
if (isset($_POST['userId'])) {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    $result = $soap->deleteUserByID($userId);
}
?>