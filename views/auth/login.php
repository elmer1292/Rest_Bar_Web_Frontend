<?php
// Remover session_start() de aquí ya que está en index.php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
require_once $headerPath;
?>
<div class="login-page">
    <div class="login-container">
        <h2>RestBar Login</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form action="/restbar/login" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            
            <input type="hidden" name="action" value="login">
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</div>
<?php
require_once $footerPath;
?>