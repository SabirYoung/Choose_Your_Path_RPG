<?php
require_once 'config.php';
require_once 'functions.php';

// This file is included on pages that require authentication
requireLogin();

// Check for saved game cookie
if (isset($_COOKIE['game_resume']) && $_COOKIE['game_resume'] === $_SESSION['user']) {
    // Player has a saved game - they can resume
    $hasSavedGame = isset($_SESSION['node']) && $_SESSION['node'] !== 'start';
}
?>