<!--Empieza Vista de la tabla de seguimiento CE-->
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
<!--Finaliza Vista de la tabla de seguimiento CE-->

<!--Empieza Vista detallada del seguimieto de un servicio-->
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
                        <?php
                        if ($datos['infoServicios']['rol'] == "Jefe") {
                            echo '<label id="btnEditarServicio" class="btn btn-primary btn-sm hidden">
                                    <i class="fa fa-pencil"></i> Editar Servicio
                                </label>';
                        }
                        ?>
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
                                    <label>Fecha de Servicio: <label class="semi-bold" id="fechaServicio"></label></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Ticket: <label class="semi-bold" id="ticketServicio"></label></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <label>Atendido por: <label class="semi-bold" id="atendidoServicio"></label></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Solicitud: <label class="semi-bold" id="solicitudServicio"></label></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción del Servicio</label>
                                    <textarea id="textareaDescripcion" class="form-control semi-bold" rows="2" disabled></textarea>
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
                                            <label>Solicita: <label class="semi-bold" id="solicitaSolicitud"></label></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Fecha Solicitud: <label class="semi-bold" id="fechaSolicitud"></label></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción Solicitud</label>
                                            <textarea id="textareaDescripcionSolicitud" class="form-control semi-bold" rows="6" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Finaliza seccion de mas detalles-->
                    </div>
                    <!--Empieza seccion de agregar Folio-->
                    <div id="agregarFolio" class="col-md-6 hidden" style="background: #F9EAC6">
                        <!--Empieza panel y eventos de folio-->
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
                                <i id="editarFolio" data-toggle="tooltip" data-placement="top" data-title="Editar" class="fa fa-2x fa-pencil hidden bloqueoConclusionBtn"></i>
                                <i id="cancelarFolio" data-toggle="tooltip" data-placement="top" data-title="Cancelar" class="fa fa-2x fa-times text-danger"></i>
                                <i id="eliminarFolio" data-toggle="tooltip" data-placement="top" data-title="Eliminar" class="fa fa-2x fa-trash-o text-danger hidden bloqueoConclusionBtn"></i>
                            </div>
                            <br><br><br>
                        </div>
                        <!--Finaliza panel y eventos de folio-->
                        <!--Empieza sección de información basica del folio-->
                        <div id="infoFolio" class="hidden">
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Creado por: <label class="semi-bold" id="creadoPorFolio"></label></label>
                                </div>
                                <div class="col-md-5">
                                    <label>Fecha Creación: <label class="semi-bold" id="fechaCreacionFolio"></label></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Solicita: <label class="semi-bold" id="solicitaFolio"></label></label>
                                </div>
                                <div class="col-md-5">
                                    <label>Prioridad: <label class="semi-bold" id="prioridadFolio"></label></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-7">
                                    <label>Asignado a: <label class="semi-bold" id="asignadoFolio"></label></label>
                                </div>
                                <div class="col-md-5">
                                    <label>Estatus: <label class="semi-bold" id="estatusFolio"></label></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label>Asunto: <label class="semi-bold" id="asuntoFolio"></label></label>
                                </div>
                            </div>
                            <!--Empieza seccion de mas detalles del folio-->
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label id="masDetallesFolio" style="cursor:pointer">+ Mas Detalles</label>
                                    <label id="menosDetallesFolio" class="hidden" style="cursor:pointer">- Menos Detalles</label>
                                    <!--Empiezan notas del folio-->
                                    <div id="detallesFolio" class="hidden">
                                        <div class="col-md-12 row">
                                            <div class="tab-content" style="background: #F9EAC6">
                                                <!--Empieza contenedor y scroll del acordean-->
                                                <div class="height-xs" data-scrollbar="true" data-height="50%" style="padding: 10px;">
                                                    <div id="accordion" class="panel-group">
                                                        <div id="collapseNotas"></div>
                                                    </div>
                                                </div>
                                                <!--Finaliza contenedor y scroll del acordean-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--Finalizan notas del folio-->
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
                                <label id="btnSinMaterial" class="btn btn-default btn-xs hidden bloqueoConclusionBtn">
                                    <i class="fa fa-toggle-off"></i> Sin Material
                                </label>
                                <label id="btnConMaterial" class="btn btn-primary btn-xs bloqueoConclusionBtn">
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
                                        <select id="selectSucursal" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"></select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea id="textareaObservaciones" class="form-control bloqueoConclusion" rows="4" data-parsley-required="true"></textarea>
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
                                <label id="btnReportar" href="#modalDefinirProblema" class="btn btn-success bloqueoConclusionBtn" data-toggle="modal">Reportar</label>
                                <br><br>
                            </div>
                            <div class="col-md-12">
                                <label>Historial de problemas</label>
                                <div class="height-xs" data-scrollbar="true" data-height="50%" style="padding: 10px;">
                                    <div id="observacionesProblemas"></div>
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
                                <div class="col-md-2 bloqueoConclusionBtn" >
                                    <a id="btnAgregarNodo" href="#modalMaterialNodo" class="btn btn-sm btn-success" data-toggle="modal"><i data-toggle="tooltip" data-placement="top" data-title="Agregar Nodo" class="fa fa-plus"></i></a>
                                </div>
                                <div class="col-md-12"><br></div>
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
                                            <th class="all">Area</th>
                                            <th class="all">Nodo</th>
                                            <th class="all">Switch</th>
                                            <th class="all"># Switch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                            <form id="formEvidenciaFija" data-parsley-validate="true" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div id="archivoEvidenciaFija" class="form-group bloqueoConclusionBtn">
                                        <input id="agregarEvidenciaFija" name="agregarEvidenciaProblema[]" type="file" multiple data-parsley-required="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="evidenciasMaterialFija"></div>
                                </div>
                            </form>
                        </div>
                        <!--Finaliza seccion agregar evidencia-->
                    </div>
                </div>
                <!--Finaliza seccion de evidencias-->

                <!--Empieza seccion de Firmas Existentes-->
                <div id="firmasExistentes" class="text-center hidden">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div id="firmaExistenteCliente" class="image-inner">
                                <a class="text-center" href="" data-lightbox="gallery-group-evidencia">
                                    <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="">
                                </a>                                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="firmaExistenteTecnico" class="image-inner">
                                <a class="text-center" href="" data-lightbox="gallery-group-evidencia">
                                    <img style="height:150px !important; max-height:150px !important;" class="img-thumbnail" src="">
                                </a>                                                
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza seccion de Firmas Existentes-->
                <!--Empieza seccion de botones-->
                <div id="botones" class="text-center">
                    <div class="col-md-12">
                        <br><br>
                        <div class="col-md-6">
                            <a id="btnGuardar" class="btn btn-success bloqueoConclusionBtn">
                                GUARDAR
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a id="btnConcluir" class="btn btn-danger bloqueoConclusionBtn" data-toggle="modal">
                                CONCLUIR
                            </a>
                        </div>
                    </div>
                </div>
                <!--Finaliza seccion de botones-->
            </div>
            <!--Finaliza Panel -->
        </div>
    </div>
    <!--Finaliza contenido general-->
