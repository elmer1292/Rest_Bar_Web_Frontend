<?php
require_once '../includes/auth.php';
require_once '../controllers/DashboardController.php';
require_once 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: restbar/login");
    exit();
}

$dashboardController = new DashboardController();
$stats = $dashboardController->getStats();

// Variables para el header
$pageTitle = "Dashboard - RestBar";
$welcomeMessage = "Bienvenido, " . $_SESSION['full_name'];
$userRole = $_SESSION['role'];


?>

<div class="dashboard-container">
    <nav class="sidebar">
        <div class="user-info">
            <h3><?php echo $_SESSION['full_name']; ?></h3>
            <p><?php echo $_SESSION['role']; ?></p>
        </div>
        <ul class="menu">
            <?php if($userRole === 'Administrador'): ?>
            <li><a href="/restbar/employees">Empleados</a></li>
            <?php endif; ?>
            
            <li><a href="./tables/index.php">Mesas</a></li>
            <li><a href="./products/index.php">Productos</a></li>
            <li><a href="./categories/index.php">Categorías</a></li>
            <li><a href="./sales/index.php">Ventas</a></li>
            <li><a href="./customers/index.php">Clientes</a></li>
            
            <li>
                <form action="/restbar/logout" method="POST">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="logout-btn">Cerrar Sesión</button>
                </form>
            </li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="dashboard-header">
            <h1><?php echo $welcomeMessage; ?></h1>
            <div class="date-time"><?php echo date('d/m/Y H:i'); ?></div>
        </header>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Mesas Activas</h3>
                <p><?php echo $stats['MesasOcupadas']; ?>/<?php echo $stats['TotalMesas']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Ventas del Día</h3>
                <p>$<?php echo number_format($stats['VentasDiarias'], 2); ?></p>
            </div>
            <div class="stat-card">
                <h3>Órdenes del Día</h3>
                <p><?php echo $stats['OrdenesHoy']; ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <h2>Acciones Rápidas</h2>
            <div class="action-buttons">
                <a href="./sales/new.php" class="btn">Nueva Venta</a>
                <a href="./tables/status.php" class="btn">Estado de Mesas</a>
                <a href="./reports/daily.php" class="btn">Reporte del Día</a>
            </div>
        </div>
    </main>
</div>

<script>
    // Update time every minute
    setInterval(function() {
        document.querySelector('.date-time').textContent = new Date().toLocaleString('es-ES');
    }, 60000);
</script>

<?php require_once 'components/footer.php'; ?>