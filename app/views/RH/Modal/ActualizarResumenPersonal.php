<div class="row m-t-10">  
    <form class="margin-bottom-0" id="formNuevoPersonal" data-parsley-validate="true" >
        <br>
        <br>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Área *</label>
                <select id="selectActualizarArea" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($areas as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Departamento *</label>
                <select id="selectActualizarDepartamento" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Puesto *</label>
                <select id="selectActualizarPerfil" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Apellido Paterno *</label>
                <input type="text" class="form-control" id="inputActualizarAPPersonal" placeholder="Ingresa el apellido paterno" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Apellido Materno *</label>
                <input type="text" class="form-control" id="inputActualizarAMPersonal" placeholder="Ingresa el apellido materno" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal">Nombre(s) *</label>
                <input type="text" class="form-control" id="inputActualizarNombrePersonal" placeholder="Ingresa nombre" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <div class="form-inline muestraCarga"></div>
            </div>
        </div>
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarPersonal"></div>
        </div>
        <!--Finalizando Error-->
    </form>
</div>   