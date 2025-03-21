<?php
class Customers {
    private $db;
    private $conn;

    public function __construct() {
        $this->conn = $database->getConnection();
    }
    public function getAllCustomers() {
        try {
            $query = "CALL sp_GetAllCategories()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al obtener categorias: " . $e->getMessage();
            return [];
        }
    }
}