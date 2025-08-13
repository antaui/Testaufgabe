<?php
namespace Test\Controller;

use Test\Model\User;
use Test\Model\Log;

class LoginController {
    private User $userModel;
    private Log $logModel;

    public function __construct() {
        // Modelle mit den richtigen CSV-Dateien initialisieren
        $this->userModel = new User('data/user.csv');
        $this->logModel = new Log('data/log.csv');
    }

    public function login(string $username, string $password): bool {
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            // Benutzer existiert nicht
            $this->logModel->addLog($username, "Login fehlgeschlagen: Benutzer existiert nicht");
            return false;
        }

        if ($user['blocked'] == 1) {
            // Benutzer gesperrt
            $this->logModel->addLog($username, "Login fehlgeschlagen: Benutzer gesperrt");
            return false;
        }

        if ($user['password'] === $password) {
            // Login erfolgreich
            $this->userModel->resetFailedAttempts($username);
            $this->userModel->updateLastLogin($username);
            $this->logModel->addLog($username, "Login erfolgreich");
            return true;
        } else {
            // Passwort falsch
            $this->userModel->incrementFailedAttempts($username);
            $this->logModel->addLog($username, "Login fehlgeschlagen: falsches Passwort");

            if (($user['failed'] + 1) >= 3) {
                $this->userModel->blockUser($username);
                $this->logModel->addLog($username, "Benutzer gesperrt wegen zu vieler Fehlversuche");
            }
            return false;
        }
    }
}
