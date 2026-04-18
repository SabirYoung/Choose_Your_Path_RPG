<?php
require_once 'includes/config.php';
require_once 'includes/function.php';

$leaderboard = getLeaderboard(15);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Forgotten Crypt</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="leaderboard-container">
        <header class="leaderboard-header">
            <h1>🏆 Hall of Heroes 🏆</h1>
            <div class="nav-links">
                <a href="game.php">Return to Game</a>
                <a href="logout.php">Logout</a>
            </div>
        </header>
        
        <div class="leaderboard-content">
            <?php if (empty($leaderboard)): ?>
                <p class="no-scores">No heroes have completed their journey yet. Be the first!</p>
            <?php else: ?>
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Hero</th>
                            <th>Class</th>
                            <th>Ending</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $index => $entry): ?>
                        <tr class="<?php echo $index < 3 ? 'top-' . ($index + 1) : ''; ?>">
                            <td class="rank">#<?php echo $index + 1; ?></td>
                            <td class="username"><?php echo htmlspecialchars($entry['username']); ?></td>
                            <td class="class"><?php echo htmlspecialchars($entry['class']); ?></td>
                            <td class="ending"><?php echo htmlspecialchars($entry['ending']); ?></td>
                            <td class="score"><?php echo $entry['score']; ?></td>
                            <td class="date"><?php echo date('M j, Y', strtotime($entry['timestamp'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>