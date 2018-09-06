<form class="margin-bottom-0" id="formRegionCliente" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloRegionCliente' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursales">Cliente *</label>
                <select id="selectClienteRegion" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($clientes as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="catalogoRegionCliente">Nombre de la Región *</label>
                <input type="text" class="form-control" id="inputNombreRegion" placeholder="Ingresa el nombre de la Región" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoRegionCliente">Responsable Cliente *</label>
                <input type="text" class="form-control" id="inputResposableCliente" placeholder="Ingresa Nombre del Resposable Cliente" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoRegionCliente">Email Responsable *</label>
                <input type="text" class="form-control" id="inputEmailResponsable" placeholder="Ingresa el Email del Responsable" style="width: 100%" data-parsley-type="email" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoRegionCliente">Responsable Siccob *</label>
                <select id="selectResposableInterno" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($responsablesSiccob as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div id='estatusRegionCliente' class="col-md-4">
            <div class="form-group">
                <label for="catalogoRegionCliente">Estatus</label>
                <select id="selectEstatusRegionCliente" class="form-control" style="width: 100%">
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
            <div class="errorRegionCliente"></div>
        </div>
        <!--Finalizando Error-->
    </div>   
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarRegionCliente"><i class="fa fa-save"></i> Guardar</a>
            </div>
        </div>
    </div>
</form>