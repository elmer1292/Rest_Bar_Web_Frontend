<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
// Extract the ID from the URL path
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', $uri);
$id = end($parts); // Get the last part of the URL which should be the ID

// Check if employee data was found
if (empty($employee)) {
    $_SESSION['error'] = "No se encontró información del empleado.";
    header('Location: /restbar/employees');
    exit;
}

// Variables para el header
$pageTitle = "Editar Empleado";
$backUrl = "/restbar/employees";

// Include the header to start a new page
require_once $headerPath;

?>

<div class="main-content">
    <div class="dashboard-header">
        <h2><?php echo $pageTitle; ?></h2>
    </div>
    <div class="form-container">
        <form action="/restbar/employees/update/<?php echo $employee['ID_Usuario']; ?>" method="POST" class="form" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="Nombre_Completo">Nombre Completo</label>
                <input type="text" id="Nombre_Completo" name="Nombre_Completo" value="<?php echo htmlspecialchars($employee['Nombre_Completo']); ?>" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" maxlength="100">
            </div>

            <div class="form-group">
                <label for="Cargo">Cargo</label>
                <select id="Cargo" name="Cargo" required>
                    <option value="">Seleccione un cargo</option>
                    <option value="1" <?php echo ($employee['ID_Rol'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo ($employee['ID_Rol'] == 2) ? 'selected' : ''; ?>>Mesero</option>
                    <option value="3" <?php echo ($employee['ID_Rol'] == 3) ? 'selected' : ''; ?>>Cajero</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Telefono">Teléfono</label>
                <input type="tel" id="Telefono" name="Telefono" value="<?php echo htmlspecialchars($employee['Telefono']); ?>" required pattern="[0-9]{8}" maxlength="8">
            </div>

            <div class="form-group">
                <label for="Correo">Correo Electrónico</label>
                <input type="email" id="Correo" name="Correo" value="<?php echo htmlspecialchars($employee['Correo']); ?>" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="Nombre_Usuario">Nombre de Usuario</label>
                <input type="text" id="Nombre_Usuario" name="Nombre_Usuario" value="<?php echo htmlspecialchars($employee['Nombre_Usuario']); ?>" required pattern="[A-Za-z0-9_]+" maxlength="30">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="/restbar/employees" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
// Include the footer to properly close the page
require_once $footerPath; 
?>