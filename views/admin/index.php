<h1 class="nombre-pagina">Panel de administración</h1>
<?php 
    include __DIR__ . "/../templades/barra.php";
    include __DIR__ . "/../templades/navegacion-admin.php";
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" 
                value="<?= $fecha ?>"
            >
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0){
        echo '<h2>NO HAY CITAS PARA ESTA FECHA</h2>';
    }
?>

<div class="citas-admin">
    <ul class="citas">
        <?php
            $citaId = 0;
            foreach($citas as $key => $cita): ?>

        <?php    
                if($citaId !== $cita->id):
                    $total = 0;
        ?>
            <li>
                    <p>Id: <span><?= $cita->id; ?> </span></p>
                    <p>Hora: <span><?= $cita->hora; ?></span></p>
                    <p>Cliente: <span><?= $cita->cliente; ?></span></p>
                    <p>Correo: <span><?= $cita->email; ?></span></p>
                    <p>Telefono: <span><?= $cita->telefono; ?></span></p>
    
                    <h3>Servicios</h3>
                
            <?php 
                endif;
                $citaId = $cita->id;
                $total += $cita->precio;
            ?>
            </li>
            <p class="servicio"><?= $cita->servicio . ' ' . $cita->precio; ?></p>
            <?php 
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;
                if(esUltimo($actual, $proximo)){


            ?>
                <p class="total">Total: <span>$ <?= $total; ?></span> </p>
                <form action="/api/eliminar" method="post">
                    <input type="hidden" name="id" id="id" value="<?= $cita->id ?>">
                    <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
        <?php   }
             endforeach; 
        ?>   
    </ul>
</div>

<?php
    $script = "
        <script src='build/js/buscador.js'></script>
    ";
?>