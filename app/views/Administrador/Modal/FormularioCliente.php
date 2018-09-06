<form class="margin-bottom-0" id="formActualizarCliente" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloCliente' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Nombre *</label>
                <input type="text" class="form-control" id="inputActualizarNombreCliente" placeholder="Ingresa nombre del Cliente" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Razon Social *</label>
                <input type="text" class="form-control" id="inputActualizarRazonSocialCliente" placeholder="Ingresa la Razón Social" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Representante</label>
                <input type="text" class="form-control" id="inputActualizarRepresentanteCliente" placeholder="Ingresa el Representante" style="width: 100%" />                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Pais *</label>
                <select id="selectActualizarPaisCliente" class="form-control" style="width: 100%" data-parsley-required="true">
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
                <label for="catalogoActualizarCliente">Estado *</label>
                <select id="selectActualizarEstadoCliente" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Delegación o Municipio *</label>
                <select id="selectActualizarMunicipioCliente" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>            
            </div>
        </div> 
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogActualizarCliente">Colonia *</label>
                <select id="selectActualizarColoniaCliente" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="control-label">CP</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inputActualizarCPCliente" placeholder="CP"/>
                    <span class="input-group-addon">
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarBuscarCP"><i class="fa fa-search"></i></a>
                        <a href="javascript:;" class="btn btn-default btn-xs" id="btnActualizarLimpiarCP"><i class="fa fa-eraser"></i></a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Calle *</label>
                <input type="text" class="form-control" id="inputActualizarCalleCliente" placeholder="Ingresa la Calle" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">No. Ext. *</label>
                <input type="text" class="form-control" id="inputActualizarExtCliente" placeholder="Ingresa No. Ext." style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">No.Int *</label>
                <input type="text" class="form-control" id="inputActualizarIntCliente" placeholder="Ingresa No. Int" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div> 
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Télefono(1) *</label>
                <input type="text" class="form-control" id="inputActualizarTelefono1Cliente" placeholder="01-555-5555555" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Télefono(2) *</label>
                <input type="tel" class="form-control" id="inputActualizarTelefono2Cliente" placeholder="01-555-5555555" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCliente">Página Web *</label>
                <input type="text" class="form-control" id="inputActualizarPaginaCliente" placeholder="Ingresa Página Web" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-8">
            <div class="form-group">
                <label for="catalogoClienteCliente">eMail</label>
                <input type="text" class="form-control" id="inputActualizarEmailCliente" placeholder="Ingresa email" style="width: 100%" data-parsley-type="email" />                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarCliente"></div>
        </div>
        <!--Finalizando Error-->
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarCliente"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>