</div>
<!--Finaliza Vista detallada del seguimieto de un servicio-->

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
                <div class="col-md-12">
                    <!--Empieza seccion agregar nodo-->
                    <form id="formDatosNodo" class="margin-bottom-0" data-parsley-validate="true">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Area</label>
                                <select id="selectArea" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nodo</label>
                                <input id="inputNodo" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Switch</label>
                                <select id="selectSwith" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label># de Switch</label>
                                <input id="inputNumSwith" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                    </form>
                    <!--Finaliza seccion agregar nodo-->
                </div>
                <div class="col-md-12">
                    <form id="formMaterial" data-parsley-validate="true" enctype="multipart/form-data">
                        <!--Empieza seccion agregar Material-->
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Material</label>
                                    <select id="selectMaterial" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"></select>
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
                                    <input id="materialUtilizar" class="form-control bloqueoConclusion" style="width: 100%" data-parsley-required="true"/>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <br>
                                <label id="btnAgregarMaterialATablaNodo" class="btn btn-success bloqueoConclusionBtn">
                                    <i class="fa fa-plus"></i>
                                </label>
                            </div>
                        </div>
                        <!--Finaliza seccion agregar Material-->
                    </form>
                </div>
                <!--Inicio Nota de material extra-->
                <div id="notaMaterial" class="col-md-12 hidden">
                    <label style="color: red">Has seleccionado material que no tienes disponible</label>
                </div>
                <!--Fin Nota de material extra-->
                <form id="formEvidenciaMaterial" data-parsley-validate="true" enctype="multipart/form-data">
                    <!--Empieza tabla de material nodos-->
                    <div class="col-md-12">
                        <div class="table-responsive bloqueoConclusion">
                            <table id="table-materialNodo" class="table table-hover table-striped table-bordered" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">id</th>
                                        <th class="all">Material</th>
                                        <th class="all">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Inicio Nota de tabla-->
                    <div class="col-md-12">
                        <label style="color: red">Al dar Click en la tabla se eliminara el material registrado</label>
                    </div>
                    <!--Fin Nota de tabla-->
                    <!--Finaliza tabla de material nodos-->
                    <!--Empieza seccion de evidencia-->
                    <div class="text-center">
                        <div id="fileEvidencia" class="col-md-12">
                            <label>Evidencia</label><br>
                            <div id="archivoEvidencia" class="form-group bloqueoConclusionBtn">
                                <input id="agregarEvidenciaNodo" name="agregarEvidenciaNodo[]" type="file" multiple data-parsley-required="true">
                            </div>
                        </div>
                        <div id="fileEvidenciaActualizar" class="col-md-12 hidden">
                            <label>Evidencia</label><br>
                            <div id="archivoEvidencia" class="form-group bloqueoConclusionBtn">
                                <input id="actualizarEvidenciaNodo" name="actualizarEvidenciaNodo[]" type="file" multiple data-parsley-required="true">
                            </div>
                        </div>
                        <div id="notaEvidencia" class="col-md-12 hidden">
                            <label style="color: red">Es necesario enviar una evidencia</label>
                        </div>
                        <div id="fileMostrarEvidencia" class="col-md-12 hidden">
                            <div id="file-evidencia-subida" class="row editar-material">
                                <div class="col-md-12">
                                    <div id="evidenciasMaterialUtilizado">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"><br></div>
                    </div>
                    <!--Finaliza seccion de evidencia-->
                </form>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarAgregarMaterial" class="btn btn-sm btn-success bloqueoConclusionBtn" data-dismiss="modal"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnActualizarAgregarMaterial" class="btn btn-sm btn-success hidden bloqueoConclusionBtn"><i class="fa fa-refresh"></i> Actualizar</a>
                <a id="btnEliminarAgregarMaterial" class="btn btn-sm btn-danger hidden bloqueoConclusionBtn"><i class="fa fa-trash-o"></i> Eliminar</a>
                <a id="btnCancelarAgregarMaterial" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finalizando seccion para material nodo-->

