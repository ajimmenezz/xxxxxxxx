<form class="margin-bottom-0" id="formActualizarAreaAtencion" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloAreaAtencion' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActualizarAreasAtencion">Nombre del Área de Atención *</label>
                <input type="text" class="form-control" id="inputActualizarNombreAreasAtencion" placeholder="Ingresa nombre del Área de Atención" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActualizarAreasAtencion">Cliente *</label>
                <select id="selectActualizarClienteAreasAtencion" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($clientes as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-12">
            <div class="form-group">
                <label for="catalogoActualizarAreasAtencion">Descripción</label>
                <textarea class="form-control" id="inputActualizarDescripcionAreasAtencion" rows="5" placeholder="Descripción breve de que trata el Área de Atención" style="width: 100%" /> </textarea>                               
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div id='estatus' class="col-md-4">
            <div class="form-group">
                <label for="CatalogoActualizarAreasAtencion">Estatus</label>
                <select id="selectActualizarEstatusAreasAtencion" class="form-control" style="width: 100%">
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
        <div class="col-md-2">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Clave</label>
                <input type="tel" class="form-control" id="inputActualizarClave" placeholder="000" maxlength="4" style="width: 100%" />                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarAreasAtencion"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarAreasAtencion"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>
