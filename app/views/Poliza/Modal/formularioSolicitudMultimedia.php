<form class="margin-bottom-0" id="formSolicitudMultimedia" data-parsley-validate="true" >
    <div class="col-md-12">
        <h4 class="m-t-10">Detalles de la Solicitud de Multimedia</h4>
    </div>
    <!--Empezando Separador-->
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="solicitudMultimedia">A que hora solicitaron el apoyo</label>
            <div id="inputFecha" class="input-group date calendario" >
                <input id="inputFechaSolicitaron" type="text" class="form-control" placeholder="Fecha y Hora" />
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>  
    <div class="col-md-12">
        <div class="form-group">
            <label for="solicitudMultimedia">Evidencia</label>
            <input id="inputEvidenciasSolicitaron" name="evidenciaSolicitaron[]" type="file" multiple />
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="solicitudMultimedia">A que hora recibieron el apoyo</label>
            <div id="fecha" class="input-group date">
                <input id="inputFechaRecibieron"  type="text" class="form-control" placeholder="Fecha y Hora" value=""/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="solicitudMultimedia">Evidencia</label>
            <input id="inputEvidenciasRecibieron" name="evidenciaRecibieron[]" type="file" multiple />
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group text-center">
            <br>
            <a href="javascript:;" class="btn btn-primary m-r-5 " id="btnGuardarSolicitudMultimedia"><i class="fa fa-save"></i> Guardar</a>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-inline muestraCarga"></div>
        </div>
    </div>
    <!--Empezando error--> 
    <div class="col-md-12">
        <div class="errorSolicitudMultimedia"></div>
    </div>
    <!--Finalizando Error-->
</form>  