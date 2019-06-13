<?php
$title = "Agregar";
if (isset($generales['Id'])) {
    $title = "Editar";
}
?>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h4><?php echo $title; ?> Concepto</h4>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <?php
        if (isset($generales['Id'])) {
            if ($generales['Estatus'] == 'Activo') {
                ?>
                <label data-id="<?php echo $generales['Id']; ?>" id="btnInhabilitarConcepto" class="btn btn-danger">Inhabilitar</label>
            <?php
        } else {
            ?>
                <label data-id="<?php echo $generales['Id']; ?>" id="btnHabilitarConcepto" class="btn btn-info">Habilitar</label>
            <?php
        }
    }
    ?>
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-5"></div>
    </div>
</div>
<form id="form-agregar-editar-concepto" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Concepto: *</label>
                <input type="text" id="txtConcepto" class="form-control" placeholder="Nuevo Concepto" data-parsley-required="true" value="<?php echo (isset($generales['Nombre'])) ? $generales['Nombre'] : ''; ?>" />
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Monto Máximo: *</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" id="txtMonto" class="form-control" placeholder="600.00" value="<?php echo (isset($generales['Monto'])) ? $generales['Monto'] : ''; ?>" data-parsley-type="number" required>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-6">
            <div class="form-group">
                <label class="f-w-600 f-s-13">¿Se considera extraordinario?: *</label>
                <div>
                    <label class="radio-inline">
                        <?php
                        $checked1 = (isset($generales['Extraordinario']) && $generales['Extraordinario'] == 'Si') ? 'checked=""' : '';
                        ?>
                        <input type="radio" name="radioExtraordinario" value="1" <?php echo $checked1; ?> data-parsley-required="false">
                        Si
                    </label>
                    <label class="radio-inline">
                        <?php
                        $checked2 = 'checked=""';
                        if (isset($generales['Extraordinario'])) {
                            $checked2 = ($generales['Extraordinario'] == 'No') ? 'checked=""' : '';
                        }

                        if ($checked1 != '') {
                            $checked2 = '';
                        }
                        ?>
                        <input type="radio" name="radioExtraordinario" value="0" <?php echo $checked2; ?> data-parsley-required="false">
                        No
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Tipos de comprobante: *</label>
                <div>
                    <?php
                    $tiposComprobantes = (isset($generales['TiposComprobante']) && $generales['TiposComprobante'] != '') ? explode(",", $generales['TiposComprobante']) : [];
                    if (isset($tiposComprobante) && count($tiposComprobante) > 0) {
                        foreach ($tiposComprobante as $key => $value) {
                            $checked = in_array($value['Id'], $tiposComprobantes) ? 'checked=""' : '';
                            echo ''
                                . '<label class="checkbox-inline">'
                                . ' <input type="checkbox" class="checkTiposComprobante" name="tiposComprobante[]" id="tiposComprobante' . $value['Id'] . '" value="' . $value['Id'] . '"  data-parsley-mincheck="1" required ' . $checked . '>'
                                . ' ' . $value['Nombre']
                                . '</label>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Tipos de Cuenta: *</label>
                <div>
                    <?php
                    $tiposCuentas = (isset($generales['TiposCuenta']) && $generales['TiposCuenta'] != '') ? explode(",", $generales['TiposCuenta']) : [];
                    if (isset($tiposCuenta) && count($tiposCuenta) > 0) {
                        foreach ($tiposCuenta as $key => $value) {
                            $checked = in_array($value['Id'], $tiposCuentas) ? 'checked=""' : '';
                            echo ''
                                . '<label class="checkbox-inline">'
                                . ' <input type="checkbox" class="checkTiposCuenta" name="tiposCuenta[]" id="tiposCuenta' . $value['Id'] . '" value="' . $value['Id'] . '"  data-parsley-mincheck="1" required ' . $checked . '>'
                                . ' ' . $value['Nombre']
                                . '</label>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row m-t-15">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <h4>Alternativas del concepto</h4>
            </div>
            <!--Empezando error-->
            <div class="col-md-12">
                <div id="errorMessageAlternativas"></div>
            </div>
            <!--Finalizando Error-->
            <div class="col-md-12">
                <div class="underline m-b-15 m-t-5"></div>
            </div>
            <!--Finalizando Separador-->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Usuario:</label>
            <select id="listUsuariosAlternativas" class="form-control" style="width: 100% !important">
                <option value="">Seleccionar . . .</option>
                <?php
                if (isset($usuarios) && count($usuarios) > 0) {
                    foreach ($usuarios as $key => $value) {
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Sucursal:</label>
            <select id="listSucursalesAlternativas" class="form-control" style="width: 100% !important">
                <option value="">Seleccionar . . .</option>
                <?php
                if (isset($sucursales) && count($sucursales) > 0) {
                    foreach ($sucursales as $key => $value) {
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Monto: *</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" id="txtMontoAlternativo" class="form-control" placeholder="600.00" data-parsley-type="number" required>
                <div class="input-group-btn">
                    <button type="button" id="btnAgregarAlternativa" class="btn btn-success"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive m-t-25">
            <table id="table-alternativas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="never">Id</th>
                        <th class="never">IdUsuario</th>
                        <th class="never">IdSucursal</th>
                        <th class="never">Monto</th>
                        <th class="all">Usuario</th>
                        <th class="all">Sucursal</th>
                        <th class="all">Monto Máximo</th>
                        <th class="all"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($alternativas) && count($alternativas) > 0) {
                        foreach ($alternativas as $key => $value) {
                            echo ''
                                . '<tr>'
                                . '<td>' . $value['Id'] . '</td>'
                                . '<td>' . $value['IdUsuario'] . '</td>'
                                . '<td>' . $value['IdSucursal'] . '</td>'
                                . '<td>' . $value['Monto'] . '</td>'
                                . '<td>' . $value['Usuario'] . '</td>'
                                . '<td>' . $value['Sucursal'] . '</td>'
                                . '<td>$' . $value['Monto'] . '</td>'
                                . '<td class="text-center"><span role="button" class="label label-danger text-white btnDeleteAlternativas"><i class="fa fa-trash"></i></span></td>'
                                . '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row m-t-20">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <a id="btnGuardarConcepto" class="btn btn-success"><i class="fa fa-save"></i> Guardar Concepto</a>
    </div>
</div>