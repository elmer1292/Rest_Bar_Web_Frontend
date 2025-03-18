<?php
require_once '../config/database.php';
require_once 'PasswordHash.php';

class Auth {
    private $conn;
    private $passwordHasher;
    
    public function __construct($db) {
        $this->conn = $db;
        $this->passwordHasher = new PasswordHash();
    }
    
    public function login($username, $password) {
        try {
            $query = "CALL sp_ValidateUser(:username)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Here's where we use the verifyPassword method
                if($this->passwordHasher->verifyPassword($password, $row['Contrasenia'])) {
                    $_SESSION['user_id'] = $row['ID_Usuario'];
                    $_SESSION['username'] = $row['Nombre_Usuario'];
                    $_SESSION['role'] = $row['Nombre_Rol'];
                    $_SESSION['full_name'] = $row['Nombre_Completo'] ?? $row['Nombre_Usuario'];
                    return true;
                }
            }
            return false;
        } catch(PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }

    public function logout() {
        $_SESSION = array();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        session_destroy();
        header('Location: /login');
        exit();
    }
}