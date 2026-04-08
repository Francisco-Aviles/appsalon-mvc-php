<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>
<?php include_once __DIR__ . "/../templades/alertas.php"; ?>
<form action="/crear-cuenta" method="post" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Pedro" value="<?= s($usuario->nombre) ?>">
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" placeholder="Pérez" value="<?= s($usuario->apellido) ?>">
    </div>

    <div class="campo">
        <label for="telefono">Telefono</label>
        <input type="tel" name="telefono" id="telefono" placeholder="Tu Telefono" value="<?= s($usuario->telefono) ?>">
    </div>
    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" name="email" id="email" placeholder="correo@correo.com" value="<?= s($usuario->email) ?>">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu Contraseña">
    </div>

    <!-- <div class="campo">
        <label for="password_confirm">Repite tu Contraseña</label>
        <input type="password" name="password_confirm" id="password_confirm" placeholder="Repite tu Contraseña">
    </div> -->

    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu Contraseña?</a>
</div>