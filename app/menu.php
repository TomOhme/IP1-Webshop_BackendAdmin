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
<nav class="nav navbar-nav" id="header" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" onclick="changeSite('dashboard');"><img src="../img/logoHeader.png" width="100" height="30" alt="BackendAdmin"></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul id="nav_site" class="nav navbar-nav">
                <li onclick="changeSite('dashboard');"><a class="header-link" href="#">Dashboard</a></li>
                <li onclick="changeSite('products');"><a class="header-link" href="#">Produkte</a></li>
                <li onclick="changeSite('categories');"><a class="header-link" href="#">Kategorien</a></li>
                <li onclick="changeSite('orders');"><a class="header-link" href="#">Bestellungen</a></li>
                <li onclick="changeSite('users');"><a class="header-link" href="#">Benutzer</a></li>
                <li onclick="changeSite('newsletter');"><a class="header-link" href="#">Newsletter</a></li>
                <li onclick="changeSite('design');"><a class="header-link" href="#">Design</a></li>
                <li onclick="changeSite('settings');"><a class="header-link" href="#">Einstellungen</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../documents/Benutzerhandbuch.pdf" target="_blank" class="header-link"><img src="../img/faq-circular-filled-button.png" width="25" height="25" title="Hilfe"></a></li>
                <li title="Eingeloggter Benutzer"><a class="loggedinuser header-link" href="#">
                <img src="../img/profile.png" width="25" height="25">&nbsp;<?php echo $_SESSION['username']; ?></a></li>
                <li><a href="../.." target="_blank" class="visible-xs header-link"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop">&nbsp;Zum Webshop</a></li>
                <li><a href="../.." target="_blank" class="hidden-xs header-link"><img src="../img/shop.png" width="25" height="25" title="Zum Webshop"></a></li>
                <li onclick="logout();" class="visible-xs" id="action_logout"><a class="header-link" href="#"><img src="../img/logout.png" width="25" height="25" title="Logout">&nbsp;Logout</a></li>
                <li onclick="logout();" class="hidden-xs" id="action_logout2"><a class="header-link" href="#"><img src="../img/logout.png" width="25" height="25" title="Logout"></a></li>
            </ul>
        </div>
    </div>
</nav>


