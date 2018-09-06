<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Editar Kit de Material</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelKitMaterial" class="panel panel-inverse">        
    <div class="panel-heading">  
        <h4 class="panel-title">Editar Kit de Material</h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Editar Kit de Materiales</h4>
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <?php
                    $id = isset($data['Id']) ? $data['Id'] : 0;
                    ?>
                    <input type="hidden" id="idKit" value="<?php echo $id; ?>" />
                    <label class="f-s-13 f-w-600">Nombre del Kit*</label>
                    <input type="text" id="txtKit" class="form-control" value="<?php echo $data['Kit'] ?>" placeholder="Ej. Nodo Taquilla" />                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class=""col-md-6 col-sm-6 col-xs-12">
                 <div id="errorKitNombre"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Definición de Materiales</h4>
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorKitMateriales"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12">
                <div class="input-group">
                    <select id="listMaterial" style="width: 90% !important">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (!empty($material)) {
                            foreach ($material as $key => $value) {
                                echo '<option value="' . $value['Id'] . '">' . $value['Accesorio'] . ' - ' . $value['Material'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <div class="input-group-btn m-l-10">
                        <button id="btnAddMaterialKit" style="margin-top: -4px !important;" class="btn btn-success"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="table-material-kit" class="table table-condensed table-condensed">
                        <thead>
                            <tr>
                                <th style="width: 70% !important;">Material</th>
                                <th style="width: 20% !important;">Cantidad</th>
                                <th style="width: 10% !important;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data['Material'])) {
                                foreach ($data['Material'] as $key => $value) {
                                    echo ''
                                    . '<tr id="tr-' . $value['IdMaterial'] . '">'
                                    . ' <td>' . $value['Nombre'] . '</td>'
                                    . ' <td><input class="form-control txtCantidadesMaterial" min="1" type="number" data-id="' . $value['IdMaterial'] . '" value="' . $value['Cantidad'] . '" placeholder="0" /></td>'
                                    . ' <td><button class="btn btn-danger btnDeleteMaterialKit" data-id="' . $value['IdMaterial'] . '"><i class="fa fa-trash"></i></button></td>'
                                    . '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>                
        </div>
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <button id="btnGuardarKit" class="btn btn-info"><i class="fa fa-save"></i> Guardar Kit</button>
            </div>
        </div>
    </div>        
</div>

