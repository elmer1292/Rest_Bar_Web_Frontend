<?php
session_start();
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/auth.php';

// Function to load view with header and footer
function loadView($file) {
    global $pageTitle, $backUrl, $createUrl, $createText, $isLoginPage;
    
    // Set default values if not set
    $pageTitle = $pageTitle ?? 'RestBar';
    $backUrl = $backUrl ?? null;
    $createUrl = $createUrl ?? null;
    $createText = $createText ?? null;
    $isLoginPage = $isLoginPage ?? false;
    
    require_once BASE_PATH . $file;
}

// Basic routing
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);
$request = str_replace('/public', '', $request);

// Simplify root handling - redirect all root variations to login
if (in_array($request, ['/', '/restbar', '/restbar/'])) {
    header('Location: /restbar/login');
    exit();
}

// Define routes...
$routes = [
    '/restbar/dashboard' => '/views/dashboard.php',
    '/restbar/employees' => '/views/employees/index.php',
    '/restbar/employees/create' => '/views/employees/create.php',
    '/restbar/employees/store' => [
        'POST' => function() {
            require_once BASE_PATH . '/controllers/EmployeeController.php';
            $controller = new EmployeeController();
            $controller->store();
        }
    ],
    '/restbar/tables' => '/views/tables/index.php',
    '/restbar/products' => '/views/products/index.php',
    '/restbar/categories' => '/views/categories/index.php',
    '/restbar/sales' => '/views/sales/index.php',
    '/restbar/customers' => '/views/customers/index.php',
    '/restbar/login' => [
        'GET' => function() {
            if (isset($_SESSION['user_id'])) {
                header('Location: /restbar/dashboard');
                exit();
            }
            loadView('/views/auth/login.php');
        },
        'POST' => '/controllers/AuthController.php'
    ],
    '/restbar/logout' => function() {
        session_destroy();
        header('Location: /restbar/login');
        exit();
    }
];

// Check if route exists
$found = false;
foreach ($routes as $url => $file) {
    if (strpos($request, $url) === 0) {  // Match the base URL
        if (is_array($file)) {
            $method = $_SERVER['REQUEST_METHOD'];
            if (isset($file[$method])) {
                if (is_callable($file[$method])) {
                    $file[$method]();
                } else if ($method === 'POST') {
                    require_once BASE_PATH . $file[$method];
                } else {
                    loadView($file[$method]);
                }
                $found = true;
                break;
            }
        } else if (is_callable($file)) {
            $file();
            $found = true;
            break;
        } else {
            loadView($file);
            $found = true;
            break;
        }
    }
    
    // Add this before the 404 handling
    // Check if the URL matches a pattern like /restbar/employees/edit/6
    if (!$found && preg_match('#^/restbar/employees/edit/(\d+)$#', $request, $matches)) {
        $id = $matches[1]; // Extract the ID from the URL
        require_once BASE_PATH . '/controllers/EmployeeController.php';
        $controller = new EmployeeController();
        $employee = $controller->show($id);
        
        if ($employee) {
            $data['employee'] = $employee;
            $pageTitle = "Editar Empleado";
            $backUrl = "/restbar/employees";
            loadView('/views/employees/edit.php');
        } else {
            $_SESSION['error'] = "Empleado no encontrado.";
            header('Location: /restbar/employees');
            exit;
        }
        
        $found = true;
    }
    
    // Add this before the 404 handling
    // Handle the update form submission
    if (!$found && preg_match('#^/restbar/employees/update/(\d+)$#', $request, $matches)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $matches[1];
            require_once BASE_PATH . '/controllers/EmployeeController.php';
            $controller = new EmployeeController();
            if ($controller->update($id, $_POST)) {
                $_SESSION['success'] = "Empleado actualizado exitosamente.";
                header('Location: /restbar/employees');
            } else {
                $_SESSION['error'] = "Error al actualizar el empleado.";
                header('Location: /restbar/employees/edit/' . $id);
            }
            exit;
        }
    }
}

// 404 handling
if (!$found) {
    http_response_code(404);
    loadView('/views/errors/404.php');
}