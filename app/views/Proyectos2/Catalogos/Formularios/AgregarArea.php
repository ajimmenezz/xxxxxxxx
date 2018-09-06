<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">
            <label class="f-w-600">Área:</label>
            <input class="form-control" type="text" id="txtNuevaArea" value="" placeholder="Área de Concepto" />
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
                        echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . ' - ' . $value['Sistema'] . '</option>';
                    }
                }
                ?>
            </select>        
        </div>
    </div>
</div>
