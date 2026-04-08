<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para añadir un nuevo servicio</p>
<?php 
    // include __DIR__ . "/../templades/barra.php";
    // include __DIR__ . "/../templades/navegacion-admin.php";
    include __DIR__ . "/../templades/alertas.php";
?>
<div>
    <a class="boton-volver" href="/servicios">Volver</a>
</div>
<form action="/servicios/crear" class="formulario" method="post">
    <?php include __DIR__ .  '/formulario.php'; ?>
    <input class="boton" type="submit" value="Crear Servicio">
</form>