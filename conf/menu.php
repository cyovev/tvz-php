<?php
$nav = array(
    'home'     => array('label' => 'Home',    'class' => 'icon-home'),
    'news'     => array('label' => 'News',    'class' => 'icon-news'),
    'contact'  => array('label' => 'Contact', 'class' => 'icon-contact'),
    'about'    => array('label' => 'About',   'class' => 'icon-about'),
    'gallery'  => array('label' => 'Gallery', 'class' => 'icon-gallery'),
);

// if the user is logged in, the nav should have logout menu item
if ($userLoggedIn) {
    $nav['logout']   = array('label' => 'Logout ('.$_SESSION['user']['first_name'].')', 'class' => 'icon-logout');
}

// otherwise show register and login menu items
else {
    $nav['register'] = array('label' => 'Registration', 'class' => 'icon-register');
    $nav['login']    = array('label' => 'Login',        'class' => 'icon-login');
}
?>