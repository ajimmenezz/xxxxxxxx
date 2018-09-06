<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <input type="hidden" id="idUbicacion" value="<?php echo $data['Id']; ?>" />
        <div class="form-group">
            <label class="f-w-600">Ubicación:</label>
            <input class="form-control" type="text" id="txtUbicacion" value="<?php echo $data['Nombre']; ?>" placeholder="Ubicación de Área" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Área:</label>
            <select id="listAreas" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($areas) && !empty($areas)) {
                    foreach ($areas as $key => $value) {
                        $selected = ($value['Id'] == $data['IdArea']) ? 'selected' : '';
                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . ' - ' . $value['Concepto'] . '</option>';
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

