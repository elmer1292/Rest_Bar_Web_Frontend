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
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al obtener empleado: " . $e->getMessage();
            return null;
        }
    }

    public function create($data) {
        try {
            // First create user
            $queryUser = "CALL sp_CreateUser(?, ?, ?, @userId)";
            $stmt = $this->conn->prepare($queryUser);
            
            $hashedPassword = password_hash($data['Contrasenia'], PASSWORD_DEFAULT);
            
            $stmt->execute([
                $data['Nombre_Usuario'],
                $hashedPassword,
                $data['Cargo']
            ]);
            
            // Get the user ID
            $result = $this->conn->query("SELECT @userId as id")->fetch(PDO::FETCH_ASSOC);
            $userId = $result['id'];
            
            // Then create employee
            $queryEmp = "CALL sp_CreateEmployeeWithUser(?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($queryEmp);
            
            return $stmt->execute([
                $userId,
                $data['Nombre'],
                $data['Apellido'],
                $data['Telefono'],
                $data['Correo'],
                $data['Salario'] ?? 0.00  // Added salary field
            ]);
            
        } catch (PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
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
}