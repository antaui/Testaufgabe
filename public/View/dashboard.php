<?php
session_start();
require '../../src/autoload.php';

use Test\Model\User;
use Test\Model\Log;

// Falls nicht eingeloggt → zurück zum Login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// CSV laden
$userModel = new User('../../data/user.csv');
$logModel = new Log('../../data/log.csv');

// User-Daten holen
$user = $userModel->getUserByUsername($username);

// Letzte 5 Logs holen
$logs = $logModel->getLogsByUser($username, 5);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Willkommen, <?= htmlspecialchars($username) ?>!</h1>
    <p>Letzter Login: <?= htmlspecialchars($user['lastlogin'] ?? 'Keine Daten') ?></p>

    <h2>Letzte Aktivitäten</h2>
    <ul>
        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): ?>
                <li><?= htmlspecialchars($log['date']) ?> - <?= htmlspecialchars($log['action']) ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Keine Aktivitäten gefunden</li>
        <?php endif; ?>
    </ul>

    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
