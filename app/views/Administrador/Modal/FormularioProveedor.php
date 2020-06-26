<form class="margin-bottom-0" id="formActualizarProveedores" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloProveedor' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Nombre de la Proveedor *</label>
                <input type="text" class="form-control" id="inputActualizarNombreProveedores" placeholder="Ingresa nombre de la Proveedor" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Razón Social *</label>
                <input type="text" class="form-control" id="inputActualizarRazonProveedores" placeholder="Ingresa nombre Cinemex" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">País *</label>
                <select id="selectActualizarPaisProveedores" class="form-control" style="width: 100%" data-parsley-required="true">
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
                <label for="catalogoActualizarProveedores">Estado *</label>
                <select id="selectActualizarEstadoProveedores" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Delegación o Municipio *</label>
                <select id="selectActualizarMunicipioProveedores" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10">         
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Colonia *</label>
                <select id="selectActualizarColoniaProveedores" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="control-label">CP *</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inputActualizarCPProveedores" placeholder="CP"/>
                    <span class="input-group-addon">
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarBuscarCPProveedores"><i class="fa fa-search"></i></a>
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarLimpiarCPProveedores"><i class="fa fa-eraser"></i></a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-8">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Calle *</label>
                <input type="text" class="form-control" id="inputActualizarCalleProveedores" placeholder="Ingresa Calle" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">No. Ext. *</label>
                <input type="text" class="form-control" id="inputActualizarExtProveedores" placeholder="Ingresa No. Ext." style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">No.Int</label>
                <input type="text" class="form-control" id="inputActualizarIntProveedores" placeholder="Ingresa No.Int" style="width: 100%"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Télefono(1)</label>
                <input type="text" class="form-control" id="inputActualizarTelefono1Proveedores" placeholder="01-555-5555555" style="width: 100%"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarProveedores">Télefono(2)</label>
                <input type="tel" class="form-control" id="inputActualizarTelefono2Proveedores" placeholder="01-555-5555555" style="width: 100%"/>                            
            </div>
        </div>
        <div id='estatus' class="col-md-4">
            <div class="form-group">
                <label for="CatalogoActualizarProveedores">Estatus</label>
                <select id="selectActualizarEstatusProveedores" class="form-control" style="width: 100%">
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
            <div class="errorActualizarProveedores"></div>
        </div>
        <!--Finalizando Error-->
    </div>  
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarProveedor"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>
