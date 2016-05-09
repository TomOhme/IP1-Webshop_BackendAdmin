<?php
/**
 * Created by IntelliJ IDEA
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 20:21
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['username'])) {
    return header('Location: index.php');
}
?>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" onclick="changeSite('products');"><img src="../img/logo.png" width="100" height="30" alt="BackendAdmin"></a>
            <a class="navbar-brand" href="#" onclick="changeSite('products');">&nbsp;Backend Admin</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul id="nav_site" class="nav navbar-nav">
                <li onclick="changeSite('products');"><a href="#">Produkte</a></li>
                <li onclick="changeSite('categories');"><a href="#">Kategorien</a></li>
                <li onclick="changeSite('users');"><a href="#">Benutzer</a></li>
                <li onclick="changeSite('orders');"><a href="#">Bestellungen</a></li>
                <li onclick="changeSite('settings');"><a href="#">Einstellungen</a></li>
                <li onclick="changeSite('design');"><a href="#">Design</a></li>
                <li onclick="changeSite('newsletter');"><a href="#">Newsletter</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li title="Eingeloggter Benutzer"><a class="loggedinuser" href="#"><img src="../img/profile.png" width="25" height="25">&nbsp;<?php echo $_SESSION['username']; ?></a></li>
                <li><a href="../.." target="_blank" class="visible-xs"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop">&nbsp;Zum Webshop</a></li>
                <li><a href="../.." target="_blank" class="hidden-xs"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop"></a></li>
                <li onclick="logout();" class="visible-xs" id="action_logout"><a href="#"><img src="../img/logout.png" width="25" height="25" title="Logout">&nbsp;Logout</a></li>
                <li onclick="logout();" class="hidden-xs" id="action_logout2"><a href="#"><img src="../img/logout.png" width="25" height="25" title="Logout"></a></li>
            </ul>
        </div>
    </div>
</nav>