<!--Empieza modal de reportar problemas-->
<div id="modalDefinirProblema" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Definir Problema</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="col-md-12">
                    <form id="formEvidenciaProblema" data-parsley-validate="true" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción del Problema</label>
                                <textarea id="textareaDescProblema" class="form-control" rows="4" data-parsley-required="true"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Evidencia</label><br>
                            <div id="archivoProblema" class="form-group">
                                <input id="agregarEvidenciaProblema" name="agregarEvidenciaProblema[]" type="file" multiple data-parsley-required="true">
                            </div>
                        </div>
                    </form>
                </div>   
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarProblema" class="btn btn-sm btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCancelarProblema" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de reportar problemas-->

<!--Empieza seccion de firmas-->
<div id="contentFirmasConclucion" class="content hidden">
    <!--Empieza contenido general-->
    <div id="panelFirmas" class="panel panel-inverse">
        <!--Empieza titulo pagina-->
        <div class="panel-heading">
            <h4 class="panel-title">Concluir Servicio</h4>
        </div>
        <!--Finaliza titulo pagina-->
        <!--Empieza Panel -->
        <div class="panel-body row">
            <!--Empieza contenido de elementos-->
            <div id="contentfirmaCliente" class="col-md-12 text-center">
                <form id="formAgregarCliente" data-parsley-validate="true" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Nombre y Firma de Cliente</label>
                                <input id="inputCliente" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                            </div>
                        </div>
                    </div>
                    <!--Empezando mensaje--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorMessageFirmaCliente"></div>
                        </div>
                    </div>
                    <!--Finalizando mensaje-->
                    <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div id="firmaCliente" style="width: 600px; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"><br><br></div>
                </form>
            </div>
            <div id="contentfirmaTecnico" class="col-md-12 text-center hidden">
                <form id="formAgregarTecnico" data-parsley-validate="true" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Firma de Técnico</label>
                            </div>
                        </div>
                    </div>
                    <!--Empezando mensaje--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorMessageFirmaTecnico"></div>
                        </div>
                    </div>
                    <!--Finalizando mensaje-->
                    <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div id="firmaTecnico" style="width: 600px; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"><br><br></div>
                </form>
            </div>
            <div class="col-md-12 text-center">
                <a id="btnResumen" class="btn btn-sm btn-info hidden"><i class="fa fa-info-circle"></i> Resumen</a>
                <a id="btnContinuar" class="btn btn-sm btn-success"><i class="fa fa-sign-in"></i> Continuar</a>
                <a id="btnTerminar" class="btn btn-sm btn-success hidden"><i class="fa fa-sign-in"></i> Concluir</a>
                <a id="btnRegresarServicio" class="btn btn-sm btn-danger"><i class="fa fa-rotate-180 fa-sign-in"></i> Regresar</a>
                <a id="btnRegresarServicio2" class="btn btn-sm btn-danger hidden"><i class="fa fa-rotate-180 fa-sign-in"></i> Regresar</a>
            </div>
            <!--Finaliza contenido de elementos-->
        </div>
        <!--Finaliza Panel -->
    </div>
    <!--Finaliza contenido general-->
</div>
<!--Finaliza seccion de firmas-->
