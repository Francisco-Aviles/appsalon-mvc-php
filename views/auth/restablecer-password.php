<h1 class="nombre-pagina">Restablece tu Contraseña</h1>
<p class="descripcion-pagina">Ingresa una nueva contraseña a continuación</p>
<?php include_once __DIR__ . "/../templades/alertas.php"; ?>

<?php if($error) return; ?>
<form method="post" class="formulario">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu nueva contraseña">
    </div>

    <input type="submit" value="Restablecer Contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea Una</a>
</div>