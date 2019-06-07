<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h5>Registrar depósito para <strong><?php echo $usuario['nombre']; ?></strong></h5>
        <input type="hidden" id="hiddenUserId" value="<?php echo $usuario['id']; ?>" />
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success f-w-600">
            <i class="fa fa-reply"></i> Regresar
        </label>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 m-t-15">
        <div class="underline m-b-10"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Tipo de Cuenta *</label>
            <select id="listTiposCuenta" class="form-control" style="width:100%">
                <option value="">Seleccionar . . . </option>
                <?php
                foreach ($tiposCuenta as $key => $value) {
                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Máximo Autorizado</label>
            <label id="montoMaximoAutorizado" class="form-control text-center">$ </label>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Saldo Actual</label>
            <label id="saldoActual" class="form-control text-center">$</label>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Monto Sugerido</label>
            <label id="montoSugerido" class="form-control text-center">$</label>
        </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Depositar *</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" id="txtMontoDepositar" class="form-control" placeholder="0.00" disabled />
                <div class="input-group-btn">
                    <button type="button" id="btnGuardarDeposito" class="btn btn-success"><i class="fa fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600 f-s-13">Evidencias de Depósito: *</label>
            <input id="fotosDeposito" name="fotosDeposito[]" type="file" multiple="" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4>Registro de depósitos</h4>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 m-t-15">
        <div class="underline m-b-10"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="tabla-depositos" class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="none">Id</th>
                        <th class="all">Tipo Cuenta</th>
                        <th class="all">Fecha</th>
                        <th class="all">Saldo Anterior</th>
                        <th class="all">Depósito</th>
                        <th class="all">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($depositos) && !empty($depositos)) {
                        foreach ($depositos as $key => $value) {
                            echo ""
                                . "<tr>"
                                . " <td>" . $value['Id'] . "</td>"
                                . " <td>" . $value['TipoCuenta'] . "</td>"
                                . " <td>" . $value['Fecha'] . "</td>"
                                . " <td>$ " . number_format((float)$value['SaldoPrevio'], 2) . "</td>"
                                . " <td>$ " . number_format((float)$value['Monto'], 2) . "</td>"
                                . " <td>$ " . number_format((float)$value['SaldoNuevo'], 2) . "</td>"                                
                                . "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>