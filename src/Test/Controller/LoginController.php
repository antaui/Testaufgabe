<?php
namespace Test\Controller;

use Test\Model\User;
use Test\Helper\Validator;

class LoginController {
    public function showLoginForm() {
        include __DIR__ . '/../../public/login.php';
    }

    public function handleLogin() {
        // Später: Validierung, Login-Logik, Weiterleitung
    }
}