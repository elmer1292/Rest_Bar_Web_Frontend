<?php
session_start();
require_once '../../controllers/EmployeeController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrador') {
    header("Location: ../auth/login.php");
    exit();
}

$controller = new EmployeeController();
$employee = $controller->show($_GET['id']);

if (!$employee) {
    $_SESSION['error'] = "Empleado no encontrado";
    header("Location: index.php");
    exit();
}

// Variables para el header
$pageTitle = "Editar Empleado";
$backUrl = "index.php";

require_once '../components/header.php';
?>

<form action="../../controllers/EmployeeController.php" method="POST" class="form">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?php echo $employee['ID_Empleado']; ?>">
    
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $employee['Nombre']; ?>" required>
    </div>

    <div class="form-group">
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo $employee['Apellido']; ?>" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo $employee['Telefono']; ?>" required>
    </div>

    <div class="form-group">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $employee['Correo']; ?>" required>
    </div>

    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <textarea id="direccion" name="direccion" required><?php echo $employee['Direccion']; ?></textarea>
    </div>

    <div class="form-group">
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $employee['Fecha_Nacimiento']; ?>" required>
    </div>

    <div class="form-group">
        <label for="fecha_contratacion">Fecha de Contratación:</label>
        <input type="date" id="fecha_contratacion" name="fecha_contratacion" value="<?php echo $employee['Fecha_Contratacion']; ?>" required>
    </div>

    <div class="form-group">
        <label for="salario">Salario:</label>
        <input type="number" id="salario" name="salario" step="0.01" value="<?php echo $employee['Salario']; ?>" required>
    </div>

    <div class="form-group">
        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" value="<?php echo $employee['Cargo']; ?>" required>
    </div>

    <div class="form-group">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" value="<?php echo $employee['Nombre_Usuario']; ?>" required>
    </div>

    <div class="form-group">
        <label for="role_id">Rol:</label>
        <select id="role_id" name="role_id" required>
            <option value="2" <?php echo $employee['ID_Rol'] == 2 ? 'selected' : ''; ?>>Mesero</option>
            <option value="3" <?php echo $employee['ID_Rol'] == 3 ? 'selected' : ''; ?>>Cocinero</option>
            <option value="4" <?php echo $employee['ID_Rol'] == 4 ? 'selected' : ''; ?>>Cajero</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
</form>

<?php require_once '../components/footer.php'; ?>