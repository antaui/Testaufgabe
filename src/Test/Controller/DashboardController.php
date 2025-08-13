<?php
namespace Test\Controller;

class DashboardController {
    public function showDashboard($username) {
        include __DIR__ . '/../../public/dashboard.php';
    }
}