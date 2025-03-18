<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
$headerHeadPath = dirname(__DIR__) . '/components/header_head.php';

// Variables para el header
$pageTitle = "Crear Empleado";
$backUrl = "/restbar/employees";
require_once $headerPath;
?>

<div class="main-content">
    <div class="dashboard-header">
        <h2><?php echo $pageTitle; ?></h2>
    </div>
    <?php require_once $headerHeadPath; ?>

    <div class="form-container">
        <form action="/restbar/employees/store" method="POST" class="form">
            <div class="form-group">
                <label for="Nombre">Nombre</label>
                <input type="text" id="Nombre" name="Nombre" required>
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido</label>
                <input type="text" id="Apellido" name="Apellido" required>
            </div>

            <div class="form-group">
                <label for="Cargo">Cargo</label>
                <select id="Cargo" name="Cargo" required>
                    <option value="">Seleccione un cargo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Mesero</option>
                    <option value="3">Cajero</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Telefono">Teléfono</label>
                <input type="tel" id="Telefono" name="Telefono" required>
            </div>

            <div class="form-group">
                <label for="Correo">Correo Electrónico</label>
                <input type="email" id="Correo" name="Correo" required>
            </div>

            <div class="form-group">
                <label for="Nombre_Usuario">Nombre de Usuario</label>
                <input type="text" id="Nombre_Usuario" name="Nombre_Usuario" required>
            </div>

            <div class="form-group">
                <label for="Contrasenia">Contraseña</label>
                <input type="password" id="Contrasenia" name="Contrasenia" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/restbar/employees" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once $footerPath; ?>