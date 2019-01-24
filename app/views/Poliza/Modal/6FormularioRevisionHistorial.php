<div id="panelLaboratorioHistorial" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">5) Revisión en Laboratorio</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorFormulario"></div>
            </div>
        </div>
        <ul class="nav nav-pills col-md-6 col-sm-6 col-xs-12">
            <li class="active"><a href="#revisionHistorial" data-toggle="tab">Historial</a></li>
            <li><a href="#refaccionUtilizada" data-toggle="tab">Refacciones Utilizadas</a></li>
        </ul>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group text-right">
                <a href="javascript:;" class="btn btn-sm btn-danger f-s-13" id="consluirRevisionLab">Concluir Revisión</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="underline m-b-10"></div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="revisionHistorial">
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Comentarios y Observaciones</label>
                            <textarea class="form-control" id="comentariosObservaciones" rows="5" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Adjuntos</label> 
                            <input id="archivosLabHistorial"  name="archivosLabHistorial[]" type="file" multiple />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="errorAgregarComentario"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <a id="agregarComentarioHistorial" class="btn btn-sm btn-success m-t-10 m-r-10 f-w-600 f-s-13">Agregar Comentario</a>
                    </div>
                </div>
                <div class="row m-t-25">
                    <legend>Comentarios y adjuntos de la revisión</legend>
                </div>
            </div>
            <div class="tab-pane fade" id="refaccionUtilizada">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="f-w-600 f-s-13">Refacción Utilizada</label>
                            <select id="listRefaccionUtil" class="form-control" style="width: 100%" data-parsley-required="true">
                                <option value="">Selecciona . . .</option>
                                <option value="1">Mother Board</option>
                                <option value="2">Disco Duro</option>
                                <option value="3">Funete</option>
                                <option value="4">Cabezal de Impresión</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="">Modelo</label>
                            <input type="number" step="any" id="cantidad" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 m-t-20">
                        <a id="btnAgregarRefaccion" class="btn btn-success f-s-13">Agregar Refacción</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="listaRefaccionUtilizada" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Refacción</th>
                                        <th class="all">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Mother Board</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>