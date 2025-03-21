<?php
session_start();
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/auth.php';

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
    '/restbar/employees/edit/(\d+)' => '/views/employees/edit.php',
    '/restbar/employees/update/(\d+)' => '/views/employees/update.php', // New route    
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
    // En la sección de rutas, asegúrate de que la ruta de login incluya el archivo de vista
    '/restbar/login' => [
        'GET' => function() {
            if (isset($_SESSION['user_id'])) {
                header('Location: /restbar/dashboard');
                exit();
            }
            require_once BASE_PATH . '/views/auth/login.php'; // Añadir esta línea
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
error_log("Request URI: " . $_SERVER['REQUEST_URI']);

// Sort routes by length (longest first) to ensure more specific routes are matched first
$routeKeys = array_keys($routes);
usort($routeKeys, function($a, $b) {
    return strlen($b) - strlen($a); // Sort by length, longest first
});

// Y en la sección donde procesas las rutas, añade:
foreach ($routeKeys as $url) {
    $file = $routes[$url];
    error_log("Checking route: " . $url . " against request: " . $request);
    
    // Para rutas con patrones como /restbar/employees/edit/(\d+)
    if (strpos($url, '(\d+)') !== false) {
        // Convertir la ruta con (\d+) a una expresión regular
        $pattern = '#^' . str_replace('(\d+)', '(\d+)', $url) . '$#';
        if (preg_match($pattern, $request, $matches)) {
            error_log("Route matched with pattern: " . $url);
            
            if (strpos($url, '/restbar/employees/edit/') === 0) {
                $id = $matches[1]; // Extract the ID from the URL
                require_once BASE_PATH . '/controllers/EmployeeController.php';
                $controller = new EmployeeController();
                $employee = $controller->show($id);
                
                if ($employee) {
                    $data['employee'] = $employee;
                    $pageTitle = "Editar Empleado";
                    $backUrl = "/restbar/employees";
                    require_once BASE_PATH . $file; // Directly include the file
                } else {
                    $_SESSION['error'] = "Empleado no encontrado.";
                    header('Location: /restbar/employees');
                    exit;
                }
                
                $found = true;
                break;
            }
        }
    }
    // Para rutas simples sin patrones
    else if ($url === $request) {  // Coincidencia exacta para rutas simples
        error_log("Route matched exactly: " . $url);
        if (is_array($file)) {
            $method = $_SERVER['REQUEST_METHOD'];
            if (isset($file[$method])) {
                if (is_callable($file[$method])) {
                    $file[$method]();
                } else if ($method === 'POST') {
                    require_once BASE_PATH . $file[$method];
                } else {
                    require_once BASE_PATH . $file[$method]; // Directly include the file
                }
                $found = true;
                break;
            }
        } else if (is_callable($file)) {
            $file();
            $found = true;
            break;
        } else {
            require_once BASE_PATH . $file; // Directly include the file
            $found = true;
            break;
        }
    }
} 
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

// 404 handling
if (!$found) {
    http_response_code(404);
    require_once BASE_PATH . '/views/errors/404.php'; // Directly include the file
}