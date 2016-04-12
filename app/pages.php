<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
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
<div id="content" style="padding-left:50px; padding-right:50px;">

</div>
<script type="text/javascript">

    //changeSite("articles");

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
        window.location = "./index.php";
    }

</script>
