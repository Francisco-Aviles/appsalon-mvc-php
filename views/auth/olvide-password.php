<h1 class="nombre-pagina">Olvide mi Contraseña</h1>
<p class="descripcion-pagina">Restablece tu contraseña ingresando tu correo</p>

<?php include __DIR__ . "/../templades/alertas.php"; ?>

<form action="/olvide" method="post" class="formulario">
    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" name="email" id="email" placeholder="Tu Correo">
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear Una</a>
</div>