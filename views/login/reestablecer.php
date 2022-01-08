<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <p class="descripcion-pagina">Coloca tu Nuevo Password</p>
        <?php if($mostrar){ ?>
            <form class="formulario" method="POST">
                
                <div class="campo">
                    <label for="password">Password: </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="Tu Password"
                    />
                </div>
                <div class="campo">
                    <label for="password2">Repetir Password: </label>
                    <input 
                        type="password" 
                        name="password2" 
                        id="password2"
                        placeholder="Repite Tu Password"
                    />
                </div>
                
                <input type="submit" value="Reestablecer password" class="boton">
            </form>
        <?php } ?>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div> <!-- contenedor sm -->
</div>
