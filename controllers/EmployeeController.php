<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Employee.php';

class EmployeeController {
    private $db;
    private $employee;

    public function __construct() {
        $this->db = new Database();
        $this->employee = new Employee($this->db);
    }

    public function index() {
        $employees = $this->employee->getAll();
        $roles = $this->employee->getAllRoles();
        
        return [
            'employees' => $employees,
            'roles' => $roles
        ];
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'Nombre' => $_POST['Nombre'],
                'Apellido' => $_POST['Apellido'],
                'Cargo' => $_POST['Cargo'],
                'Telefono' => $_POST['Telefono'],
                'Correo' => $_POST['Correo'],
                'Nombre_Usuario' => $_POST['Nombre_Usuario'],
                'Contrasenia' => $_POST['Contrasenia']
            ];

            try {
                if ($this->employee->create($data)) {
                    $_SESSION['success'] = "Empleado creado exitosamente";
                    header('Location: /restbar/employees');
                    exit;
                } else {                    
                    $_SESSION['error'] = "Error al crear el empleado";
                    header('Location: /restbar/employees/create');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al crear el empleado: " . $e->getMessage();
                header('Location: /restbar/employees/create');
                exit;
            }
        }
    }
    
    public function update($id, $data) {
        if($this->employee->update($id, $data)) {
            $_SESSION['success'] = "Empleado actualizado exitosamente.";
            return true;
        }
        $_SESSION['error'] = "Error al actualizar el empleado.";
        return false;
    }
    
    public function delete($id) {
        if($this->employee->delete($id)) {
            $_SESSION['success'] = "Empleado eliminado exitosamente.";
            return true;
        }
        $_SESSION['error'] = "Error al eliminar el empleado.";
        return false;
    }
    
    public function show($id) {
        return $this->employee->getById($id);
    }
    
    
    public function getById($id) {
        $employee = $this->employee->getById($id);
        if (!$employee) {
            $_SESSION['error'] = "Empleado no encontrado.";
            return null;
        }
        return ['employee' => $employee];
    }
}