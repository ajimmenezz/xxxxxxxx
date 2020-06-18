<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <input type="hidden" id="idArea" value="<?php echo $data['Id']; ?>" />
        <div class="form-group">
            <label class="f-w-600">Área:</label>
            <input class="form-control" type="text" id="txtArea" value="<?php echo $data['Nombre']; ?>" placeholder="Área de Concepto" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Concepto:</label>
            <select id="listConceptos" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($conceptos) && !empty($conceptos)) {
                    foreach ($conceptos as $key => $value) {
                        $selected = ($value['Id'] == $data['IdConcepto']) ? 'selected' : '';
                        echo '<option value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . ' - ' . $value['Sistema'] . '</option>';
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

