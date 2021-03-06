<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4>Agregar Material a la Tarea</h4>                                                
    </div>
</div>
<div class="row">
    <div class="underline m-b-10"></div>
</div> 
<form id="formNodoUbicacion" data-parsley-validate="true">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">  
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
                            <a id="btnAddMaterialTarea" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
        <div id="errorMaterialTarea"></div>
    </div>
</div>
<div class="row m-t-10">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4>Materiales de Tarea</h4>                                                
    </div>
</div>
<div class="row">
    <div class="underline m-b-10"></div>
</div> 
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="table-material-tarea" class="table table-striped table-bordered no-wrap" style="cursor:pointer">
                <thead>
                    <tr>
                        <th class="never">Id</th>
                        <th class="never">IdAccesorio</th>
                        <th class="never">IdMaterial</th>
                        <th class="all">Accesorio</th>
                        <th class="all">Material</th>
                        <th class="all">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($materiales) && !empty($materiales)) {
                        foreach ($materiales as $key => $value) {
                            echo ''
                            . '<tr>'
                            . '<td>' . $value['Id'] . '</td>'
                            . '<td>' . $value['IdAccesorio'] . '</td>'
                            . '<td>' . $value['IdMaterial'] . '</td>'
                            . '<td>' . $value['Accesorio'] . '</td>'
                            . '<td>' . $value['Material'] . '</td>'
                            . '<td>' . $value['Cantidad'] . '</td>'
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
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <a class="btn btn-success" id="btnGuardarMaterialTarea"><i class="fa fa-save"> </i>Guardar Cambios</a>
    </div>
</div>