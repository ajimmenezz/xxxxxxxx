<form class="margin-bottom-0" id="formNuevoModelo" data-parsley-validate="true" >
    <div class="row">
        <div class="col-md-12">                        
            <div class="form-group">
                <h3 class="m-t-10">Nuevo Modelo</h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Línea de Equipo *</label>
                <select id="selectLineaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($lineas as $item) {
                        if ($item['Flag'] > 0) {
                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Sublínea de Equipo *</label>
                <select id="selectSublineaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>               
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Marca de Equipo *</label>
                <select id="selectMarcaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>               
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="inputNombreModelo" placeholder="Ingresa nombre del modelo" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="form-group">
                <label>No. Parte *</label>
                <input type="text" class="form-control" id="inputParteModelo" placeholder="Ingresa número de parte" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="form-group">
                <div class="form-inline">
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoModelo"><i class="fa fa-plus"></i> Agregar</a>
                    <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelar"><i class="fa fa-times"></i> Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="form-inline muestraCarga"></div>
            </div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="row">
        <div class="col-md-12">
            <div class="errorModelo"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>


