<?php
class Category {
    private $db;
    private $conn;

    public function __construct() {
        $this->conn = $database->getConnection();
    }
    public function getAllCategories() {
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
    
    function update($id, $name) {
        try { 
            $query = "update categories set Nombre_Categoria = :name where id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al obtener categorias: " . $e->getMessage();
            return [];
        }
    }
}
