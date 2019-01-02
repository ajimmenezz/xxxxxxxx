<div id="divRecepcionLaboratorio" class="content">
    <div id="panelRecepcionLaboratorio" class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">4) Recepción en Almacén</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <legend>Documentación de recepción en Laboratorio</legend>
                </div>
            </div>
            <form id="formRecepcionLaboratorio" data-parsley-validate="true">
                <fieldset>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                            <select id="usuarioRecibeLab" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Horacio Padilla</option>
                                <option value="2">Roberto Meza</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <div class="input-group date" id="fechaRecepcionLab">
                                <input type="text" class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Evidencia de recepción *</label> 
                            <input id="evidenciaRecepcionLab"  name="evidenciaRecepcionLab[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="errorFormulario"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarRecepcionLab" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Guardar Recepción</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>    
</div>