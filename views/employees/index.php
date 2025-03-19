<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
require_once dirname(dirname(__DIR__)) . '/controllers/EmployeeController.php';

// Initialize controller and get employees
$controller = new EmployeeController();
$result = $controller->index();
$employees = $result['employees'] ?? [];
$roles = $result['roles'] ?? [];

// Variables para el header
$pageTitle = "Gestión de Empleados";
$backUrl = "/restbar/dashboard";
$createUrl = "/restbar/employees/create"; // Establecer la URL para crear un nuevo empleado
$createText = "Nuevo Empleado";

require_once $headerPath;

?>
<div class="main-content">
    <div class="dashboard-header">
        <h2><?php echo $pageTitle; ?></h2>
    </div>
    <header>
                <?php if(isset($backUrl)): ?>
                    <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">Volver</a>
                <?php endif; ?>
                <?php if(isset($createUrl)): ?>
                    <a href="<?php echo $createUrl; ?>" class="btn btn-primary"><?php echo $createText ?? 'Nuevo'; ?></a>
                <?php endif; ?>
    </header>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cargo</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if($employees && count($employees) > 0): ?>
                <?php foreach($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee['ID_Empleado']; ?></td>
                        <td><?php 
                            $nombreCompleto = explode(' ', $employee['Nombre_Completo']);
                            echo $nombreCompleto[0]; // First name
                        ?></td>
                        <td><?php 
                            echo isset($nombreCompleto[1]) ? $nombreCompleto[1] : ''; // Last name
                        ?></td>
                        <td><?php 
                            // Replace hardcoded array with dynamic data
                            $roleName = 'Desconocido';
                            foreach ($roles as $role) {
                                if ($role['ID_Rol'] == $employee['ID_Rol']) {
                                    $roleName = $role['Nombre_Rol'];  // Use Nombre_Rol from SP
                                    break;
                                }
                            }
                            echo $roleName;
                        ?></td>
                        <td><?php echo $employee['Telefono']; ?></td>
                        <td><?php echo $employee['Correo']; ?></td>
                        <td><?php echo $employee['Nombre_Usuario']; ?></td>
                        <td class="actions">
                            <a href="/restbar/employees/edit/<?php echo $employee['ID_Usuario']; ?>" class="btn btn-small">Editar</a>
                            <form action="/restbar/employees/delete" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $employee['ID_Empleado']; ?>">
                                <button type="submit" class="btn btn-small btn-danger" 
                                        onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay empleados registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once $footerPath; ?>