<div id="contentServiciosGeneralesRedes" class="content">
    <!-- Empezando titulo de cabecera -->
    <h1 class="page-header">Servicios Generales de Redes</h1>
    <!-- Finalizando titulo de cabecera -->
    <!--Empieza contenido general-->
    <div id="panelServicios" class="panel panel-inverse">
        <!--Empieza titulo pagina-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <h4 class="panel-title">Seguimiento</h4>
        </div>
        <!--Finaliza titulo pagina-->
        <!--Empieza Panel -->
        <div class="panel-body row">
            <!--Empieza el encabezado del panel-->

            <!--Finaliza el encabezado del panel-->
            <!--Empieza tabla de servicios-->
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="table-ServiciosGeneralesRedes" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Folio</th>
                                <th class="never">Ticket</th>
                                <th class="never">Solicitud</th>
                                <th class="all">Servicio</th>
                                <th class="all">Fecha de Creación</th>
                                <?php
                                if ($datos['infoServicios']['rol'] == "Jefe") {
                                    echo '<th class="all">Técnico</th>';
                                }
                                ?>
                                <th class="all">Descripción</th>
                                <th class="all">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datos['infoServicios']['servicios'] as $valor) {
                                if ($datos['infoServicios']['rol'] == "Jefe") {
                                    foreach ($valor as $dato) {
                                        echo '<tr>
                                                <th>' . $dato['Id'] . '</th>
                                                <th>' . $dato['Folio'] . '</th>
                                                <th>' . $dato['Ticket'] . '</th>
                                                <th>' . $dato['IdSolicitud'] . '</th>
                                                <th>' . $dato['TipoServicio'] . '</th>
                                                <th>' . $dato['FechaCreacion'] . '</th>
                                                <th>' . $dato['Atiende'] . '</th>
                                                <th>' . $dato['Descripcion'] . '</th>
                                                <th>' . $dato['Estatus'] . '</th>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr>
                                            <th>' . $valor['Id'] . '</th>
                                            <th>' . $valor['Folio'] . '</th>
                                            <th>' . $valor['Ticket'] . '</th>
                                            <th>' . $valor['IdSolicitud'] . '</th>
                                            <th>' . $valor['TipoServicio'] . '</th>
                                            <th>' . $valor['FechaCreacion'] . '</th>
                                            <th>' . $valor['Descripcion'] . '</th>
                                            <th>' . $valor['Estatus'] . '</th>
                                        </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Finaliza tabla de servicios-->
        </div>
        <!--Finaliza Panel -->
    </div>
    <!--Finaliza contenido general-->
</div>


<div id="contentServiciosRedes" class="hidden">
    <!--Empieza contenido general-->
    <div id="content" class="content">
        <div id="panelServiciosGeneralesRedes" class="panel panel-inverse">

            <!--Empieza titulo pagina-->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <label id="btnRegresar" class="btn btn-success btn-xs">
                        <i class="fa fa-reply"></i> Regresar
                    </label>
                </div>
                <h4 class="panel-title">Seguimiento</h4>
            </div>
            <!--Finaliza titulo pagina-->

            <!--Empieza Panel -->
            <div class="panel-body row">
                <!--Empieza subtitulo y accion de botones-->
                <div class="col-md-12">
                    <div class="panel-heading-btn">
                        <label id="btnEditarServicio" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i> Editar Servicio
                        </label>
                        <label id="btnAgregarFolio" class="btn btn-warning btn-sm">
                            <i class="fa fa-plus"></i> Agregar Folio
                        </label>
                    </div>
                    <h4>Información del Servicio</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finaliza subtitulo y accion de botones-->
                <!--Empieza detalles de servicio-->
                <div class="col-md-12">
                    <div id="infoServicio" class="col-md-12">
                        <div class="col-md-12 row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <label>Fecha de Servicio: </label>
                                </div>
                                <div class="col-md-4">
                                    <label>Ticket: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <label>Atendido por: </label>
                                </div>
                                <div class="col-md-4">
                                    <label>Solicitud: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción del Servicio</label>
                                    <textarea id="textareaDescripcion" class="form-control" rows="2" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Empieza seccion de mas detalles-->
                        <div class="col-md-12 row">
                            <div class="form-group">
                                <label id="masDetalles" style="cursor:pointer">+ Mas Detalles</label>
                                <label id="menosDetalles" class="hidden" style="cursor:pointer">- Menos Detalles</label>
                                <div id="detallesServicio" class="hidden">
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <label>Solicita: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Fecha Solicitud: </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción Solicitud</label>
                                            <textarea id="textareaDescripcion" class="form-control" rows="2" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza seccion de mas detalles-->
                    </div>
                    <!--Empieza seccion de agregar Folio-->
                    <div id="agregarFolio" class="col-md-6 hidden" style="background: #F9EAC6">
                        <div class="col-md-12">
                            <br>
                            <form id="folio" data-parsley-validate="true" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-md-2">Folio :</label>
                                    <div class="col-md-8">
                                        <input id="addFolio" class="form-control" data-parsley-required="true" />
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-2">
                                <i id="guardarFolio" data-toggle="tooltip" data-placement="top" data-title="Guardar" class="fa fa-2x fa-save  text-success"></i>
                                <i id="editarFolio" data-toggle="tooltip" data-placement="top" data-title="Editar" class="fa fa-2x fa-pencil hidden"></i>
                                <i id="cancelarFolio" data-toggle="tooltip" data-placement="top" data-title="Cancelar" class="fa fa-2x fa-times text-danger"></i>
                                <i id="eliminarFolio" data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger hidden"></i>
                            </div>
                            <br><br><br>
                        </div>
                        <!--Empieza sección de información basica del folio-->
                        <div id="infoFolio" class="hidden">
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Creado por: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Fecha Creación: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Solicita: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Prioridad: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Asignado a: </label>
                                </div>
                                <div class="col-md-5">
                                    <label>Estatus: </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label>Asunto: </label>
                                </div>
                            </div>
                            <!--Empieza seccion de mas detalles del folio-->
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label id="masDetallesFolio" style="cursor:pointer">+ Mas Detalles</label>
                                    <label id="menosDetallesFolio" class="hidden" style="cursor:pointer">- Menos Detalles</label>
                                    <div id="detallesFolio" class="hidden">
                                        <div class="col-md-12 row">
                                            <div class="tab-content" style="background: #F9EAC6">
                                                <div id="accordion" class="panel-group">
                                                    <div class="panel panel-inverse overflow-hidden">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title">
                                                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                                                                    <i class="fa fa-plus-circle pull-right"></i> 
                                                                    Nota
                                                                </a>
                                                            </h3>
                                                        </div>
                                                        <div id="collapseSeven" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Finaliza seccion de mas detalles del folio-->
                        </div>
                        <!--Finaliza sección de información basica del folio-->
                    </div>
                    <!--Finaliza seccion de agregar Folio-->
                </div>
                <!--Finaliza detalles de servicio-->
                <!--Empieza seccion de Solucion y Problemas-->
                <div class="col-md-12">
                    <!--Empieza datos solucion-->
                    <div class="col-md-6">
                        <!--Empieza Titulo solucion-->
                        <div class="col-md-12 row">
                            <div class="panel-heading-btn">
                                <label id="btnSinMaterial" class="btn btn-default btn-xs hidden">
                                    <i class="fa fa-toggle-off"></i> Sin Material
                                </label>
                                <label id="btnConMaterial" class="btn btn-primary btn-xs">
                                    <i class="fa fa-toggle-off fa-rotate-180"></i> Con Material
                                </label>
                            </div>
                            <h4>Datos de solución</h4>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finaliza Titulo solucion-->
                        <!--Empieza contenido de solucion-->
                        <div class="col-md-12">
                            <form id="formDatosSolucion" data-parsley-validate="true" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sucursal</label>
                                        <select id="selectSucursal" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
                                            <option value="prueba">Prueba</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea id="textareaObservaciones" class="form-control" rows="4" data-parsley-required="true"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--Finaliza contenido de solucion-->
                    </div>
                    <!--Finaliza datos solucion-->
                    <!--Empieza problemas reportados-->
                    <div class="col-md-6">
                        <!--Empieza Titulo problemas-->
                        <div class="col-md-12 row">
                            <h4>Problemas Reportados</h4>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <!--Finaliza Titulo problemas-->
                        <!--Empieza contenido de problemas-->
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <br>
                                <label id="btnReportar" class="btn btn-success">Reportar</label>
                                <br><br>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Historial de problemas</label>
                                    <textarea id="textareaObservaciones" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza contenido de problemas-->
                    </div>
                    <!--Finaliza problemas reportados-->
                </div>
                <!--Finaliza seccion de Solucion y Problemas-->
                <!--Empieza seccion Nodos-->
                <div id="conMaterial" class="col-md-12">
                    <!--Empieza Titulo tabla nodos-->
                    <div class="col-md-12">
                        <div class="panel-heading-btn">
                            <label id="btnVerMaterial" style="cursor:pointer">Ver Material Utilizado</label>
                        </div>
                        <div class="panel-heading-btn">
                            <label id="btnVerNodos" class="hidden" style="cursor:pointer">Ver Nodos</label>
                        </div>
                        <h4>Nodos</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finaliza Titulo tabla nodos-->
                    <div id="vistaNodos">
                        <div id="datosNodo">
                            <!--Empieza seccion agregar nodo-->
                            <div class="col-md-12">
                                <form id="formDatosNodo" class="margin-bottom-0" data-parsley-validate="true">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Area</label>
                                            <select id="selectArea" class="form-control" style="width: 100%" data-parsley-required="true">
                                                <option value="">Seleccionar</option>
                                                <option value="prueba">Prueba</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Nodo</label>
                                            <input id="inputNodo" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Switch</label>
                                            <select id="selectSwith" class="form-control" style="width: 100%" data-parsley-required="true">
                                                <option value="">Seleccionar</option>
                                                <option value="prueba">Prueba</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label># de Switch</label>
                                            <input id="inputNumSwith" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-1">
                                    <br>
                                    <a id="btnAgregarNodo" href="#" class="btn btn-sm btn-success" data-toggle="modal"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <!--Finaliza seccion agregar nodo-->
                        </div>
                        <!--Empieza tabla de nodos-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-nodo" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">id</th>
                                            <th class="all">Sucursal</th>
                                            <th class="all">Nodo</th>
                                            <th class="all">Switch</th>
                                            <th class="all"># Switch</th>
                                            <th class="all">Evidencia</th>
                                            <th class="all">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th class="all id">1</th>
                                            <th class="all sucursal">Platino</th>
                                            <th class="all nodo">Platino</th>
                                            <th class="all switch">Platino</th>
                                            <th class="all numSwitch">5</th>
                                            <th class="all" style="text-align: center"><a href="#" class="btn btn-sm btn-white evidenciaNodo" data-toggle="modal"><i class="fa fa-2x fa-file"></i></a></th>
                                            <th style="text-align: center">
                                                <a id="editarNodo" href="#" class="btn btn-sm btn-white editarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Nodo" class="fa fa-2x fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a id="editarMaterial" href="#" class="btn btn-sm btn-white editarMaterial" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Material" class="fa fa-2x fa-file-photo-o text-warning"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="#" class="btn btn-sm btn-white eliminarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger"></i></a>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="all id">2</th>
                                            <th class="all sucursal">Platino2</th>
                                            <th class="all nodo">Platino2</th>
                                            <th class="all switch">Platino2</th>
                                            <th class="all numSwitch">5</th>
                                            <th class="all" style="text-align: center"><a href="#" class="btn btn-sm btn-white evidenciaNodo" data-toggle="modal"><i class="fa fa-2x fa-file"></i></a></th>
                                            <th style="text-align: center">
                                                <a id="editarNodo" href="#" class="btn btn-sm btn-white editarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Nodo" class="fa fa-2x fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a id="editarMaterial" href="#" class="btn btn-sm btn-white editarMaterial" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Material" class="fa fa-2x fa-file-photo-o text-warning"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="#" class="btn btn-sm btn-white eliminarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger"></i></a>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="all id">3</th>
                                            <th class="all sucursal">Platino3</th>
                                            <th class="all nodo">Platino3</th>
                                            <th class="all switch">Platino3</th>
                                            <th class="all numSwitch">5</th>
                                            <th class="all" style="text-align: center"><a href="#" class="btn btn-sm btn-white evidenciaNodo" data-toggle="modal"><i class="fa fa-2x fa-file"></i></a></th>
                                            <th style="text-align: center">
                                                <a id="editarNodo" href="#" class="btn btn-sm btn-white editarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Nodo" class="fa fa-2x fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a id="editarMaterial" href="#" class="btn btn-sm btn-white editarMaterial" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Editar Material" class="fa fa-2x fa-file-photo-o text-warning"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="#" class="btn btn-sm btn-white eliminarNodo" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger"></i></a>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Finaliza tabla de nodos-->
                    </div>
                    <div id="vistaMaterialUsado" class="hidden">
                        <!--Empieza tabla de material utilizado-->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table-material" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Material</th>
                                            <th class="all">Cantidad</th>
                                            <th class="all">Almacén Disponible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Cable</th>
                                            <th>10 m</th>
                                            <th>60 m</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Finaliza tabla de material utilizado-->
                    </div>
                </div>
                <!--Finaliza seccion Nodos-->

                <!--Empieza seccion de evidencias-->
                <div id="sinMaterial" class="col-md-12 hidden">
                    <!--Empieza Titulo de evidencias-->
                    <div class="col-md-12">
                        <h4>Evidencias</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                    <!--Finaliza Titulo de evidencias-->
                    <div id="vistaEvidencias">
                        <!--Empieza seccion agregar evidencia-->
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label id="addEvidencia" class="btn btn-success">
                                    <i class="fa fa-plus"></i> EVIDENCIA
                                </label>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <div class="form-group">
                                    <textarea id="textareaObservaciones" class="form-control" rows="8"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza seccion agregar evidencia-->
                    </div>
                </div>
                <!--Finaliza seccion de evidencias-->

                <!--Empieza seccion de botones-->
                <div id="botones" class="text-center">
                    <div class="col-md-12">
                        <br><br>
                        <div class="col-md-6">
                            <a id="btnGuardar" class="btn btn-success">
                                GUARDAR
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#modalConcluir" class="btn btn-danger" data-toggle="modal">
                                CONCLUIR
                            </a>
                        </div>
                    </div>
                </div>
                <!--Finaliza seccion de botones-->
                <!--Segmento de modal reportar-->
                <div id="segReportar" class="hidden">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descripción del Problema</label>
                            <textarea id="textareaDescProblema" class="form-control" rows="4" data-parsley-required="true"></textarea>
                        </div>
                    </div>
                </div>
                <!--Segmento de modal reportar-->
            </div>
            <!--Finaliza Panel -->
        </div>
    </div>
    <!--Finaliza contenido general-->
</div>

<!--Empezando seccion para material nodo-->
<div id="modalMaterialNodo" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Material</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <form id="formMaterial" data-parsley-validate="true" enctype="multipart/form-data">
                    <!--Empieza seccion agregar Material-->
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Material</label>
                                <select id="selectMaterial" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <option value="prueba">Prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cantidad Disponible</label>
                                <input id="materialDisponible" class="form-control" style="width: 100%" disabled/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cantidad Utilizar</label>
                                <input id="materialUtilizar" type="number" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <br>
                            <label id="btnAgregarMaterialNodo" class="btn btn-success">
                                <i class="fa fa-plus"></i>
                            </label>
                        </div>
                    </div>
                    <!--Finaliza seccion agregar Material-->
                </form>
                <form id="formEvidenciaMaterial" data-parsley-validate="true" enctype="multipart/form-data">
                    <!--Empieza tabla de material nodos-->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table-materialNodo" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Material</th>
                                        <th class="all">Cantidad</th>
                                        <th class="all">Accion</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Finaliza tabla de material nodos-->
                    <!--Empieza seccion de evidencia-->
                    <div class="col-md-12">
                        <div id="archivoCitaIncapacidad" class="form-group">
                            <label>Evidencia</label><br>
                            <input id="agregarEvidenciaNodo" name="agregarEvidenciaNodo[]" type="file" multiple data-parsley-required="true">
                        </div>
                    </div>
                    <!--Finaliza seccion de evidencia-->
                </form>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarM" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrarM" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finalizando seccion para material nodo-->

<!--Empezando modal editar nodo-->
<div id="modalEditarNodo" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Actualizar Nodo</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <!--Empieza seccion edicion nodo-->
                <div class="col-md-12">
                    <form id="formEdicionNodo" class="margin-bottom-0" data-parsley-validate="true">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Area</label>
                                <select id="selectEdicionArea" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <option value="prueba">Prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nodo</label>
                                <input id="inputEdicionNodo" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Switch</label>
                                <select id="selectEdicionSwith" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <option value="prueba">Prueba</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label># de Switch</label>
                                <input id="inputEdicionNumSwith" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                    </form>
                </div>
                <!--Finaliza seccion edicion nodo-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarAM" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finalizando modal editar nodo-->

<!--Empieza modal de evidencia-->
<div id="modalEvidencia" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Evidencia</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body text-center">
                <!--Empieza seccion de evidencia-->
                <div class="col-md-12">
                     <div class="image-inner">
                        <a class="text-center" href="" data-lightbox="gallery-group-evidencia">
                            <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="">
                        </a>                                                
                    </div>
                </div>
                <!--Finaliza seccion de evidencia-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnCerrarAM" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de evidencia-->

<!--Empieza modal de conclucion-->
<div id="modalConcluir" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Concluir</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <!--Empieza seccion del cliente-->
                <div class="col-md-12">
                    <form id="formAgregarCliente" data-parsley-validate="true" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre Cliente</label>
                                    <input id="inputCliente" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="f-s-14 f-w-600 m-t-10">Firma del Técnico (Siccob o Lexmark) * :</label>
                                <div class="input-group m-b-10">
                                    <span class="input-group-addon"><i class="fa fa-child"></i></span>
                                    <label class="form-control"></label>
                                </div>
                                <div class="image-inner">
                                    <a class="text-center" href="" data-lightbox="gallery-group-firmas">
                                        <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="" alt="Firma Técnico">
                                    </a>                                                
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Firma Cliente</label>
                                    <input id="inputFirmaCliente" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--Finaliza seccion del cliente-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnResumen" class="btn btn-sm btn-info"><i class="fa fa-info-circle"></i> Resumen</a>
                <a id="btnConcluir" class="btn btn-sm btn-success"><i class="fa fa-sign-in"></i> Continuar</a>
                <a class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de conclucion-->
