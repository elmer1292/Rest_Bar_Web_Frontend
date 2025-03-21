<?php
require_once dirname(__DIR__) . '/config/database.php';
class Category{
    private $conn;
    private $db;  // Add this line to store the database instance

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();  // Use $this->db instead of $db
    }
    function getAll() {
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
?>