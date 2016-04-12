<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR
    . dirname(__FILE__)  . PATH_SEPARATOR . dirname(__FILE__));
set_include_path(dirname(__FILE__));
//Set custom memory limit
ini_set('memory_limit', '512M');
include('Product.php');
include('ProductGroup.php');