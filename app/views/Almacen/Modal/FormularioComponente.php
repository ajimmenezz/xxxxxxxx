<form class="margin-bottom-0" id="formNuevoComponente" data-parsley-validate="true" >
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="form-group">
                <h3 class="m-t-10">Nuevo Componente</h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="form-group">
                <label>Equipo *</label>
                <select id="selectEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                    <option value="">Seleccionar</option>
                    <?php
                    foreach ($equipos as $item) {
                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="inputNombreComponente" placeholder="Ingresa nombre del componente" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <label>No. Parte *</label>
                <input type="text" class="form-control" id="inputParteComponente" placeholder="Ingresa número de parte" style="width: 100%" data-parsley-required="true"/>                            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="form-group">
                <div class="form-inline">
                    <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevoComponente"><i class="fa fa-plus"></i> Agregar</a>
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
            <div class="errorComponente"></div>
        </div>
    </div>
    <!--Finalizando Error-->
</form>


