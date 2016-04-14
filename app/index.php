<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 07.04.2016
 * Time: 15:44
 */

include("header.php");
?>
<link rel="stylesheet" href="../css/loginStyle.css" />
</head>
    <body>
        <div class="outer">
            <div class="middle">
                <div class="inner">
                    <div>
                        <img id="logo"  src="../img/Logo.png" alt="" title="">
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
                    //location.reload();
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