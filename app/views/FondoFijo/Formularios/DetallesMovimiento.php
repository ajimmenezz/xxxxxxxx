<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Usuario que Registra</label>
            <label class="form-control"><?php echo $generales['Registra']; ?></label>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Tipo de Movimiento</label>
            <label class="form-control"><?php echo $generales['TipoMovimiento']; ?></label>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Concepto</label>
            <label class="form-control"><?php echo $generales['Nombre']; ?></label>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Monto</label>
            <label class="form-control f-s-14 f-w-600">$<?php echo $generales['Monto']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Saldo Anterior</label>
            <label class="form-control f-s-14 f-w-600">$<?php echo $generales['SaldoPrevio']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Saldo</label>
            <label class="form-control f-s-14 f-w-600">$<?php echo $generales['SaldoNuevo']; ?></label>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Estatus</label>
            <label class="form-control"><?php echo $generales['Estatus']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Fecha de Movimiento</label>
            <label class="form-control"><?php echo $generales['FechaMovimiento']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Fecha de Autorización</label>
            <label class="form-control"><?php echo $generales['FechaAutorizacion']; ?></label>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Autorizado por</label>
            <label class="form-control"><?php echo ($generales['EnPresupuesto'] == "SI" && !in_array($generales['IdTipoMovimiento'], [5, '5'])) ? 'PRESUPUESTO' : $generales['Autoriza']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">¿Extraordinario?</label>
            <label class="form-control"><?php echo $generales['Extraordinario']; ?></label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">¿Dentro de presupuesto?</label>
            <label class="form-control"><?php echo $generales['EnPresupuesto']; ?></label>
        </div>
    </div>
    <?php
    if ($generales['Ticket'] !== '' && !is_null($generales['Ticket'])) {
        ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Ticket</label>
                <label class="form-control"><?php echo $generales['Ticket']; ?></label>
            </div>
        </div>
    <?php
}

if ($generales['Origen'] !== "" && !is_null($generales['Origen'])) {
    ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Origen</label>
                <label class="form-control"><?php echo $generales['Origen']; ?></label>
            </div>
        </div>
    <?php
}

if ($generales['Destino'] != "" && !is_null($generales['Destino'])) {
    ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Destino</label>
                <label class="form-control"><?php echo $generales['Destino']; ?></label>
            </div>
        </div>
    <?php
}
if ($generales['Observaciones'] != '' && !is_null($generales['Observaciones'])) {
    ?>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Observaciones</label>
                <label class="form-control"><?php echo $generales['Observaciones']; ?></label>
            </div>
        </div>

    <?php
}
?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Tipo de Comprobante</label>
            <label class="form-control"><?php echo $generales['TipoComprobante']; ?></label>
        </div>
    </div>

    <?php if ($generales['XML'] != '' && !is_null($generales['XML'])) {
        ?>
        <div class="col-md-6 col-sm-6 col-xs-12 text-center">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Archivo XML</label>
                <div class="thumbnail-pic m-l-5 m-r-5 m-b-5 p-5">
                    <?php echo '<a class="imagenesSolicitud" target="_blank" href="' . $generales['XML'] . '"><img src="/assets/img/Iconos/xml_icon.png" class="img-responsive img-thumbnail" style="max-height:130px !important;" alt="XML" /></a>'; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-center">
            <div class="form-group">
                <label class="f-s-13 f-w-600">Archivo PDF</label>
                <div class="thumbnail-pic m-l-5 m-r-5 m-b-5 p-5">
                    <?php echo '<a class="imagenesSolicitud" target="_blank" href="' . $generales['PDF'] . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:130px !important;" alt="XML" /></a>'; ?>
                </div>
            </div>
        </div>
    <?php
}

if ($generales['Archivos'] != '' && !is_null($generales['Archivos'])) {
    $archivos = explode(",", $generales['Archivos']);
    ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php
            foreach ($archivos as $key => $value) {
                echo '<div class="thumbnail-pic m-l-5 m-r-5 m-b-5 p-5">';
                $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                switch ($ext) {
                    case 'png':
                    case 'jpeg':
                    case 'jpg':
                    case 'gif':
                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:100px !important;" alt="Evidencia" /></a>';
                        break;
                    case 'xls':
                    case 'xlsx':
                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:100px !important;" alt="Evidencia" /></a>';
                        break;
                    case 'doc':
                    case 'docx':
                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:100px !important;" alt="Evidencia" /></a>';
                        break;
                    case 'pdf':
                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:100px !important;" alt="Evidencia" /></a>';
                        break;
                    default:
                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:100px !important;" alt="Evidencia" /></a>';
                        break;
                }
                echo '</div>';
            }
            ?>
        </div>
    <?php
}
?>
</div>
<?php
if ($generales['IdTipoMovimiento'] == 7 && !in_array($generales['IdEstatus'], [6, 10, '6', '10']) && (!isset($rolAutoriza) || $rolAutoriza == 0)) {
    ?>
    <div class="row m-t-10">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a id="btnCancelarMovimiento" class="btn btn-danger btn-block">Cancelar Movimiento</a>
        </div>
    </div>

<?php
}

if (isset($rolAutoriza) && $rolAutoriza == 1) {
    ?>
    <div class="row m-t-20">
        <div class="col-md-12 underline"></div>
    </div>
    <div class="row m-t-20">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-15">Observaciones:</label>
                <textarea class="form-control" placeholder="Escribe aqui las observaciones de la autorización o el rechazo" id="txtObservacionesAutorizacion"></textarea>
                <div id="errorAutorizacion"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-5">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a id="btnAutorizarMovimiento" class="btn btn-success btn-block f-s-15 f-w-600">Autorizar Movimiento</a>
        </div>
    </div>
    <div class="row m-t-20">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a id="btnRechazarMovimiento" class="btn btn-warning btn-block f-s-15 f-w-600">Rechazar Movimiento</a>
        </div>
    </div>
    <!--<div class="row m-t-20">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a id="btnRechazarMovimientoCobrable" class="btn btn-danger btn-block f-s-15 f-w-600">Rechazar Movimiento (Con cobro)</a>
                </div>
            </div>-->
<?php
}
?>