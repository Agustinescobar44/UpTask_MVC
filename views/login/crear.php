<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear Cuenta</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form action="/crear" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre: </label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo s($usuario->nombre) ?>"
                />
            </div>
            
            <div class="campo">
                <label for="email">E-mail: </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email"
                    value="<?php echo s($usuario->email) ?>"
                />
            </div>
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
            <input type="submit" value="Crear Cuenta" class="boton">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div> <!-- contenedor sm -->
</div>
