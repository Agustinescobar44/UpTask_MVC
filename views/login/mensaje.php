<div class="contenedor mensaje">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Hemos enviado las instrucciones de confirmacion a tu email!</p>
        <p class="email-usuario"><?php echo $_GET['email'] ?? "" ?></p>
    </div> <!-- contenedor sm -->
</div>
