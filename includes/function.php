<?php
require_once 'config.php';

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate username format
 */
function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    return strlen($password) >= 6;
}

/**
 * Load users from JSON file
 */
function loadUsers() {
    $usersData = file_get_contents(USERS_FILE);
    return json_decode($usersData, true) ?: [];
}

/**
 * Save users to JSON file
 */
function saveUsers($users) {
    return file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

/**
 * Register a new user
 */
function registerUser($username, $password) {
    $users = loadUsers();
    
    // Check if username already exists
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }
    }
    
    // Hash password and add user
    $users[] = [
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    if (saveUsers($users)) {
        return ['success' => true, 'message' => 'Registration successful!'];
    }
    
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

/**
 * Authenticate user login
 */
function authenticateUser($username, $password) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            if (password_verify($password, $user['password_hash'])) {
                return ['success' => true, 'message' => 'Login successful!'];
            }
            return ['success' => false, 'message' => 'Incorrect password.'];
        }
    }
    
    return ['success' => false, 'message' => 'Username not found.'];
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Initialize new game session
 */
function initGameSession($characterName, $characterClass) {
    $classData = CHARACTER_CLASSES[$characterClass];
    
    $_SESSION['hero'] = [
        'name' => sanitizeInput($characterName),
        'class' => $characterClass,
        'className' => $classData['name'],
        'health' => $classData['health'],
        'maxHealth' => $classData['health'],
        'strength' => $classData['strength'],
        'magic' => $classData['magic'],
        'agility' => $classData['agility'],
        'inventory' => [],
        'score' => 0
    ];
    
    $_SESSION['node'] = 'start';
    $_SESSION['choices_log'] = [];
    $_SESSION['alignment'] = 0;
    
    // Save resume cookie (valid for 30 days)
    setcookie('game_resume', $_SESSION['user'], time() + (86400 * 30), '/');
}

/**
 * Load leaderboard data
 */
function loadLeaderboard() {
    $data = file_get_contents(LEADERBOARD_FILE);
    return json_decode($data, true) ?: [];
}

/**
 * Save leaderboard entry
 */
function saveLeaderboardEntry($username, $score, $ending) {
    $leaderboard = loadLeaderboard();
    
    $leaderboard[] = [
        'username' => $username,
        'score' => $score,
        'ending' => $ending,
        'class' => $_SESSION['hero']['className'],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Sort by score descending
    usort($leaderboard, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    
    // Keep only top 20 entries
    $leaderboard = array_slice($leaderboard, 0, 20);
    
    return file_put_contents(LEADERBOARD_FILE, json_encode($leaderboard, JSON_PRETTY_PRINT));
}

/**
 * Get sorted leaderboard
 */
function getLeaderboard($limit = 10) {
    $leaderboard = loadLeaderboard();
    return array_slice($leaderboard, 0, $limit);
}

/**
 * Check if choice requirements are met
 */
function checkRequirements($requirements) {
    if (!isset($requirements) || empty($requirements)) {
        return true;
    }
    
    $hero = $_SESSION['hero'];
    
    // Check stat requirements
    if (isset($requirements['strength']) && $hero['strength'] < $requirements['strength']) {
        return ['stat' => 'strength', 'required' => $requirements['strength'], 'current' => $hero['strength']];
    }
    
    if (isset($requirements['magic']) && $hero['magic'] < $requirements['magic']) {
        return ['stat' => 'magic', 'required' => $requirements['magic'], 'current' => $hero['magic']];
    }
    
    if (isset($requirements['agility']) && $hero['agility'] < $requirements['agility']) {
        return ['stat' => 'agility', 'required' => $requirements['agility'], 'current' => $hero['agility']];
    }
    
    // Check inventory requirements
    if (isset($requirements['item'])) {
        if (!in_array($requirements['item'], $hero['inventory'])) {
            return ['stat' => 'item', 'required' => $requirements['item'], 'current' => 'none'];
        }
    }
    
    return true;
}

/**
 * Apply stat changes from choice
 */
function applyStatChanges($statCost) {
    if (!isset($statCost) || empty($statCost)) {
        return;
    }
    
    foreach ($statCost as $stat => $change) {
        if ($stat === 'item_add') {
            if (!in_array($change, $_SESSION['hero']['inventory'])) {
                $_SESSION['hero']['inventory'][] = $change;
            }
        } elseif ($stat === 'item_remove') {
            $key = array_search($change, $_SESSION['hero']['inventory']);
            if ($key !== false) {
                unset($_SESSION['hero']['inventory'][$key]);
                $_SESSION['hero']['inventory'] = array_values($_SESSION['hero']['inventory']);
            }
        } elseif ($stat === 'score') {
            $_SESSION['hero']['score'] += $change;
        } elseif (isset($_SESSION['hero'][$stat])) {
            $_SESSION['hero'][$stat] += $change;
            
            // Ensure health doesn't exceed max
            if ($stat === 'health' && $_SESSION['hero']['health'] > $_SESSION['hero']['maxHealth']) {
                $_SESSION['hero']['health'] = $_SESSION['hero']['maxHealth'];
            }
            
            // Check for death
            if ($stat === 'health' && $_SESSION['hero']['health'] <= 0) {
                $_SESSION['hero']['health'] = 0;
            }
        }
    }
}

/**
 * Generate hero summary for ending
 */
function generateHeroSummary() {
    $hero = $_SESSION['hero'];
    $log = $_SESSION['choices_log'];
    $alignment = $_SESSION['alignment'];
    
    $alignmentText = $alignment > 2 ? 'heroic' : ($alignment < -2 ? 'villainous' : 'neutral');
    
    $summary = "{$hero['name']}, the {$alignmentText} {$hero['className']}, ";
    
    if (count($log) >= 3) {
        $summary .= "whose journey was marked by three pivotal moments: ";
        $summary .= "choosing to {$log[0]['text']}, ";
        $summary .= "then {$log[1]['text']}, ";
        $summary .= "and finally {$log[2]['text']}. ";
    } else {
        $summary .= "whose adventure was brief but memorable. ";
    }
    
    $summary .= "With {$hero['health']} health remaining";
    if (!empty($hero['inventory'])) {
        $summary .= " and carrying " . implode(', ', $hero['inventory']);
    }
    $summary .= ", they earned a final score of {$hero['score']}.";
    
    return $summary;
}

/**
 * Get class-specific text variant
 */
function getClassText($node) {
    $heroClass = $_SESSION['hero']['class'];
    
    if (isset($node["text_{$heroClass}"])) {
        return $node["text_{$heroClass}"];
    }
    
    return $node['text'];
}
?>