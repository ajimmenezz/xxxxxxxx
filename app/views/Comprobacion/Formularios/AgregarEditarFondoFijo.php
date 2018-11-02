<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Usuario: *</label>
            <select id="listUsuariosFF" class="form-control" style="width: 100% !important">
                <option value="">Seleccionar . . .</option>
                <?php
                if (isset($usuarios) && count($usuarios) > 0) {
                    foreach ($usuarios as $key => $value) {
                        $selected = (isset($generales['IdUsuario']) && $generales['IdUsuario'] == $value['Id']) ? 'selected="selected"' : '';
                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                    }
                }
                ?>   
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-s-13 f-w-600">Monto del Fondo Fijo: *</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" id="txtMontoFF" class="form-control" placeholder="5000.00" value="<?php echo (isset($generales['Monto'])) ? $generales['Monto'] : ''; ?>" data-parsley-type="number" required>
            </div>    
        </div>
    </div>
</div>

<?php
if (isset($generales['IdUsuario'])) {
    if ($generales['Estatus'] == 'Activo') {
        ?>
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <a id="btnInhabilitarFF" data-id="<?php echo $generales['Id']; ?>" class="btn btn-danger btn-block">Inhabilitar Fondo Fijo</a>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <a id="btnHabilitarFF" data-id="<?php echo $generales['Id']; ?>" class="btn btn-success btn-block">Habilitar Fondo Fijo</a>
            </div>
        </div>
        <?php
    }
}
?>

