<?php
require_once '../config/database.php';
require_once '../models/User.php';

class UserController {
    private $user;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->user = new User($db);
    }

    public function getAllUsers() {
        return $this->user->getAll();
    }

    public function createUser($username, $password, $roleId, $employeeId) {
        return $this->user->create($username, $password, $roleId, $employeeId);
    }

    public function updateUser($userId, $username, $password, $roleId) {
        return $this->user->update($userId, $username, $password, $roleId);
    }

    public function deleteUser($userId) {
        return $this->user->delete($userId);
    }
}