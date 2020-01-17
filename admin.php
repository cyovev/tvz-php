<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
if (!$userLoggedIn) {
    setFlashMessage('errors', 'To access the administrative panel you need to be logged in.');
    header("Location: index.php?page=login");exit;
}

// $permissions are set in the functions.php

// get only those sections from the permission which have at least one action
$visibleSections = array();
foreach ($permissions[$role] as $sect => $act) {
    if (count($act)) { array_push($visibleSections, $sect); }
}
// END OF SETTING PERMISSIONS
$metaTitle = 'Administration';
include ('layout/header.php');

$section   = (isset($_GET['section']) && in_array($_GET['section'], $visibleSections)) ? $_GET['section'] : false;
?>

<article>
    <h1>Administration</h1>
    <div class="admin-nav">
        <?php
        foreach ($visibleSections as $item) {
            echo '<a href="index.php?page=admin&section='.$item.'"' . (($section == $item) ? ' class="active"' : '') . '><img src="images/icons/'.$item.'.png" /> '.ucfirst($item).'</a>';
        }
        ?>
    </div>
    <?php
    if ($section) {
        // stores the green/red images for active/inactive records
        $activeIcon = array('inactive.png', 'active.png');

        // get current action and check if it belongs to the role permission,
        // if not set action to false
        $action = (isset($_GET['action']) && in_array($_GET['action'], $permissions[$role][$section])) ? $_GET['action'] : false;
        if (!$action && in_array('index', $permissions[$role][$section])) {
            $action = 'index';
        }

        // include respective section (users or news)
        include ('admin/'.$section.'.php');
    }
    ?>
</article>

<?php
include ('layout/footer.php');
?>