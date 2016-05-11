<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 07.04.2016
 * Time: 15:44
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="apple-touch-icon" sizes="300x300" href="apple-touch-icon-300x300.png" />
    <link rel="icon" sizes="300x300" href="apple-touch-icon-300x300.png">
    <meta name="description" content="EasyAdmin for Magento 1.9">
    <meta name="author" content="Yanick Schraner, Tom Ohme, Janis Angs, Patrick Althaus, François Martin, Jennifer Mueller, Norina Steiner, Stefan Wohlgensinger">
    <title>EasyAdmin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/loginStyle.css" />
    <script src="../js/jquery-2.2.2.min.js"></script>
    <script src="../js/notify.min.js"></script>
    </head>
    <body>
        <div class="outer">
            <div class="middle">
                <div class="inner">
                    <div>
                        <img id="logo"  src="../img/logo.png" alt="" title="">
                    </div>
                    <div id="page-content" onKeyPress="return checkSubmit(event)" style="width:320px;">
                        <form method="post" action="">
                            <span id="error" style="display: none"></span>
                            <br />
                            <div class="input-group" style="width: 100%;">
                                <input class="form-control required" type="text" name="username" id="username"  placeholder="Benutzername" autofocus>
                            </div>
                            <p><div class="input-group" style="width: 100%;">
                                <input class="form-control required" type="password" name="password" id="password" size="40" placeholder="Passwort">
                            </div></p>
                            <br />
                            <div id="loginButton">
                                <a href="#" id="login_btn" class="btn btn-primary btn-block" role="button" onclick="proceedLogin();">Login</a>
                                <br />
                            </div>
                            <div style="text-align:center">
                                <a href="/../magento/index.php/admin/index/forgotpassword/">Passwort vergessen?</a>
                            </div>
                            <div style="text-align:center;">
                                <a href="/../magento/index.php/">Zum Webshop</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        function checkSubmit(e) {
            if(e && e.keyCode == 13) {
                proceedLogin();
            }
        }
        $.fn.serializeObject = function() {
          var o = {};
          var a = this.serializeArray();
          $.each(a, function() {
              if (o[this.name] !== undefined) {
                  if (!o[this.name].push) {
                      o[this.name] = [o[this.name]];
                  }
                  o[this.name].push(this.value || '');
              } else {
                  o[this.name] = this.value || '';
              }
          });
            return o;
        };

        function proceedLogin(){
            var payload = JSON.stringify($('form').serializeObject());

            $.ajax({
                url : '../api/login.php',
                type: 'POST',
                data: payload,
                success: function(data){
                    window.location = "./pages.php";
                },
                error: function(){
                    $("#login_btn").notify("Ungültiger Login", {
                        position:"right",
                        className: "error"}
                    );
                }
            });
        };
        </script>
    </body>
</html>