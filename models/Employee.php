<?php
class Employee {
    private $db;
    private $conn;

    public function __construct($database) {
        $this->db = $database;
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        try {
            $query = "CALL sp_GetAllEmployees()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al obtener empleados: " . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        try {
            $query = "CALL sp_GetEmployeeById(?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("GetById result: " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            $_SESSION['error'] = "Error al obtener empleado: " . $e->getMessage();
            return null;
        }
    }

    public function create($data) {
        try {
            // Modified to not include salary
            $query = "CALL sp_CreateUserWithEmployee(?, ?, ?, ?, ?, ?, CURDATE(), NULL)";
            $stmt = $this->conn->prepare($query);
            
            $hashedPassword = password_hash($data['Contrasenia'], PASSWORD_DEFAULT);
            $nombreCompleto = $data['Nombre'] . ' ' . $data['Apellido'];
            
            $params = [
                $data['Nombre_Usuario'],
                $hashedPassword,
                $data['Cargo'],
                $nombreCompleto,
                $data['Correo'],
                $data['Telefono'],
                // Passing NULL for salary
            ];
            
            $result = $stmt->execute($params);
            return $result;
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $query = "CALL sp_UpdateEmployee(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $id,
                $data['Nombre'],
                $data['Apellido'],
                $data['Cargo'],
                $data['Telefono'],
                $data['Correo'],
                $data['Nombre_Usuario']
            ]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al actualizar empleado: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "CALL sp_DeleteEmployee(?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al eliminar empleado: " . $e->getMessage();
            return false;
        }
    }

    public function getAllRoles() {
        try {
            $query = "CALL sp_GetAllRols()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting roles: " . $e->getMessage());
            return [];
        }
    }
}