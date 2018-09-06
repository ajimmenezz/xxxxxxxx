<form class="margin-bottom-0" id="formActualizarRegiones" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarNombreRegion">Nombre *</label>
                <input type="text" class="form-control" id="inputActualizarNombreRegion" placeholder="Ingresa nombre de la perfil" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div> 
        <div class="col-md-8">
            <div class="form-group">
                <label for="catalogoActualizarDescripcionRegion">Descripción *</label>
                <input type="text" class="form-control" id="inputActualizarDescripcionRegion" placeholder="Descripción breve de que trata el perfil" style="width: 100%" data-parsley-required="true"/>                              
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group">
                <label for="catalogoActualizarSucursalesRegion">Sucursales *</label>
                <select id="selectActualizarSucursalesRegion" class="form-control" style="width: 100%" multiple="multiple" data-parsley-required="true">
                    <?php
                    foreach ($sucursales as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div> 
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarSucursalesRegion">Estatus</label>
                <select id="selectActualizarEstatusRegion" class="form-control" style="width: 100%" required>
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
        <div class="col-md-12">
            <div class="form-group">
                <div class="form-inline muestraCarga"></div>
            </div>
        </div>
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarRegion"></div>
        </div>
        <!--Finalizando Error-->
    </div>     
</form>
