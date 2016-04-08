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

include("header.php");
include("menu.php");
?>

<script type="text/javascript">

    function changeSite(page) {
        $.ajax({
            url : 'pages.php?page=' + page,
            type: 'GET',
            success: function(data){
                $('#content').html(data);
            }
        });
    };

</script>
</body>
</html>
