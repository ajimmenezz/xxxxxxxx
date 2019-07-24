<div class="row">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Detalles del servicio.</legend>
        </fieldset>  
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Id del Servicio</h5>
        <pre><?php echo $datos['Id']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Tipo</h5>
        <pre><?php echo $datos['Tipo']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Estatus</h5>
        <pre><?php echo $datos['Estatus']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Fecha de Creación</h5>
        <pre><?php echo $datos['FechaCreacion']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Fecha de Inicio</h5>
        <pre><?php echo $datos['FechaInicio']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Atiende</h5>
        <pre><?php echo $datos['Atiende']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h5 class="f-w-700">Servicio</h5>
        <pre><?php echo $datos['Servicio']; ?></pre>
    </div>
</div>
<?php
if (!in_array($datos['Firma'], ['', NULL])) {
    ?>
    <div class="row">
        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
            <h5 class="f-w-700 text-center">Firma de Cierre</h5>
            <img style="max-height: 120px;" src="<?php echo $datos['Firma']; ?>" alt="Firma de Cierre" />
            <h6 class="f-w-700 text-center"><?php echo $datos['NombreFirma']; ?></h6>            
            <h6 class="f-w-700 text-center"><?php echo $datos['FechaFirma']; ?></h6>            
        </div>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Tipo de Tráfico</h5>
        <pre><?php echo $datos['TipoTrafico']; ?></pre>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Encargado de Ruta</h5>
        <pre><?php echo $datos['Encargado']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Origen</h5>
        <pre><?php echo $datos['Origen']; ?></pre>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5 class="f-w-700">Destino</h5>
        <pre><?php echo $datos['Destino']; ?></pre>
    </div>
</div>
<div class="row m-t-20">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Detalle de Items.</legend>
        </fieldset>  
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered no-wrap table-datatable" style="cursor:pointer; width">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Serie</th>
                        <th>Cantidad</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($items as $key => $value) {
                        echo ''
                        . ' <tr>'
                        . '     <td>' . $value['Equipo'] . '</td>'
                        . '     <td>' . $value['Serie'] . '</td>'
                        . '     <td>' . $value['Cantidad'] . '</td>'
                        . ' </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row m-t-20">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Documentación del servicio.</legend>
        </fieldset>  
    </div>
</div>
<?php
if ($datos['IdTipoTrafico'] == 1) {
    if ($envio[0]['TipoEnvio'] !== '') {
        ?>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">

            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <pre>Aún no hay documentación de este servicio.</pre>
        </div>
    </div>
    <?php
}
echo $htmlDocumentacion;
?>

