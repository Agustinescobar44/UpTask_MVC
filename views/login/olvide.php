<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <p class="descripcion-pagina">Reestablecer Password</p>
        <form action="/olvide" class="formulario" method="POST">
            
            <div class="campo">
                <label for="email">E-mail: </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email"
                />
            </div>
            
            <input type="submit" value="Reestablecer password" class="boton">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crea una!</a>
        </div>
    </div> <!-- contenedor sm -->
</div>
