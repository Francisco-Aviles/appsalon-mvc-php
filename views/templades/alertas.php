
<?php
    foreach($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):
?>
        <div class="alerta <?= $key; ?>">
            <p><?= $mensaje; ?></p>
        </div>
<?php
        endforeach;
    endforeach; ?>