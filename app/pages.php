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

    $(document).ready(function() {
        changeSite("products");
    });

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

    function cancleOrder(id){
        $.ajax({
            url : 'orders.php',
            type: 'POST',
            data: {"cancleOrderID": id},
            success: function (data) {
                changeSite("orders");
                $('#orderSuccess').empty();
                $('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde storniert.");
                $("#alertOrderSuccess").toggle();
                $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                    $("#alertOrderSuccess").hide();
                });
            }
        });
    }

    function closeOrder(id){
        $.ajax({
            url : 'orders.php',
            type: 'POST',
            data: {"closeOrderID": id},
            success: function (data) {
                changeSite("orders");
                $('#orderSuccess').empty();
                $('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde abgeschlossen.");
                $("#alertOrderSuccess").toggle();
                $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                    $("#alertOrderSuccess").hide();
                });
            }
        });
    }

    function reopenOrder(id){
        $.ajax({
            url : 'orders.php',
            type: 'POST',
            data: {"reopenOrderID": id},
            success: function (data) {
                changeSite("orders");
                $('#orderSuccess').empty();
                $('#orderSuccess').html("<strong>Erfolgreich!</strong> Die Bestellung wurde erneut er&ouml;ffnet.");
                $("#alertOrderSuccess").toggle();
                $("#alertOrderSuccess").fadeTo(10000, 500).slideUp(500, function(){
                    $("#alertOrderSuccess").hide();
                });
            }
        });
    }

    function logout() {
        $.get('logout.php', function() {
            window.location = "./index.php";
        });
    }

</script>
