<form id="formMaterialTareaEdit" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">        
            <div class="form-group">
                <label class="f-w-600 f-s-13">Accesorio - Material*:</label>
                <select id="listMaterialesEdit" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Selecciona . . .</option>
                    <?php
                    if (isset($accesorios) && !empty($accesorios)) {
                        foreach ($accesorios as $key => $value) {
                            $selected = ($material['accesorio'] == $value['IdAccesorio'] && $material['material'] == $value['IdMaterial']) ? 'selected' : '';
                            echo '<option '
                            . 'data-id-accesorio="' . $value['IdAccesorio'] . '" '
                            . 'data-accesorio="' . $value['Accesorio'] . '" '
                            . 'data-id-material="' . $value['IdMaterial'] . '" '
                            . 'data-material="' . $value['Material'] . '" '
                            . 'value="' . $value['Id'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
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
                <label class="f-w-600 f-s-13">Cantidad*:</label>
                <input type="number" id="txtCantidadEdit" class="form-control" min="1" value="<?php echo $material['cantidad']; ?>" data-parsley-required="true" /> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a href="javascript:;" id="btnEliminarMaterialTarea" class="btn btn-danger btn-block">Eliminar Material</a>
        </div>
    </div>
</form>