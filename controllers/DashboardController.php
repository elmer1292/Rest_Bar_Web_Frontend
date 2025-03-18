<?php
require_once '../config/database.php';
require_once '../models/Dashboard.php';

class DashboardController {
    private $dashboard;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->dashboard = new Dashboard($db);
    }

    public function getStats() {
        return $this->dashboard->getStats();
    }
}