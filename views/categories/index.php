<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
require_once dirname(dirname(__DIR__)) . '/controllers/CategoryController.php';


// Obtener los datos del resultado del controlador
// Initialize controller and get employees
$controller = new CategoryController();
$result = $controller->index();
$categories = $result['categories'] ?? [];

// Variables para el header
$pageTitle = "Gestión de Categorias";
$backUrl = "/restbar/dashboard";
$createUrl = "/restbar/categories/create"; // Establecer la URL para crear un nuevo empleado
$createText = "Nueva Categoria";
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
                <th>Categoria</th>

                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if($categories && count($categories) > 0): ?>
                <?php foreach($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['ID_Categoria']; ?></td>
                        <td><?php echo $cat['Nombre_Categoria']; ?></td>
                        <td class="actions">
                            <a href="/restbar/categories/edit/<?php echo $cat['ID_Categoria']; ?>" class="btn btn-small">Editar</a>
                            <!-- <form action="/restbar/employees/delete" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<php echo $employee['ID_Empleado']; ?>">
                                <button type="submit" class="btn btn-small btn-danger" 
                                        onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                    Eliminar
                                </button>
                            </form> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay categorias registradas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once $footerPath;
?>