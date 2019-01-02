<div id="divRecepcionLogistica" class="content">
    <div id="panelRecepcionLogistica" class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">6) Recepción en Logística</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <legend>Documentación de recepción en Logística</legend>
                </div>
            </div>
            <form id="formRecepcionLogistica" data-parsley-validate="true">
                <fieldset>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                            <select id="usuarioRecepcionLogistica" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Enrique Rodriguez</option>
                                <option value="2">Carlos Camacho</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                            <div class="input-group date" id="fechaRecepcionLogistica">
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
                            <input id="evidenciaRecepcionLogistica"  name="evidenciaRecepcionLogistica[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="errorFormulario"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="btnGuardarRecepcionLogistica" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Guardar Recepción</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>    
</div>