<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>
<?php 
    include __DIR__ . "/../templades/barra.php";
    include __DIR__ . "/../templades/navegacion-admin.php";
?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?= $servicio->nombre ?></span></p>
            <p>Precio: <span>$ <?= $servicio->precio ?></span></p>
        </li>
        <div class="acciones">
            <a class="boton" href="/servicios/actualizar?id=<?= $servicio->id; ?>">Actualizar</a>
            <form action="/servicios/eliminar" method="post">
                <input type="hidden" name="id" value="<?= $servicio->id; ?>">
                <input class="boton-eliminar" type="submit" value="Eliminar">
            </form>
        </div>
    <?php endforeach; ?>
</ul>