<?php
/**
 * Created by IntelliJ IDEA.
 * User: Tom
 * Date: 08.04.2016
 * Time: 14:18
 */

$page = isset($_GET['page']) ? $_GET['page'] : null;

if ($page == 'articles') {
    header('Location: articles.php');
} else if ($page == 'categories') {
    header('Location: categories.php');
} else if ($page == 'orders') {
    header('Location: orders.php');
} else if ($page == 'settings') {
    header('Location: settings.php');
}
?>
