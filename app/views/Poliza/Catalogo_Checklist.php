<div id="panel-catalogo-checklist" class="content">
    <h1 class="page-header">Catálogo de Revicion Fisica Checklist</h1>
    <div id="seccion-agregar-usuario" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
        <div class="panel-heading p-0">
            <div class="panel-heading-btn m-r-10 m-t-10">                                 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#tablaCategorias" data-toggle="tab">Categoria</a></li>
                    <li class=""><a href="#listaPreguntas" data-toggle="tab">Preguntas (Conceptos)</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in panel-body" id="tablaCategorias">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                        <div class="input-group">
                            <input type="text" id="txtNuevaCategoria" class="form-control" placeholder="Nueva Categoria">
                            <span role="button" id="btnAgregarCategoria" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                        </div>
                    </div>
                </div><br>
                <div Id="errorMessage"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Categoria</h4>
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="tabla-categorias" class="table table-striped table-bordered table-condensed" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                        foreach ($datos['Categorias'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['Nombre'] . '</td>';
                                            echo '<td>' . $value['Estatus'] . '</td>';
                                            echo '</tr>';
                                        }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade panel-body " id="listaPreguntas">
                <div class="row panel panel-inverse" data-sortable-id="form-stuff-3">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <h4>Lista de Preguntas (Conceptos)</h4>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group text-right">
                                <a href="javascript:;" class="btn btn-info" id="agregarPregunta"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div id="errorMessagePregunta"></div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tabla-preguntas" class="table table-striped table-bordered table-condensed" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all">Concepto</th>
                                        <th class="all">Etiqueta Reporte</th>
                                        <th class="all">Categoria</th>
                                        <th class="all">Área de Atención</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                      foreach ($datos['ListaPreguntas'] as $clave => $pregunta){                                         
                                            echo '<tr>';
                                               echo '<td>' . $pregunta['Id'] . '</td>';
                                               echo '<td>' . $pregunta['Concepto'] . '</td>';
                                               echo '<td>' . $pregunta['Etiqueta'] . '</td>';
                                               echo '<td>' . $pregunta['NombreCategoria'] . '</td>';
                                               echo '<td>' . $pregunta['Areas'] . '</td>';
                                               echo '<td>' . $pregunta['Estatus'] . '</td>';
                                            echo '</tr>';                                            
                                      }  
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>