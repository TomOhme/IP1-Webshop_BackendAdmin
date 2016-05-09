<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 08.04.2016
 * Time: 14:18
 */
include("header.php");
include("menu.php");
?>
<div id="content">

</div>
<script type="text/javascript">

    /*$(document).ready(function() {
        changeSite("products"); TODO change site after success login
    });*/

    function changeSite(page) {
        $("#content").empty();
        $.ajax({
            url : page + ".php",
            type: 'GET',
            success: function(data){
                $('#content').append(data);
            }
        });
    };

    function logout() {
        $.get('logout.php', function() {
            window.location = "./index.php";
        });
    }

</script>
