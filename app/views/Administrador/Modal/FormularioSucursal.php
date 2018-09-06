<form class="margin-bottom-0" id="formActualizarSucursales" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloSucursal' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Nombre de la Sucursal *</label>
                <input type="text" class="form-control" id="inputActualizarNombreSucursales" placeholder="Ingresa nombre de la Sucursal" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Nombre Cinemex</label>
                <input type="text" class="form-control" id="inputActualizarCinemexSucursales" placeholder="Ingresa nombre Cinemex" style="width: 100%"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Técnico Responsable *</label>
                <select id="selectActualizarResponsableSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($usuarios as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . ' ' . $item['ApPaterno'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Cliente *</label>
                <select id="selectActualizarClienteSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($clientes as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Región</label>
                <select id="selectActualizarRegionSucursales" class="form-control" style="width: 100%">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($regiones as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucusales">Unidad de Negocio *</label>
                <select id="selectActualizarUnidadNegocioSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($unidadesNegocio as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">País *</label>
                <select id="selectActualizarPaisSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($paises as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Estado *</label>
                <select id="selectActualizarEstadoSucursales" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Delegación o Municipio *</label>
                <select id="selectActualizarMunicipioSucursales" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Colonia *</label>
                <select id="selectActualizarColoniaSucursales" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="control-label">CP</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inputActualizarCPSucursales" placeholder="CP"/>
                    <span class="input-group-addon">
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarBuscarCPSucursales"><i class="fa fa-search"></i></a>
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarLimpiarCPSucursales"><i class="fa fa-eraser"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Calle *</label>
                <input type="text" class="form-control" id="inputActualizarCalleSucursales" placeholder="Ingresa Calle" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-2">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">No. Ext. *</label>
                <input type="text" class="form-control" id="inputActualizarExtSucursales" placeholder="Ingresa No. Ext." style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">No.Int</label>
                <input type="text" class="form-control" id="inputActualizarIntSucursales" placeholder="Ingresa No.Int" style="width: 100%"/>                            
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Télefono(1)</label>
                <input type="text" class="form-control" id="inputActualizarTelefono1Sucursales" placeholder="01-555-5555555" style="width: 100%"/>                            
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Télefono(2)</label>
                <input type="tel" class="form-control" id="inputActualizarTelefono2Sucursales" placeholder="01-555-5555555" style="width: 100%"/>                            
            </div>
        </div>
        <div id='estatus' class="col-md-2">
            <div class="form-group">
                <label for="CatalogoActualizarSucursales">Estatus</label>
                <select id="selectActualizarEstatusSucursales" class="form-control" style="width: 100%">
                    <?php
                    foreach ($flag as $item) {
                        if ($item['Flag'] === '1') {
                            ?>
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                            <?php
                        } else {
                            ?>
                            <option value="1">Activo</option>
                            <option value="0" selected>Inactivo</option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarSucursales"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarSucursales"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>
