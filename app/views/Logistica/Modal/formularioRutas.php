<form class="margin-bottom-0" id="formNuevaRuta" data-parsley-validate="true" >
    <div class="col-md-6">
        <h3 class="m-t-10">Ruta</h3>
    </div>
    <div id="empezarRuta" class="col-md-6 hidden">
        <div class="form-group text-right">
            <a href="javascript:;" class="btn btn-warning btn-lg" id="btnEmpezarRuta"><i class="fa fa-truck"></i> Empezar Ruta</a>
        </div>
    </div>
    <!--Empezando Separador-->
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>
    <div id="datosActualizar" class="hidden">
        <div class="col-md-4 m-t-10">
            <div class="form-group">
                <label for="rutasLogistica">Código de Ruta</label>
                <br>
                <label id="textCodigoRuta"></label>                           
            </div>
        </div>
        <div class="col-md-4 m-t-10">
            <div class="form-group">
                <label for="rutasLogistica">Fecha</label>
                <br>
                <label id="textFechaRuta"></label>                           
            </div>
        </div>
    </div>
    <div id="fechaNueva" class="col-md-4 m-t-10">
        <div class="form-group">
            <label for="rutasLogistica"> Fecha de Ruta *</label>
            <div id="inputFecha" class="input-group date calendario" >
                <input id="inputFechaRutas" type="text" class="form-control" placeholder="Fecha de Ruta" />
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div> 
    <div class="col-md-4 m-t-10">
        <div class="form-group">
            <label for="rutasLogistica">Chofer o Auxiliar Asignado *</label>
            <select id="selectChoferRutas"  style="width: 100%" data-parsley-required="true">
                <option value="">Seleccionar</option>
                <?php
                foreach ($choferes as $item) {
                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . ' ' . $item['ApPaterno'] . ' ' . $item['ApMaterno'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div id="botonesNuevaRuta" class="col-md-12 m-t-10 text-center">
        <a href="javascript:;" class="btn btn-success m-r-5 " id="btnNuevaRuta"><i class="fa fa-floppy-o"></i> Guardar</a>
        <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarRuta"><i class="fa fa-times"></i> Cancelar</a>
    </div>
    <div id="botonesActualizarRuta" class="col-md-12 m-t-10 text-center hidden">
        <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnActualizarRuta"><i class="fa fa-pencil-square-o"></i> Actualizar</a>
        <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnCancelarActualizarRuta"><i class="fa fa-times"></i> Cancelar Ruta</a>
        <a href="javascript:;" class="btn btn-default m-r-5 " id="btnConcliurRuta"><i class="fa fa-check"></i> Concluir Ruta</a>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-inline muestraCarga"></div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="col-md-12">
        <div class="errorRuta"></div>
    </div>
    <!--Finalizando Error-->
</form>
