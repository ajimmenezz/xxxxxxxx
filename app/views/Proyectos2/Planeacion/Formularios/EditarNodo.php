<form id="formNodoUbicacionEdit" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">        
            <div class="form-group">
                <label class="f-w-600 f-s-13">Tipo de Nodo*:</label>
                <select id="listTiposNodoEdit" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Selecciona . . .</option>
                    <?php
                    if (isset($tipos) && !empty($tipos)) {
                        foreach ($tipos as $key => $value) {
                            $selected = ($nodo['tipo'] == $value['Id']) ? 'selected' : '';
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
                <label class="f-w-600 f-s-13">Nombre*:</label>
                <input type="text" id="txtNombreNodoEdit" class="form-control" value="<?php echo $nodo['nombre']; ?>" placeholder="Nombre del Nodo" data-parsley-required="true" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">        
            <div class="form-group">
                <label class="f-w-600 f-s-13">Accesorio - Material*:</label>
                <select id="listMaterialesEdit" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Selecciona . . .</option>
                    <?php
                    if (isset($accesorios) && !empty($accesorios)) {
                        foreach ($accesorios as $key => $value) {
                            $selected = ($nodo['accesorio'] == $value['IdAccesorio'] && $nodo['material'] == $value['IdMaterial']) ? 'selected' : '';
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
                <input type="number" id="txtCantidadEdit" class="form-control" min="1" value="<?php echo $nodo['cantidad']; ?>" data-parsley-required="true" /> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a href="javascript:;" id="btnEliminarNodo" class="btn btn-danger btn-block">Eliminar Nodo</a>
        </div>
    </div>
</form>