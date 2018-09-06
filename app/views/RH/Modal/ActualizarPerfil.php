<form class="margin-bottom-0" id="formActualizarPerfiles" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloPerfil' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarArea">Área *</label>
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
                <label for="catalogoActualizarDepartamento">Departamento *</label>
                <select id="selectActualizarDepartamento" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                    <option value="">Seleccionar</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarNombre">Nombre *</label>
                <input type="text" class="form-control" id="inputActualizarNombrePerfil" placeholder="Ingresa nombre de la perfil" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div> 
    </div>
    <div class="row m-t-10">
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogActualizarPerfil">Nivel *</label>
                <select id="selectActualizarNivel" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarClave">Clave *</label>
                <input type="text" class="form-control" id="inputActualizarClave" placeholder="Ingresa clave del perfil" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="catalogoActualizarCantidad">Cantidad *</label>
                <input type="number" class="form-control" id="inputActualizarCantidad" placeholder="Ingresa cantidad del perfil" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group">
                <label for="catalogoActualizarPerfil">Descripción *</label>
                <textarea class="form-control" id="inputActualizarDescripcionPerfil" rows="4" placeholder="Descripción breve de que trata el perfil" style="width: 100%" data-parsley-required="true"/> </textarea>                               
            </div>
        </div>
        <?php if ($Autorizacion) { ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="catalogoActualizarPermisos">Permisos *</label>
                    <select id="selectActualizarPermisos" class="form-control" style="width: 100%" multiple="multiple">
                        <?php
                        foreach ($permisos as $item) {
                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="checkbox" id="checkboxPerfilesActualizar" > Todos los permisos
                </div>
            </div> 
        <?php } ?>
        <div id='estatus' class="col-md-4">
            <div class="form-group">
                <label for="selectEstatus">Estatus</label>
                <select id="selectActualizarEstatus" class="form-control" style="width: 100%">
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
        <!--Empezando error--> 
        <div class="col-md-12">
            <div class="errorActualizarPerfil"></div>
        </div>
        <!--Finalizando Error-->
        <div class="row m-t-10">
            <div class="col-md-12">
                <div class="form-group text-center">
                    <br>
                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarPerfil"><i class="fa fa-save"></i> Guardar</a>
                </div>
            </div>
        </div>
    </div>     
</form>
