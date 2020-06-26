<form class="margin-bottom-0" id="formEditarModelo" data-parsley-validate="true" >
    <div class="row">
        <div class="col-md-12">                        
            <div class="form-group">
                <h4 class="m-t-10">Editar Modelo</h4>
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
        <div class="col-md-4">
            <div class="form-group">
                <label>Marca de Equipo *</label>
                <select id="selectEditarMarcaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>      
                    <?php
                    foreach ($marcas as $item) {
                        if ($item['IdSub'] == $datos['idsub']) {
                            $selected = ($datos['idmar'] == $item['IdMar']) ? 'selected' : '';
                            echo '<option value="' . $item['IdMar'] . '" ' . $selected . '>' . $item['Marca'] . '</option>';
                        }
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
                <input type="text" value="<?php echo $datos['modelo'] ?>" class="form-control" id="inputEditarNombreModelo" placeholder="Ingresa nombre del modelo" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>No Parte *</label>
                <input type="text" value="<?php echo $datos['parte'] ?>" class="form-control" id="inputEditarParteModelo" placeholder="Ingresa número de parte" style="width: 100%" data-parsley-required="true"/>                            
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
        <div class="col-md-12">
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" class="form-control" id="inputEditarDescripcionModelo" value="<?php echo $datos['descripcion'] ?>" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Agregar Imagenes</label>
                <input id="archivosEditarModelo" name="archivosEditarModelo[]" type="file" multiple />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 text-center">
            <div class="form-group">
                <div class="form-inline">
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="btnEditarModelo"><i class="fa fa-save"></i> Guardar Cambios</a>
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
            <div class="errorEditarModelo"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>