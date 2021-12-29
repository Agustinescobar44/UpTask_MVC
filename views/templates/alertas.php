<?php
    foreach($alertas as $tipo => $alertas):
        foreach($alertas as $mensaje):
?>
            <div class="alerta <?php echo $tipo ?>">
                <?php echo $mensaje ?>
            </div>
<?php 
        endforeach;
    endforeach;
?>