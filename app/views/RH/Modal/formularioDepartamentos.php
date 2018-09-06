<!--Inicia formulario para catálogo de departamentos -->
<form class="margin-bottom-0" id="formDepartamentos" data-parsley-validate="true">
    <div class="row m-t-10">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 id='tituloDepartamento' class="m-t-10"></h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-6">
            <div class="form-group">
                <label for="catalogoDepartamentos">Área *</label>
                <select id="selectAreaDepartamento" class="form-control" style="width: 100%" data-parsley-required="true">
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
                <label for="catalogoDepartamentos">Nombre *</label>
                <input type="text" class="form-control" id="inputNombreDepartamento" placeholder="Ingresa nombre del departamento" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group">
                <label for="catalogoDepartamentos">Descripción *</label>
                <textarea class="form-control" id="inputDescripcionDespartamento" rows="5" placeholder="Descripción breve de que trata el departamento" style="width: 100%" data-parsley-required="true"/> </textarea>                               
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div id="nuevoDepartamento" class="col-md-12">
            <div class="form-group text-center">
                <br>
                <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoDepartamento"><i class="fa fa-plus"></i> Agregar</a>
                <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarDepartamento"><i class="fa fa-times"></i> Cancelar</a>
            </div>
        </div>
        <div id="actualizarDepartamento" class="hidden">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="selectEstatusArea">Estatus</label>
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
            <div class="col-md-12">
                <div class="form-group text-center">
                    <br>
                    <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnActualizarDepartamento"><i class="fa fa-floppy-o"></i> Guardar</a>
                    <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarActualizarDepartamento"><i class="fa fa-times"></i> Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="form-group">
                <div class="form-inline muestraCarga"></div>
            </div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="row m-t-10">
        <div class="col-md-12">
            <div class="errorDepartamento"></div>
        </div>
        <!--Finalizando Error-->
    </div>
</form>