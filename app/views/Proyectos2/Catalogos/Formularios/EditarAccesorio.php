<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <input type="hidden" id="idAccesorio" value="<?php echo $data['Id']; ?>" />
        <div class="form-group">
            <label class="f-w-600">Accesorio:</label>
            <input class="form-control" type="text" id="txtAccesorio" value="<?php echo $data['Nombre']; ?>" placeholder="Accesorio de Sistema" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Sistema:</label>
            <select id="listSistemas" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($sistemas) && !empty($sistemas)) {
                    foreach ($sistemas as $key => $value) {
                        $selected = ($value['Id'] == $data['IdSistema']) ? 'selected' : '';
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
            <label class="f-w-600">Estatus:</label>
            <select id="listEstatus" class="form-control" style="width: 100% !important;">
                <option value="1" <?php echo ($data['Flag'] == 1) ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo ($data['Flag'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
            </select>        
        </div>
    </div>
</div>

