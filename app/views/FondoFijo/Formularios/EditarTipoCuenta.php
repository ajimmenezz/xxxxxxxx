<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" id="idTipoCuenta" value="<?php echo $data['Id']; ?>" />
        <div class="form-group">
            <label class="f-w-600">Tipo de Cuenta:</label>
            <input class="form-control" type="text" id="txtTipoCuenta" value="<?php echo $data['Nombre']; ?>" placeholder="Tipo de Cuenta" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Estatus:</label>
            <select id="listEstatus" class="form-control" style="width: 100% !important;">
                <option value="1" <?php echo ($data['Flag'] == 1) ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo ($data['Flag'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
            </select>        
        </div>
    </div>
</div>
