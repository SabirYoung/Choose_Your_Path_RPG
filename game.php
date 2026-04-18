<?php
require_once 'includes/config.php';
require_once 'includes/function.php';
require_once 'includes/story-data.php';
requireLogin();

// Handle character creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_character') {
        $characterName = htmlspecialchars(filter_input(INPUT_POST, 'character_name', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
        $characterClass = htmlspecialchars(filter_input(INPUT_POST, 'character_class', FILTER_UNSAFE_RAW), ENT_QUOTES, 'UTF-8');
        
        if (empty($characterName) || strlen($characterName) < 2) {
            $error = "Please enter a valid character name (at least 2 characters).";
        } elseif (!array_key_exists($characterClass, CHARACTER_CLASSES)) {
            $error = "Please select a valid character class.";
        } else {
            initGameSession($characterName, $characterClass);
            header('Location: game.php');
            exit;
        }
    }
    
    // Handle story choice
    if ($_POST['action'] === 'make_choice' && isset($_POST['choice_index'])) {
        $currentNode = $_SESSION['node'];
        $choiceIndex = (int)$_POST['choice_index'];
        
        if (isset($storyTree[$currentNode]['choices'][$choiceIndex])) {
            $choice = $storyTree[$currentNode]['choices'][$choiceIndex];
            
            // Check requirements
            $reqCheck = checkRequirements($choice['requires'] ?? null);
            
            if ($reqCheck === true) {
                // Log the choice
                $_SESSION['choices_log'][] = [
                    'node' => $currentNode,
                    'text' => $choice['text'],
                    'timestamp' => date('H:i:s')
                ];
                
                // Update alignment
                if (isset($choice['alignment'])) {
                    $_SESSION['alignment'] += $choice['alignment'];
                }
                
                // Apply stat changes
                applyStatChanges($choice['stat_cost'] ?? null);
                
                // Check if hero died
                if ($_SESSION['hero']['health'] <= 0) {
                    $_SESSION['node'] = 'ending_tragic';
                    // Save to leaderboard
                    saveLeaderboardEntry($_SESSION['user'], $_SESSION['hero']['score'], 'Tragic Failure');
                } else {
                    $_SESSION['node'] = $choice['next'];
                    
                    // Check if it's an ending
                    if (isset($storyTree[$choice['next']]['is_ending'])) {
                        saveLeaderboardEntry(
                            $_SESSION['user'], 
                            $_SESSION['hero']['score'], 
                            $storyTree[$choice['next']]['ending_type']
                        );
                    }
                }
                
                header('Location: game.php');
                exit;
            } else {
                $requirementError = $reqCheck;
            }
        }
    }
    
    // Handle new game
    if ($_POST['action'] === 'new_game') {
        session_destroy();
        session_start();
        $_SESSION['user'] = $_POST['username'];
        header('Location: game.php');
        exit;
    }
}

// Get current node data
$currentNode = $_SESSION['node'] ?? null;
$nodeData = $currentNode ? $storyTree[$currentNode] : null;

