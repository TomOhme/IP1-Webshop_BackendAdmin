<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom Ohme
 * Date: 08.04.2016
 * Time: 14:18
 */

include("header.php");
include("menu.php");

/*
$page = isset($_GET['page']) ? $_GET['page'] : null;


if ($page == 'articles') {
    header('Location: articles.php');
} else if ($page == 'categories') {
    header('Location: categories.php');
} else if ($page == 'orders') {
    header('Location: orders.php');
} else if ($page == 'settings') {
    header('Location: settings.php');
}*/

?>
<div id="content">

</div>
<script type="text/javascript">

    //changeSite("articles"); TODO

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
        window.location = "./index.php";
    }

</script>
