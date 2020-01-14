<?php
// unset the user, destroy the session
unset($_SESSION['user']);
session_destroy();

// start a new session for the flash message
session_start();
setFlashMessage('success', 'You have successfully logged out of your acount.');

// redirect to home page
header("Location: .");exit;
?>