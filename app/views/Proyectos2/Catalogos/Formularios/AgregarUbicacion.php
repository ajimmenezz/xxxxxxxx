<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <label class="f-w-600">Ubicación:</label>
            <input class="form-control" type="text" id="txtNuevaUbicacion" value="" placeholder="Ubicación de Área" />
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
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . ' - ' . $value['Concepto'] . '</option>';
                    }
                }
                ?>
            </select>        
        </div>
    </div>
</div>
