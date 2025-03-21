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
            return $result;
        } catch (PDOException $e) {
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
            // First, we need to get the ID_Empleado from the ID_Usuario
            $query = "SELECT ID_Empleado FROM empleados WHERE ID_Usuario = ?";
            $stmt = $this->conn->prepare($query);
           
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            $empleadoId = $result['ID_Empleado'];
    
            // Now call the stored procedure with the correct ID_Empleado
            $query = "CALL sp_UpdateEmployee(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            // Convert Cargo to integer
            $cargo = (int) $data['Cargo'];
            
            return $stmt->execute([
                $empleadoId,         // p_ID_Empleado
                $data['Nombre_Completo'],     // p_Nombre
                $data['Correo'],     // p_Correo
                $data['Telefono'],   // p_Telefono
                $id,                 // p_ID_Usuario
                $data['Nombre_Usuario'], // p_Nombre_Usuario
                $cargo,              // p_ID_Rol
            ]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al actualizar empleado: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "CALL sp_DeleteEmployee(?)";
            $stmt = $this->conn->prepare($query);  // Changed from $this->db to $this->conn
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