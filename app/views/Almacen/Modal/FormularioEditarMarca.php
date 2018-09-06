<form class="margin-bottom-0" id="formEditarMarca" data-parsley-validate="true" >
    <div class="row">
        <div class="col-md-12">                        
            <div class="form-group">
                <h4 class="m-t-10">Editar Marca</h4>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>Línea de Equipo *</label>
                <select id="selectEditarLineaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($lineas as $item) {
                        $selected = ($datos['idlinea'] == $item['Id']) ? 'selected' : '';
                        echo '<option value="' . $item['Id'] . '" ' . $selected . '>' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Sublínea de Equipo *</label>
                <select id="selectEditarSublineaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>      
                    <?php
                    foreach ($sublineas as $item) {
                        if ($item['IdLinea'] == $datos['idlinea']) {
                            $selected = ($datos['idsub'] == $item['IdSub']) ? 'selected' : '';
                            echo '<option value="' . $item['IdSub'] . '" ' . $selected . '>' . $item['Sublinea'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" value="<?php echo $datos['marca'] ?>" class="form-control" id="inputEditarNombreMarca" placeholder="Ingresa nombre de la marca" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row">
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
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="btnEditarMarca"><i class="fa fa-save"></i> Guardar Cambios</a>
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
            <div class="errorEditarMarca"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>