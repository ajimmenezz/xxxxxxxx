<?php
if (isset($generalsService) && !empty($generalsService) && isset($diagnosticService) && !empty($diagnosticService)) {
?>
    <input type="hidden" id="serviceId" value="<?php echo $generalsService[0]['IdServicio']; ?>" />
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h4>Movimiento y validación</h4>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="note note-warning">
                <p>Todos los campos <span class="f-w-600">marcados con * son obligatorios</span>. Revise su información antes de guardar.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
            <label class="f-s-13 f-w-600">¿Quién valida el retiro o la solicitud? *</label>
            <select id="validatorsList" class="form-control" style="width:100%">
                <option value="">Selecciona . . .</option>
                <?php
                if (isset($validators) && !empty($validators)) {
                    foreach ($validators as $k => $v) {                        
                        echo '<option value="' . $v['Id'] . '">' . $v['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 form-group">
            <label class="f-s-13 f-w-600">Movimiento a realizar *</label>
            <select id="movementsList" class="form-control" style="width:100%">
                <option value="">Selecciona . . .</option>
                <option value="1">Retiro y envío del equipo (foraneos)</option>
                <option value="2">Retiro y entrega del equipo (locales)</option>
                <option value="3">Solicitud de equipo o refacción</option>
            </select>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 form-group hidden" id="warehousesDiv">
            <label class="f-s-13 f-w-600">¿A quién solicita el equipo o refacción? *</label>
            <select id="warehousesList" class="form-control" style="width:100%">
                <option value="">Selecciona . . .</option>
                <option value="1">Solicitud a almacén (Siccob)</option>
                <option value="2">Solicitud al Cliente</option>
                <option value="3">Solicitud a Multimedia</option>
            </select>
        </div>
    </div>
    <div id="backupDeviceDiv" class="hidden">
        <div class="row m-t-5">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Equipo de respaldo</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
        </div>
        <?php
        if (isset($posibleBackupDevices) && count($posibleBackupDevices) > 0) {
            if ($diagnosticService[0]['IdTipoDiagnostico'] == 2) {
                echo '
                <input type="hidden" id="isBadUse" value="1" />
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="note note-danger">
                            <p class="f-w-600 f-s-12">
                                En los incidentes diagnosticados como impericia, NO es necesario dejar un equipo de respaldo. 
                                <br />Se recomienda que se comunique con el supervisor correspondiente para la autorización del respaldo.
                            </p>
                        </div>
                    </div>
                </div>
                ';
            }
        ?>
            <input type="hidden" id="isBadUse" value="0" />
            <div class="row">
                <div class="col-md-12 col-md-12 col-xs-12 table-responsive">
                    <table id="technicianInventoryTable" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">IdEquipo</th>
                                <th class="all">Equipo</th>
                                <th class="never">Cantidad</th>
                                <th class="never">IdInventario</th>
                                <th class="all">Serie</th>
                                <th class="all"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($posibleBackupDevices as $key => $value) {
                                //$checked = ($value['Usado'] <= 0) ? 'fa-square-o' : 'fa-check-square-o';
                                $checked = 'fa-square-o';
                                echo ''
                                    . '<tr>'
                                    . ' <td>' . $value['IdProducto'] . '</td>'
                                    . ' <td>' . $value['Producto'] . '</td>'
                                    . ' <td>' . $value['Cantidad'] . '</td>'
                                    . ' <td>' . $value['IdInventario'] . '</td>'
                                    . ' <td>' . $value['Serie'] . '</td>'
                                    . ' <td class="text-center"><i data-id="' . $value['IdInventario'] . '" data-id-producto="' . $value['IdProducto'] . '" data-serie="' . $value['Serie'] . '" class="checkEquipoStock fa fa-2x ' . $checked . '"></i></td>'
                                    . '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        } else {
        ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="note note-danger">
                        <p class="f-w-600 f-s-12">No cuentas con equipos de la misma línea para dejar un respaldo. Si crees que esto es un error, contacta al administrador.</p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="m-t-5">
        <div class="col-md-12 col-sm-12 col-xs-12 underline">
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12 col-sm-12 col-xs-12" id="errorMessage">

        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a id="saveDeviceTransferButton" class="btn btn-success pull-right f-s-13 f-w-600">Guardar</a>
        </div>
    </div>

<?php
} else {
?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="note note-warning">
                <p class="f-s-13 f-w-600">Es necesario que el servicio tenga documentada la sección de "Información General" y "Diagnóstico del Equipos" </p>
            </div>
        </div>
    </div>
<?php
}
?>