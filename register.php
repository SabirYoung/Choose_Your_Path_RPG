<?php
require_once 'includes/config.php';
require_once 'includes/function.php';

$error = '';
$success = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_UNSAFE_RAW);
    
    // Validation
    if (empty($username) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!validateUsername($username)) {
        $error = 'Username must be 3-20 characters and contain only letters, numbers, and underscores.';
    } elseif (!validatePassword($password)) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $result = registerUser($username, $password);
        if ($result['success']) {
            $success = $result['message'];
            $username = ''; // Clear form
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
    <title>Register - Forgotten Crypt</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <h1>⚔️ Join the Adventure ⚔️</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
                <a href="login.php">Click here to login</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php" class="auth-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?php echo htmlspecialchars($username); ?>"
                       required 
                       pattern="[a-zA-Z0-9_]{3,20}"
                       title="3-20 characters, letters, numbers, and underscores only">
                <small>3-20 characters, letters, numbers, underscores only</small>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       minlength="6">
                <small>Minimum 6 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" 
                       id="confirm_password" 
                       name="confirm_password" 
                       required>
            </div>
            
            <button type="submit" class="btn-primary">Register</button>
        </form>
        
        <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>