<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="f-w-600">Accesorio:</label>
            <select id="listAccesorios" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($accesorios) && !empty($accesorios)) {
                    foreach ($accesorios as $key => $value) {
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . ' - ' . $value['Sistema'] . '</option>';
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
            <label class="f-w-600">Concepto:</label>
            <select id="listMaterial" class="form-control" style="width: 100% !important;">
                <option value="">Seleccionar. . .</option>
                <?php
                if (isset($material) && !empty($material)) {
                    foreach ($material as $key => $value) {
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                    }
                }
                ?>
            </select>        
        </div>
    </div>
</div>
