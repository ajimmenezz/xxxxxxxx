<div class="row m-t-20">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="shadow-box p-25">
            <h5>Agregar Áreas y Puntos</h5>
            <div class="underline m-b-20"></div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">                
                    <div class="control-group">
                        <label class="f-w-600">Área de Atencion *</label>
                        <select class="form-control" id="listAreasAtencion">
                            <option value="">Seleccionar . . .</option>
                            <?php
                            if (isset($areasCliente) && count($areasCliente) > 0) {
                                foreach ($areasCliente as $key => $value) {
                                    echo '<option value="' . $value['Id'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 m-t-10">                
                    <div class="control-group">
                        <label class="f-w-600">Puntos *</label>
                        <input type="number" class="form-control" id="txtCantidadPuntos" value="1"/>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 m-t-10 text-center">
                    <div class="control-group">
                        <label style="color: transparent !important;" class="f-w-600">-</label>
                        <a id="btnAgregarAreaPuntos" class="btn btn-block btn-success f-w-600">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="row m-t-10">
                <div class="col-md-12">
                    <div class="divError"></div>
                </div>
            </div>
        </div>
        <div class="m-t-20">
            <a class="f-s-14 f-w-600 btn btn-small btn-info btn-block btnGuardarCambiosAreasPuntos">GUARDAR CAMBIOS EN ÁREAS Y PUNTOS</a>        
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 m-t-20">    
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Área de Atención</th>
                    <th style="width: 100px !important; max-width: 100px !important;">Puntos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($areasPuntos) && count($areasPuntos) > 0) {
                    foreach ($areasPuntos as $key => $value) {
                        echo ''
                        . '<tr>'
                        . ' <td>' . $value['Area'] . '</td>'
                        . ' <td>'
                        . '     <input type="number" data-id="' . $value['Id'] . '" class="form-control cantidadPuntosAreas" value="' . $value['Puntos'] . '" />'
                        . ' </td>'
                        . '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="m-t-20">
            <a class="f-s-14 f-w-600 btn btn-small btn-info btn-block btnGuardarCambiosAreasPuntos">GUARDAR CAMBIOS EN ÁREAS Y PUNTOS</a>        
        </div>
    </div>
</div>