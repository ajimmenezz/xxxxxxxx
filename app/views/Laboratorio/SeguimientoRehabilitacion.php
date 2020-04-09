<!-- Empezando contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Rehabilitación de Equipos</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right cambioVistas hidden">
            <label id="btnRegresar" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel-->
    <div id="panelRehabilitacionEquiposTabla" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading"></div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="col-md-12">
                <h3>Lista de Equipos por rehabilitar</h3>
                <div class="underline m-b-15 m-t-15"></div>
            </div>
            <div class="col-md-12">                       
                <div  id="tablaModelos" class="table-responsive">
                    <table id="data-tablaModelos" class="table table-bordered" style="cursor:pointer; background: white" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Modelo</th>
                                <th class="all">Serie</th>
                                <th class="all">Estatus</th>
                                <th class="all">Ticket/Folio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datos['equipos'] as $value) {
                                echo "<tr>";
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Producto'] . '</td>';
                                echo '<td>' . $value['Serie'] . '</td>';
                                echo '<td>' . $value['Estatus'] . '</td>';
                                echo '<td>0</td>';
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel-->

    <!-- Empezando panel-->
    <div id="panelRehabilitacionEquiposInfoModelo" class="panel panel-inverse cambioVistas hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading p-0">
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse" id="change">
                    <li class="active" id="bitacora"><a href="#infoBitacora" data-toggle="tab">Bitácora de seguimiento</a></li>
                    <li id="refacciones"><a href="#infoRefacciones" data-toggle="tab">Uso de refacciones</a></li>
                    <li id="deshuesar"><a href="#infoDeshuesar" data-toggle="tab">Deshuesar equipo</a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empieza contenido de pestañas-->
        <div class="tab-content">
            <!--Empezando cuerpo del panel de infoBitacora-->
            <div class="tab-pane fade active in" id="infoBitacora">
                <div class="panel-body">
                    <!--Empieza encabezado del panel-->
                    <div class="row">
                        <div class="col-lg-8 col-md-9 col-sm-6 col-xs-6">
                            <h4 class="page-header">Historial de la bitácora de seguimiento</h4>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
                            <label id="btnConcluirRevision" class="btn btn-primary">Concluir Revisión</label>  
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
                            <label id="btnAgregarComentario" href="#modalAgregarComentario" class="btn btn-warning" data-toggle="modal">Agregar Comentario</label>  
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="mensajeConcluir"></div>
                    </div>
                    <!--Finaliza encabezado del panel-->
                    <!--Empezando informacion del equipo-->
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="col-sm-3 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input id="cargaModelo" type="text" class="form-control" style="width: 100%" disabled/>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Serie</label>
                                    <input id="cargaSerie" type="text" class="form-control" style="width: 100%" disabled/>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Estatus</label>
                                    <input id="cargaEstatus" type="text" class="form-control" style="width: 100%" disabled/>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Ticket/Folio</label>
                                    <input id="cargaTicket" type="text" class="form-control" style="width: 100%" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <ul class="media-list media-list-with-divider">
                                <div id="collapseComentarios"></div>
                            </ul>
                        </div>
                    </div>
                    <!--Terminando informacion del equipo-->
                </div>
            </div>
            <!--Finalizando cuerpo del panel de infoBitacora-->

            <!--Empezando cuerpo del panel de infoRefacciones-->
            <div class="tab-pane fade" id="infoRefacciones">
                <div class="panel-body">
                    <!--Empieza encabezado del panel-->
                    <div class="col-md-12">
                        <h4>Lista de refacciones disponibles</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finaliza encabezado del panel-->
                    <div class="col-md-12">                       
                        <div  id="tablaRefaccion" class="table-responsive">
                            <table id="data-tablaRefaccion" class="table table-bordered" style="cursor:pointer; background: white" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">IdRefaccion</th>
                                        <th class="never">Bloqueado</th>
                                        <th class="never">Key</th>
                                        <th class="all">Refacción</th>
                                        <th class="all">Serie</th>
                                        <th class="all">Selección</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--Finalizando cuerpo del panel de infoRefacciones-->

            <!--Empezando cuerpo del panel de infoDeshuesar-->
            <div class="tab-pane fade" id="infoDeshuesar">
                <div class="panel-body">
                    <!--Empieza encabezado del panel-->
                    <div class="col-md-12">
                        <h4>Componentes y estatus</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finaliza encabezado del panel-->
                    <div class="col-md-12 hidden">                       
                        <div  id="tablaDeshuesar" class="table-responsive">
                            <table id="data-tablaDeshuesar" class="table table-bordered" style="cursor:pointer; background: white" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">IdRefaccion</th>
                                        <th class="all">Refacción</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!--Empieza comentario deshuesar-->
                    <div class="col-md-12"><br></div>
                    <div class="row col-md-12">
                        <div id="nuevaTabla"></div>
                    </div>
                    <div class="col-md-12"><br></div>
                    <div class="col-md-12" style="background-color: papayawhip">
                        <p></p>
                        <p><strong>Importante:</strong> Al presionar el botón “Deshuesar Equipo”, todos los componentes con sus respectivos estatus serán trasladados a sus inventario y el equipo será marcado para destrucción.</p>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="mensajeConcluirDeshuesar"></div>
                    </div>
                    <!--Termina comentario deshuesar-->
                    <!--Comienza boton concluir-->
                    <div class="col-md-12"><br></div>
                    <div class="col-sm-4 col-md-4"></div>
                    <div class="col-sm-4 col-md-4">
                        <label id="btnConcluirDeshuesar" class="btn btn-primary">Deshuesar y Concluir</label>
                    </div>
                    <div class="col-sm-4 col-md-4"></div>
                    <!--Termina boton concluir-->
                </div>
            </div>
            <!--Finalizando cuerpo del panel de infoDeshuesar-->
        </div>
        <!--Finaliza contenido de pestañas-->

    </div>
    <!-- Finalizando panel-->
</div>
<!-- Finalizando contenido -->

<!--Empieza modal de reportar problemas-->
<div id="modalAgregarComentario" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Agregar Comentario</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="col-md-12">
                    <form id="formAgregarComentario" data-parsley-validate="true" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comentario *</label>
                                <textarea id="textareaComentario" class="form-control" rows="4" data-parsley-required="true"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Evidencia</label><br>
                            <div id="archivoProblema" class="form-group">
                                <input id="agregarEvidencia" name="agregarEvidencia[]" type="file" multiple>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div id="existenEvidencias"></div>
                </div>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarComentario" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCancelarComentario" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de reportar problemas-->
