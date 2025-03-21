<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';

// Variables para el header
$pageTitle = "Crear Empleado";
$backUrl = "/restbar/employees";
require_once $headerPath;
?>

<div class="main-content">
    <div class="dashboard-header">
        <h2><?php echo $pageTitle; ?></h2>
    </div>

    <div class="form-container">
        <form action="/restbar/employees/store" method="POST" class="form" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="Nombre">Nombre</label>
                <input type="text" id="Nombre" name="Nombre" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" maxlength="50">
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido</label>
                <input type="text" id="Apellido" name="Apellido" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" maxlength="50">
            </div>

            <div class="form-group">
                <label for="Cargo">Rol</label>
                <select id="Cargo" name="Cargo" required>
                    <option value="">Seleccione un Rol</option>
                    <option value="1">Administrador</option>
                    <option value="2">Mesero</option>
                    <option value="3">Cajero</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Telefono">Teléfono</label>
                <input type="tel" id="Telefono" name="Telefono" required pattern="[0-9]{8}" maxlength="8">
            </div>

            <!-- Salary field removed -->

            <div class="form-group">
                <label for="Correo">Correo Electrónico</label>
                <input type="email" id="Correo" name="Correo" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="Nombre_Usuario">Nombre de Usuario</label>
                <input type="text" id="Nombre_Usuario" name="Nombre_Usuario" required pattern="[A-Za-z0-9_]+" maxlength="30">
            </div>

            <div class="form-group">
                <label for="Contrasenia">Contraseña</label>
                <input type="password" id="Contrasenia" name="Contrasenia" required minlength="8" maxlength="255">
            </div>

            <!-- Add JavaScript validation -->
            <script>
            function validateForm() {
                const nombre = document.getElementById('Nombre').value.trim();
                const apellido = document.getElementById('Apellido').value.trim();
                const cargo = document.getElementById('Cargo').value;
                const telefono = document.getElementById('Telefono').value.trim();
                const correo = document.getElementById('Correo').value.trim();
                const usuario = document.getElementById('Nombre_Usuario').value.trim();
                const password = document.getElementById('Contrasenia').value;

                if (!nombre || !apellido || !cargo || !telefono || !correo || !usuario || !password) {
                    alert('Todos los campos son obligatorios');
                    return false;
                }

                // Salary validation removed

                if (!/^[A-Za-z0-9_]+$/.test(usuario)) {
                    alert('El nombre de usuario solo puede contener letras, números y guiones bajos');
                    return false;
                }

                if (password.length < 6) {
                    alert('La contraseña debe tener al menos 6 caracteres');
                    return false;
                }

                if (!/^[0-9]{8}$/.test(telefono)) {
                    alert('El teléfono debe contener 8 dígitos');
                    return false;
                }

                return true;
            }
            </script>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/restbar/employees" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once $footerPath; ?>