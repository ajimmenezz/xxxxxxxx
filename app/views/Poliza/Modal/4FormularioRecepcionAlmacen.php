<div id="panelRecepcionAlmacen" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">3) Recepción en Almacén</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <legend>Documentación de recepción en Almacén</legend>
            </div>
        </div>
        <div class="row"></div>
        <ul class="nav nav-pills">
            <li class="active"><a href="#RecepcionAlm" data-toggle="tab">Recepcion</a></li>
            <li><a href="#problmeasRecepcionAlm" data-toggle="tab">Problemas de recepción</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="RecepcionAlm">
                <form id="formRecepcionAlmacen" data-parsley-validate="true">
                    <fieldset>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Usuario que recibe *</label>
                                <select id="" class="form-control listUsuarioRecibe" style="width: 100%" data-parsley-required="true">
                                    <option value="">Selecciona . . .</option>
                                    <option value="1">Horacio Padilla</option>
                                    <option value="2">Roberto Meza</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Fecha de Recepción *</label>
                                <input type="datetime-local" id="fechaRecepcionAlm" value="<?php echo $date = date('Y-m-d\TH:i'); ?>" class="form-control" data-parsley-pattern="^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01])T(|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$" required/>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Evidencia de recepción *</label> 
                                <input id="evidenciaRecepcionAlmacen"  name="evidenciaRecepcionAlmacen[]" type="file" multiple />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="errorFormulario"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a id="btnGuardarRecepcionAlm" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Guardar Recepción</a>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="tab-pane fade" id="problmeasRecepcionAlm">
                <form id="formProblemaRecepcionAlmacen" data-parsley-validate="true">
                    <div class="row">
                        <div class="col-md-8 col-sm-9 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Nota:</label>
                                <textarea class="form-control" rows="5" id="txtNota" value=""></textarea>                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-600 f-s-13">Adjuntos:</label>
                                <input id="adjuntosProblemaAlm" name="adjuntosTarea[]" type="file" multiple=""/>    
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a id="btnAgregarProblemaAlm" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13">Agregar Problema</a>
                        </div>
                    </div>
                </form>
            </div>                
        </div>
    </div>
</div>