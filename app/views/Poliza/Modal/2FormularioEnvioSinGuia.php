<div id="panelAsignacionGuia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Asignación de Guía</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de Guía</legend>
            </div>
        </div>
        <form id="formAsignacionGuia" data-parsley-validate="true">
            <fieldset>
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Comentarios de la solicitud *</label>
                            <textarea class="form-control" id="txtComentarios" rows="5" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Archivos de problemas o guía *</label> 
                            <input id="archivosProblemaGuia"  name="archivosProblemaGuia[]" type="file" multiple />
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarProblema" class="btn btn-sm btn-danger m-t-10 m-r-10 f-w-600 f-s-13">Guardar Problema</a>
                        <a id="btnGuardarSolicitud" class="btn btn-sm btn-success m-t-10 m-l-10 f-w-600 f-s-13">Guardar Solicitud</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>