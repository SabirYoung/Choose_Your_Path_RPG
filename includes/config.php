<?php
// Start session on every page that includes this
session_start();

// Define base path for includes
define('BASE_PATH', dirname(__DIR__) . '/');

// Leaderboard file path
define('LEADERBOARD_FILE', BASE_PATH . 'data/leaderboard.json');
define('USERS_FILE', BASE_PATH . 'data/users.json');

// Create data directory if it doesn't exist
if (!is_dir(BASE_PATH . 'data')) {
    mkdir(BASE_PATH . 'data', 0755, true);
}

// Initialize leaderboard file if it doesn't exist
if (!file_exists(LEADERBOARD_FILE)) {
    file_put_contents(LEADERBOARD_FILE, json_encode([]));
}

// Initialize users file if it doesn't exist
if (!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([]));
}

// Character class definitions
define('CHARACTER_CLASSES', [
    'warrior' => [
        'name' => 'Warrior',
        'health' => 120,
        'strength' => 15,
        'magic' => 5,
        'agility' => 8,
        'description' => 'Strong and resilient, masters of melee combat.'
    ],
    'mage' => [
        'name' => 'Mage',
        'health' => 70,
        'strength' => 5,
        'magic' => 20,
        'agility' => 10,
        'description' => 'Wielders of arcane power, fragile but devastating.'
    ],
    'rogue' => [
        'name' => 'Rogue',
        'health' => 85,
        'strength' => 10,
        'magic' => 8,
        'agility' => 18,
        'description' => 'Quick and cunning, masters of stealth and precision.'
    ]
]);
?>