<form class="margin-bottom-0" id="formEditarComponente" data-parsley-validate="true" >
    <div class="row">
        <div class="col-md-12">                        
            <div class="form-group">
                <h4 class="m-t-10">Editar Componente</h4>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Equipo *</label>
                <select id="selectEditarEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>      
                    <?php
                    foreach ($equipos as $item) {
                        $selected = ($datos['idmod'] == $item['Id']) ? 'selected' : '';
                        echo '<option value="' . $item['Id'] . '" ' . $selected . '>' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" value="<?php echo $datos['componente'] ?>" class="form-control" id="inputEditarNombreComponente" placeholder="Ingresa nombre del componente" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>No Parte *</label>
                <input type="text" value="<?php echo $datos['parte'] ?>" class="form-control" id="inputEditarParteComponente" placeholder="Ingresa número de parte" style="width: 100%" data-parsley-required="true" />                            
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>Estatus</label>
                <select id="selectEditarEstatus" class="form-control" style="width: 100%" required>
                    <?php if ($datos['flag'] === 'Activo') { ?>
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    <?php } else { ?>
                        <option value="1">Activo</option>
                        <option value="0" selected>Inactivo</option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 text-center">
            <div class="form-group">
                <div class="form-inline">
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="btnEditarComponente"><i class="fa fa-save"></i> Guardar Cambios</a>
                    <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnEditarCancelar"><i class="fa fa-times"></i> Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="form-group">
                <div class="form-inline muestraEditarCarga"></div>
            </div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="errorEditarComponente"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>