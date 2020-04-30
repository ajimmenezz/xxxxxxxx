<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend class="pull-left width-full f-s-17">Detalles del servicio.</legend>
        </fieldset>
    </div>
</div>
<div class="row">
    <!--        <div class="col-md-12">
                <pre>
    <?php // var_dump($datos); 
    ?>
                </pre>
            </div>-->
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
if (!in_array($datos['Sucursal'], ['', NULL])) {
?>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <h5 class="f-w-700">Sucursal</h5>
            <pre><?php echo $datos['Sucursal']; ?></pre>
        </div>
    <?php
}

if (!in_array($datos['FechaConclusion'], ['', NULL])) {
    ?>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <h5 class="f-w-700">Fecha de Conclusión</h5>
            <span class="pre"><?php echo $datos['FechaConclusion']; ?></span>
        </div>
    </div>
<?php
} else {
?>
    </div>
<?php
}

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
<div class="row m-t-15"></div>

<?php
echo $diferencias['html'];
?>

<!--
<div class="row m-t-20">
    <div class="col-md-12">
        <fieldset>
            <legend class="pull-left width-full f-s-17">Información del censo.</legend>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered no-wrap table-datatable" style="cursor:pointer; width">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Punto</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>No. Terminal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // foreach ($detalles as $key => $value) {
                    //     echo ''
                    //         . ' <tr>'
                    //         . '     <td>' . $value['Area'] . '</td>'
                    //         . '     <td>' . $value['Punto'] . '</td>'
                    //         . '     <td>' . $value['Modelo'] . '</td>'
                    //         . '     <td>' . $value['Serie'] . '</td>'
                    //         . '     <td>' . $value['Terminal'] . '</td>'
                    //         . '</tr>';
                    // }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>-->