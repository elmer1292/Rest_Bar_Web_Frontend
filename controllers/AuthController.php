<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/Security.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = Security::sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!Security::validateUsername($username)) {
            $_SESSION['error'] = "Usuario inválido";
            header("Location: /restbar/login");
            exit();
        }
        
        if ($auth->login($username, $password)) {
            header("Location: /restbar/dashboard");
            exit();
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos";
            header("Location: /restbar/login");
            exit();
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        $auth->logout();
        if ($action === 'logout') {
            session_destroy();
            header('Location: /restbar/login');
            exit();
        }
    }
}