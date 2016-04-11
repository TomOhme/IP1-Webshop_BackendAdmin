<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:36
 */

session_start();

if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}
?>

<div id="content" style="padding-left:50px; padding-right:50px;">
    <img src="img/loader.gif">
</div>

</body>
</html>
