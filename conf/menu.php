<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
$nav = array(
    'home'     => array('label' => 'Home',    'class' => 'icon-home'),
    'news'     => array('label' => 'News',    'class' => 'icon-news'),
    'contact'  => array('label' => 'Contact', 'class' => 'icon-contact'),
    'about'    => array('label' => 'About',   'class' => 'icon-about'),
    'gallery'  => array('label' => 'Gallery', 'class' => 'icon-gallery'),
);
$loggedNav = array(
    'admin'  => array('label' => 'Administration',                               'class' => 'icon-admin'),
    'logout' => array('label' => 'Logout ('.(isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '').')', 'class' => 'icon-logout'),
);

$notLoggedNav = array(
    'register' => array('label' => 'Registration', 'class' => 'icon-register'),
    'login'    => array('label' => 'Login',        'class' => 'icon-login'),
);

// if the user is logged in, the nav should have logout menu item
if ($userLoggedIn) {
    $nav = array_merge($nav, $loggedNav);
}

// otherwise show register and login menu items
else {
    $nav = array_merge($nav, $notLoggedNav);
}

$allPages = array_merge($nav, $loggedNav, $notLoggedNav);
?>