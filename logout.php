<?php
require_once 'includes/config.php';

// Clear session
$_SESSION = array();

// Destroy session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy session
session_destroy();

// Redirect to login
header('Location: login.php');
exit;
?>