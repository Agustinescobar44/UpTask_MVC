<div class="contenedor confirmar">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <?php if(empty($alertas)): ?>
            <p class="descripcion-pagina">Tu cuenta ah sido confirmada con exito!</p>
        <?php endif; ?>

        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
        </div>
    </div> <!-- contenedor sm -->
</div>
