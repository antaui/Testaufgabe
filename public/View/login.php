<?php
session_start();

require '../../src/autoload.php';

use Test\Model\User;
use Test\Model\Log;

$userModel = new User('../../data/user.csv');
$logModel = new Log('../../data/log.csv');

$message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $userModel->getUserByUsername($username);

    if ($user) {
        if ($user['blocked'] == 1) {
            $message = "Benutzer ist gesperrt!";
        } elseif ($user['password'] === $password) {
            // Erfolgreiches Login
            $userModel->resetFailedAttempts($username);
            $userModel->updateLastLogin($username);
            $logModel->addLog($username, "Erfolgreich eingeloggt");

            $_SESSION['username'] = $username;

            // Weiterleitung zum Dashboard
            header("Location: dashboard.php");
            exit;

        } else {
            // Falsches Passwort
            $userModel->incrementFailedAttempts($username);
            $logModel->addLog($username, "Falsches Passwort");

            if ($user['failed'] + 1 >= 3) {
                $userModel->blockUser($username);
                $logModel->addLog($username, "Benutzer gesperrt wegen zu vieler Fehlversuche");
                $message = "Benutzer wurde gesperrt!";
            } else {
                $message = "Falsches Passwort!";
            }
        }
    } else {
        $message = "Benutzer nicht gefunden!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login Test</title>
</head>
<body>
    <h2>Login Test</h2>
    <form method="post">
        Benutzername: <input type="text" name="username"><br>
        Passwort: <input type="password" name="password"><br>
        <button type="submit">Login</button>
    </form>
    <p style="color:red;"><?= htmlspecialchars($message) ?></p>
</body>
</html>
