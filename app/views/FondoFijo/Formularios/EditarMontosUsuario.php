<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h4>Montos para <?php echo $usuario['nombre']; ?></h4>
        <input type="hidden" id="hiddenUserId" value="<?php echo $usuario['id']; ?>" />
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
        <label id="btnGuardarMontos" class="btn btn-info f-w-600">
            <i class="fa fa-save"></i> Guardar
        </label>
        <label id="btnRegresar" class="btn btn-success f-w-600">
            <i class="fa fa-reply"></i> Regresar
        </label>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="underline m-b-10"></div>
    </div>
</div>
<div class="row">
    <?php
    foreach ($tiposCuenta as $key => $value) {
        $monto = 0;
        foreach ($montos as $mk => $mv) {
            if ($mv['IdTipoCuenta'] == $value['Id']) {
                $monto = $mv['Monto'];
            }
        }

        echo '
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">' . $value['Nombre'] . '</label>
                <input class="form-control txtMonto" type="number" value="' . $monto . '" data-id="' . $value['Id'] . '" placeholder="Monto máx" />
            </div>
        </div>
        ';
    }
    ?>
</div>