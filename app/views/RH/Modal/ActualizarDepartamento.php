<form class="margin-bottom-0" id="formActualizarDepartamento" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActualizarDepartamento">Área *</label>
                <select id="selectActualizarAreaDepartamento" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($areas as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoActtualizarDepartamento">Nombre *</label>
                <input type="text" class="form-control" id="inputActualizarNombreDepartamento" placeholder="Ingresa nombre de la departamento" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div> 
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group">
                <label for="catalogoActualizarDepartamento">Descripción *</label>
                <textarea class="form-control" id="inputActualizarDescripcionDepartamento" rows="5" placeholder="Descripción breve de que trata el departamento" style="width: 100%" data-parsley-required="true"/> </textarea>                               
            </div>
        </div> 
        <div class="col-md-4">
            <div class="form-group">
                <label for="selectEstatusArea">Estatus</label>
                <select id="selectActualizarEstatus" class="form-control" style="width: 100%" required>
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
            <div class="errorActualizarDepartamento"></div>
        </div>
        <!--Finalizando Error-->
    </div>     
</form>
