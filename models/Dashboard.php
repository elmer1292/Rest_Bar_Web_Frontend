<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getStats() {
        try {
            $query = "CALL sp_GetDashboardStats()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return false;
        }
    }
}