// Check if game needs character creation
$needsCharacter = !isset($_SESSION['hero']);
$isEnding = $nodeData && isset($nodeData['is_ending']) && $nodeData['is_ending'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgotten Crypt - Text Adventure RPG</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="game-container">
        <header class="game-header">
            <h1>⚔️ Forgotten Crypt ⚔️</h1>
            <div class="user-info">
                <span>Player: <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </header>
        
        <?php if ($needsCharacter): ?>
        <!-- Character Creation Form -->
        <div class="character-creation">
            <h2>Create Your Hero</h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="game.php" class="character-form">
                <input type="hidden" name="action" value="create_character">
                
                <div class="form-group">
                    <label for="character_name">Hero Name:</label>
                    <input type="text" 
                           id="character_name" 
                           name="character_name" 
                           required 
                           minlength="2" 
                           maxlength="30"
                           value="<?php echo isset($_POST['character_name']) ? htmlspecialchars($_POST['character_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Choose Your Class:</label>
                    <div class="class-options">
                        <?php foreach (CHARACTER_CLASSES as $key => $class): ?>
                        <div class="class-option">
                            <input type="radio" 
                                   name="character_class" 
                                   id="class_<?php echo $key; ?>" 
                                   value="<?php echo $key; ?>"
                                   <?php echo (isset($_POST['character_class']) && $_POST['character_class'] === $key) ? 'checked' : ''; ?>
                                   required>
                            <label for="class_<?php echo $key; ?>">
                                <strong><?php echo $class['name']; ?></strong>
                                <span class="class-stats">
                                    HP: <?php echo $class['health']; ?> | 
                                    STR: <?php echo $class['strength']; ?> | 
                                    MAG: <?php echo $class['magic']; ?> | 
                                    AGI: <?php echo $class['agility']; ?>
                                </span>
                                <span class="class-desc"><?php echo $class['description']; ?></span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">Begin Adventure</button>
            </form>
        </div>
        
        <?php elseif ($isEnding): ?>
        <!-- Ending Screen -->
        <div class="ending-screen ending-<?php echo strtolower(str_replace(' ', '-', $nodeData['ending_type'])); ?>">
            <h2 class="ending-title"><?php echo $nodeData['ending_type']; ?></h2>
            <div class="ending-text typewriter">
                <?php echo htmlspecialchars($nodeData['text']); ?>
            </div>
            
            <div class="hero-summary">
                <h3>Hero's Journey Summary</h3>
                <p><?php echo generateHeroSummary(); ?></p>
            </div>
            
            <div class="final-stats">
                <p>Final Score: <strong><?php echo $_SESSION['hero']['score']; ?></strong></p>
                <p>Health Remaining: <?php echo $_SESSION['hero']['health']; ?>/<?php echo $_SESSION['hero']['maxHealth']; ?></p>
                <p>Alignment: <?php echo $_SESSION['alignment'] > 2 ? 'Heroic' : ($_SESSION['alignment'] < -2 ? 'Villainous' : 'Neutral'); ?></p>
            </div>
            
            <div class="ending-actions">
                <form method="POST" action="game.php">
                    <input type="hidden" name="action" value="new_game">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">
                    <button type="submit" class="btn-primary">Start New Adventure</button>
                </form>
                <a href="leaderboard.php" class="btn-secondary">View Leaderboard</a>
            </div>
        </div>
        
        <?php else: ?>
        <!-- Main Game Interface -->
        <div class="game-main">
            <!-- Character Stats Panel -->
            <div class="stats-panel">
                <h3><?php echo htmlspecialchars($_SESSION['hero']['name']); ?> the <?php echo $_SESSION['hero']['className']; ?></h3>
                <div class="stat-bars">
                    <div class="stat">
                        <span>❤️ Health:</span>
                        <div class="stat-bar-container">
                            <div class="stat-bar health-bar" 
                                 style="width: <?php echo ($_SESSION['hero']['health'] / $_SESSION['hero']['maxHealth']) * 100; ?>%">
                            </div>
                        </div>
                        <span><?php echo $_SESSION['hero']['health']; ?>/<?php echo $_SESSION['hero']['maxHealth']; ?></span>
                    </div>
                </div>
                <div class="stat-grid">
                    <div class="stat-item">💪 Strength: <?php echo $_SESSION['hero']['strength']; ?></div>
                    <div class="stat-item">✨ Magic: <?php echo $_SESSION['hero']['magic']; ?></div>
                    <div class="stat-item">🏃 Agility: <?php echo $_SESSION['hero']['agility']; ?></div>
                    <div class="stat-item">🏆 Score: <?php echo $_SESSION['hero']['score']; ?></div>
                </div>
                
                <!-- Inventory -->
                <div class="inventory">
                    <h4>🎒 Inventory</h4>
                    <?php if (empty($_SESSION['hero']['inventory'])): ?>
                        <p class="empty-inventory">Empty</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($_SESSION['hero']['inventory'] as $item): ?>
                                <li><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $item))); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Story Panel -->
            <div class="story-panel">
                <div class="story-text fade-in">
                    <?php echo htmlspecialchars(getClassText($nodeData)); ?>
                </div>
                
                <?php if (isset($requirementError)): ?>
                    <div class="ai-advisor error">
                        <strong>🤖 Story Advisor:</strong> 
                        <?php if ($requirementError['stat'] === 'item'): ?>
                            You need the <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $requirementError['required']))); ?> to choose this path.
                        <?php else: ?>
                            This choice requires <?php echo $requirementError['required']; ?> <?php echo ucfirst($requirementError['stat']); ?> — you have <?php echo $requirementError['current']; ?>. 
                            Gain <?php echo $requirementError['required'] - $requirementError['current']; ?> more to unlock this path.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Choice Form -->
                <?php if (!empty($nodeData['choices'])): ?>
                <form method="POST" action="game.php" class="choices-form">
                    <input type="hidden" name="action" value="make_choice">
                    
                    <?php foreach ($nodeData['choices'] as $index => $choice): ?>
                        <?php $reqCheck = checkRequirements($choice['requires'] ?? null); ?>
                        <div class="choice-container">
                            <button type="submit" 
                                    name="choice_index" 
                                    value="<?php echo $index; ?>"
                                    class="choice-btn <?php echo $reqCheck !== true ? 'choice-locked' : ''; ?>"
                                    <?php echo $reqCheck !== true ? 'disabled' : ''; ?>>
                                <?php echo htmlspecialchars($choice['text']); ?>
                            </button>
                            
                            <?php if (isset($choice['ai_preview'])): ?>
                                <div class="ai-preview">
                                    🤖 <?php echo htmlspecialchars($choice['ai_preview']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($reqCheck !== true): ?>
                                <div class="requirement-hint">
                                    <?php if ($reqCheck['stat'] === 'item'): ?>
                                        🔒 Requires: <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $reqCheck['required']))); ?>
                                    <?php else: ?>
                                        🔒 Requires <?php echo $reqCheck['required']; ?> <?php echo ucfirst($reqCheck['stat']); ?> 
                                        (You have: <?php echo $reqCheck['current']; ?>)
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </form>
                <?php endif; ?>
            </div>
            
            <!-- AI Story Path Tracker Sidebar -->
            <div class="path-tracker">
                <h3>📜 Your Journey</h3>
                <?php if (empty($_SESSION['choices_log'])): ?>
                    <p class="empty-log">Your adventure begins...</p>
                <?php else: ?>
                    <ol class="choice-log">
                        <?php foreach ($_SESSION['choices_log'] as $log): ?>
                            <li>
                                <span class="log-time"><?php echo $log['timestamp']; ?></span>
                                <span class="log-text"><?php echo htmlspecialchars($log['text']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
                
                <div class="alignment-meter">
                    <h4>⚖️ Alignment</h4>
                    <div class="alignment-bar-container">
                        <div class="alignment-bar" 
                             style="width: <?php echo min(100, max(0, (($_SESSION['alignment'] + 10) / 20) * 100)); ?>%">
                        </div>
                    </div>
                    <div class="alignment-labels">
                        <span>😈 Villain</span>
                        <span>😐 Neutral</span>
                        <span>😇 Hero</span>
                    </div>
                    <p class="alignment-value">
                        <?php 
                        $alignment = $_SESSION['alignment'];
                        if ($alignment > 5) echo "Pure Hero";
                        elseif ($alignment > 2) echo "Heroic";
                        elseif ($alignment > 0) echo "Good";
                        elseif ($alignment == 0) echo "Neutral";
                        elseif ($alignment > -2) echo "Morally Gray";
                        elseif ($alignment > -5) echo "Villainous";
                        else echo "Pure Evil";
                        ?>
                        (<?php echo $alignment; ?>)
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>