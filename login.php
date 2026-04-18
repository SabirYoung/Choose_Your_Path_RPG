<?php
require_once 'includes/config.php';
require_once 'includes/function.php';

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        $result = authenticateUser($username, $password);
        if ($result['success']) {
            $_SESSION['user'] = $username;
            
            // Check for saved game
            if (isset($_COOKIE['game_resume']) && $_COOKIE['game_resume'] === $username) {
                // Resume functionality would load saved state here
            }
            
            header('Location: game.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: game.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Forgotten Crypt</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <h1>⚔️ Return to the Crypt ⚔️</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" class="auth-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?php echo htmlspecialchars($username); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required>
            </div>
            
            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <p class="auth-link">New adventurer? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>