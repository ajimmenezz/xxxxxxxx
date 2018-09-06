<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-warning fade in m-b-15 f-s-14 f-w-500">
            <strong>Alerta!</strong>
            Si selecciona otra "Ubicación" todo lo que no haya sido guardado será eliminado.          
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h5>Definición de Nodos de la Ubicación</h5>                    
        <div class="underline m-b-10"></div>
    </div>
</div>
<form id="formNodoUbicacion" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Tipo de Nodo*:</label>
                <select id="listTiposNodo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Selecciona . . .</option>
                    <?php
                    if (isset($tipos) && !empty($tipos)) {
                        foreach ($tipos as $key => $value) {
                            echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="f-w-600 f-s-13">Nombre*:</label>
                <input type="text" id="txtNombreNodo" class="form-control" placeholder="Nombre del Nodo" data-parsley-required="true" />
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">  
            <div class="form-group">
                <label class="f-w-600 f-s-13">¿Qué desea agregar?:</label><br />
                <label class="radio-inline">
                    <input type="radio" name="radioMaterialKit" value="1" checked="">
                    Agregar Material
                </label>
                <label class="radio-inline">
                    <input type="radio" name="radioMaterialKit" value="2">
                    Agregar Kit
                </label>                
            </div>
        </div>
    </div>
    <div id='agregarMaterial'>
        <div class="row">
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Accesorio - Material*:</label>
                    <select id="listMateriales" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($accesorios) && !empty($accesorios)) {
                            foreach ($accesorios as $key => $value) {
                                echo '<option '
                                . 'data-id-accesorio="' . $value['IdAccesorio'] . '" '
                                . 'data-accesorio="' . $value['Accesorio'] . '" '
                                . 'data-id-material="' . $value['IdMaterial'] . '" '
                                . 'data-material="' . $value['Material'] . '" '
                                . 'value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Cantidad*:</label>
                    <div class="input-group">               
                        <input type="number" id="txtCantidad" class="form-control" min="1" data-parsley-required="true" /> 
                        <div class="input-group-btn m-l-10">
                            <a id="btnAddNodoUbicacion" class="btn btn-success"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
    <div id='agregarKit' style="display:none">
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Kit*:</label>
                    <select id="listKits" class="form-control" style="width: 100%">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($kits) && !empty($kits)) {
                            foreach ($kits as $key => $value) {
                                echo "<option value='" . $value['Id'] . "'>" . $value['Kit'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <?php
                if (isset($kits) && !empty($kits)) {
                    foreach ($kits as $key => $value) {
                        ?>
                        <div class="row m-t-5 divMaterialKit" id="divMaterialKit-<?php echo $value['Id']; ?>" style="display: none;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="note note-success">
                                    <?php
                                    foreach ($value['Material'] as $k => $v) {
                                        echo '<label><strong>' . $v['Cantidad'] . ' - </strong> ' . $v['Nombre'] . '</label><br />';
                                        echo '<div class="divHiddenValues-' . $value['Id'] . '">';
                                        echo '<input type="hidden" class="materialKit-idAccesorio" value="' . $v['IdAccesorio'] . '" />';
                                        echo '<input type="hidden" class="materialKit-idMaterial" value="' . $v['IdMaterialSAE'] . '" />';
                                        echo '<input type="hidden" class="materialKit-cantidad" value="' . $v['Cantidad'] . '" />';
                                        echo '<input type="hidden" class="materialKit-accesorio" value="' . $v['Accesorio'] . '" />';
                                        echo '<input type="hidden" class="materialKit-material" value="' . $v['Material'] . '" />';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 text-center">                
                <a class="btn btn-success" id="btnAgregarKit" style="margin-top: 23.5px !important;"><i class="fa fa-plus"> Agregar</i></a>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="errorNodosUbicacion"></div>
    </div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h5>Nodos de la Ubicación</h5>                    
        <div class="underline m-b-10"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="table-nodos-ubicacion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer">
                <thead>
                    <tr>
                        <th class="never">Id</th>
                        <th class="never">IdTipo</th>
                        <th class="never">IdAccesorio</th>
                        <th class="never">IdMaterial</th>                        
                        <th class="all">Tipo Nodo</th>
                        <th class="all">Nodo</th>
                        <th class="all">Accesorio</th>
                        <th class="all">Material</th>
                        <th class="all">Cantidad</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($nodos) && !empty($nodos)) {
                        foreach ($nodos as $key => $value) {
                            echo ''
                            . '<tr>'
                            . ' <td>' . $value['Id'] . '</td>'
                            . ' <td>' . $value['IdTipoNodo'] . '</td>'
                            . ' <td>' . $value['IdAccesorio'] . '</td>'
                            . ' <td>' . $value['IdMaterial'] . '</td>'
                            . ' <td>' . $value['TipoNodo'] . '</td>'
                            . ' <td>' . $value['Nodo'] . '</td>'
                            . ' <td>' . $value['Accesorio'] . '</td>'
                            . ' <td>' . $value['Material'] . '</td>'
                            . ' <td>' . $value['Cantidad'] . '</td>'
                            . '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="errorGuardarNodosUbicacion"></div>
    </div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <a id="btnGuardarNodos" class="btn btn-success"><i class="fa fa-save"></i> Guardar</a>
    </div>
</div>
