<header>
            <?php if(isset($backUrl)): ?>
                <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">Volver</a>
            <?php endif; ?>
            <?php if(isset($createUrl)): ?>
                <a href="<?php echo $createUrl; ?>" class="btn btn-primary"><?php echo $createText ?? 'Nuevo'; ?></a>
            <?php endif; ?>
</header>