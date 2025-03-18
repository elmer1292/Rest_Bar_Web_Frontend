<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
$headerHeadPath = dirname(__DIR__) . '/components/header_head.php';
require_once dirname(dirname(__DIR__)) . '/controllers/EmployeeController.php';

// Initialize controller and get employees
$controller = new EmployeeController();
$employees = $controller->index();

// Variables para el header
$pageTitle = "Gestión de Empleados";
$backUrl = "/restbar/dashboard";
$createUrl = "/restbar/employees/create";
$createText = "Nuevo Empleado";

require_once $headerPath;

?>
<div class="main-content">
    <div class="dashboard-header">
        <h2><?php echo $pageTitle; ?></h2>
        
    </div>
    <?php require_once $headerHeadPath; ?>
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
                        <td><?php echo $employee['Nombre']; ?></td>
                        <td><?php echo $employee['Apellido']; ?></td>
                        <td><?php echo $employee['Cargo']; ?></td>
                        <td><?php echo $employee['Telefono']; ?></td>
                        <td><?php echo $employee['Correo']; ?></td>
                        <td><?php echo $employee['Nombre_Usuario']; ?></td>
                        <td class="actions">
                            <a href="/restbar/employees/edit?id=<?php echo $employee['ID_Empleado']; ?>" 
                               class="btn btn-small">Editar</a>
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