<?php
# Database connection
include ("conf/dbconn.php");
##############################

include ('layout/header.php');
##############################

// if there is no page parameter set, show the home page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// load respective php file if the page parameter exists as a key
// in the $nav array (declared in layout/header.php)
if (array_key_exists($page, $nav)) {
    include($page.'.php');
}

// otherwise show 404
else {
    include('404.php');
}

include ('layout/footer.php');
?>