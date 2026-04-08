<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>
<?php 
    // include __DIR__ . "/../templades/barra.php";
    // include __DIR__ . "/../templades/navegacion-admin.php";
    include __DIR__ . "/../templades/alertas.php";
?>
<div>
    <a class="boton-volver" href="/servicios">Volver</a>
</div>
<form class="formulario" method="post">
    <?php include __DIR__ .  '/formulario.php'; ?>
    <input class="boton" type="submit" value="Actualizar Servicio">
</form>