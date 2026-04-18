<?php
require_once 'includes/config.php';
require_once 'includes/function.php';

// Redirect based on login status
if (isLoggedIn()) {
    header('Location: game.php');
} else {
    header('Location: login.php');
}
exit;
?>