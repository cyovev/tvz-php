<?php
session_start();
// error_reporting(0);
# Database connection
include ("conf/dbconn.php");
include ("conf/functions.php");
include ("conf/menu.php");
##############################

// if there is no page parameter set, show the home page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// load respective php file if the page parameter exists as a key
// in the $nav array (declared in layout/header.php)

// NB! Don't use file_exists for the parameter and the target file, that's vulnerable
if (array_key_exists($page, $nav)) {
    include($page.'.php');
}

// otherwise show 404
else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    include('404.php');
}
?>