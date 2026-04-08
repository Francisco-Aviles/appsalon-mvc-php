<?php
    $ruta = $_SERVER['REQUEST_URI'] ?? '';
?>

<div class="barra-servicios">
    <a class="boton <?php echo $ruta == '/admin' ? 'actual' : '';?>" href="/admin">Ver Citas</a>
    <a class="boton <?php echo $ruta == '/servicios' ? 'actual' : '';?>" href="/servicios">Ver Servicios</a>
    <a class="boton <?php echo $ruta == '/servicios/crear' ? 'actual' : '';?>" href="/servicios/crear">Nuevo Servicio</a>
</div>