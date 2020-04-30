<!--Empieza Vista catalogo de Switch-->
<div id="contentCatalogoSwitch">
    <!--Empieza contenido general-->
    <div id="content" class="content">
        <div id="panelCatalogoSwitch" class="panel panel-inverse">

            <!--Empieza titulo pagina-->
            <div class="panel-heading">
                <h4 class="panel-title">Catálogo de Switch</h4>
            </div>
            <!--Finaliza titulo pagina-->

            <!--Empieza Panel -->
            <div class="panel-body row">
                <!--Empieza formulario de catalogo de switch-->
                <form id="formAgregarSwitch" data-parsley-validate="true">
                    <div class="col-md-12">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Marca de Equipo</label>
                                <select id="marcaEquipo" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($datos["infoSwitch"]["marcaEquipo"] as $marcaEquipo) {
                                        echo '<option value="' . $marcaEquipo['id'] . '">' . $marcaEquipo['text'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <div class="form-group">
                                <label>Modelo</label>
                                <input id="nombreEquipo" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>No. Parte</label>
                                <input id="noParteEquipo" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>

                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <div class="form-group">
                                <label>Agregar</label><br>
                                <label id="agregarSwitch" class="btn btn-white" data-toggle="tooltip" data-placement="top" data-title="Agregar">
                                    <i class="fa fa-2x fa-plus text-success"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <div class="form-group">
                                <label>Limpiar</label><br>
                                <label id="limpiarCampos" class="btn btn-white" data-toggle="tooltip" data-placement="top" data-title="Limpiar Campos">
                                    <i class="fa fa-2x fa-times text-danger"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
                <!--Finaliza formulario de catalogo de switch-->
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <div class="col-md-12">
                    <h4 class="m-t-10">Lista de Switch Generados</h4>
                </div>
                <!--Comenzando tabla de catalogos generados-->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="table-catalogo-switch" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Modelo</th>
                                    <th class="all">No. Parte</th>
                                    <th class="all">Marca</th>
                                    <th class="never">IdMarca</th>
                                    <th class="all">Flag</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['infoSwitch']["censoSwitch"] as $value) {
                                    echo "<tr>";
                                    echo '<td>' . $value['id'] . '</td>';
                                    echo '<td>' . $value['Modelo'] . '</td>';
                                    echo '<td>' . $value['Parte'] . '</td>';
                                    echo '<td>' . $value['Marca'] . '</td>';
                                    echo '<td>' . $value['IdMarca'] . '</td>';
                                    if ($value["Flag"] == 1) {
                                        echo '<th>Habilitado</th>';
                                    } else {
                                        echo '<th>Deshabilitado</th>';
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando tabla de catalogos generados-->
            </div>
            <!--Finaliza Panel -->
        </div>
    </div>
    <!--Finaliza contenido general-->
</div>
<!--Finaliza Vista catalogo de Switch-->

<div id="modalEditarSwitch" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Editar</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <!--Empieza seccion de edición-->
                <form id="formEditarSwith" data-parsley-validate="true" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Marca de Equipo</label>
                                <select id="marcaEquipoEditar" class="marcaEquipoEditar form-control" style="width: 100%" data-parsley-required="true">
                                    <?php
                                    foreach ($datos["infoSwitch"]["marcaEquipo"] as $marcaEquipo) {
                                        echo '<option value="' . $marcaEquipo['id'] . '">' . $marcaEquipo['text'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select id="estadoEquipoEditar" class="estadoEquipoEditar form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="1">Habilitado</option>
                                    <option value="0">Deshabilitado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Modelo</label>
                                <input id="nombreEquipoEditar" type="text" class="nombreEquipoEditar form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>No. Parte</label>
                                <input id="noParteEquipoEditar" type="text" class="noParteEquipoEditar form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                    </div>
                </form>
                <!--Finaliza seccion de edición-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptar" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrar" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>