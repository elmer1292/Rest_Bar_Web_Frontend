<?php
$headerPath = dirname(__DIR__) . '/components/header.php';
$footerPath = dirname(__DIR__) . '/components/footer.php';
require_once $headerPath;
$pageTitle = 'Error 404';
$isLoginPage = false;
?>

<div class="error-page">
    <div class="error-container">
        <h1>Error 404</h1>
        <p>Lo sentimos, la página que buscas no existe.</p>
        <a href="/restbar/login" class="btn btn-primary">Volver al inicio</a>
    </div>
</div>
<?php
require_once $footerPath;
?>