<?php
require_once '../includes/PasswordHash.php';

class User {
    private $conn;
    private $passwordHasher;

    public function __construct($db) {
        $this->conn = $db;
        $this->passwordHasher = new PasswordHash();
    }

    public function getAll() {
        try {
            $query = "CALL sp_GetAllUsers()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting users: " . $e->getMessage());
            return false;
        }
    }

    public function create($username, $password, $roleId, $employeeId) {
        try {
            $hashedPassword = $this->passwordHasher->hashPassword($password);
            $query = "CALL sp_CreateUser(:username, :password, :roleId, :employeeId)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":roleId", $roleId);
            $stmt->bindParam(":employeeId", $employeeId);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function update($userId, $username, $password = null, $roleId) {
        try {
            $hashedPassword = $password ? $this->passwordHasher->hashPassword($password) : null;
            $query = "CALL sp_UpdateUser(:userId, :username, :password, :roleId)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":roleId", $roleId);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function delete($userId) {
        try {
            $query = "CALL sp_DeleteUser(:userId)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":userId", $userId);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}