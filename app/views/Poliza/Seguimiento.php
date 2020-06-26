<!-- Empezando #contenido de lista de servicios-->
<div id="listaPoliza" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento Póliza</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel de lista de servicios de solicitudes -->
    <div id="panelSeguimientoPoliza" class="panel panel-inverse">

        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Seguimiento Póliza</h4>
        </div>
        <!--Finalizando cabecera del panel-->

        <!--Empezando cuerpo del panel-->
        <div class="panel-body">

            <!--Empezando error--> 
            <div class="row"> 
                <div class="col-md-12">
                    <div class="errorListaPoliza"></div>
                </div>
            </div>
            <!--Finalizando Error-->

            <!--Empezando Titulo y buscador-->
            <div class="row">

                <div class="col-md-6">  
                    <h3 class="m-t-10">Lista General de Tickets y Tareas Pendientes</h3>
                </div>

                <div class="col-xs-12 col-md-3 text-right hidden">
                    <a href="javascript:;" class="btn btn-info m-r-5 " id="btnMostrarServicios"><i class="fa fa-refresh"></i> Mostrar todos los tickets</a>
                </div>

                <div class="col-xs-12 col-md-3 hidden">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputBuscarFolio" placeholder="Folio"/>
                            <span class="input-group-addon">
                                <a href="javascript:;" class="btn btn-default btn-xs" id="btnBuscarFolio"><i class="fa fa-search"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!--Empezando Titulo y buscador-->

            <!--Empezando Separador-->
            <div class="row">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
            </div> 
            <!--Finalizando Separador-->

            <!--Empezando Tabla de servicios-->
            <div class="table-responsive">
                <table id="data-table-poliza" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="never">Id</th>
                            <th class="all">Ticket</th>
                            <th class="all">Solicitud </th>
                            <th class="all">Servicio</th>
                            <th class="all">Fecha de Creación</th>
                            <th class="all">Descripcion</th>
                            <th class="all">Estatus</th>
                            <th class="never">IdEstatus</th>
                            <th class="all">Folio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['Servicios'])) {
                            foreach ($datos['Servicios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Id'] . '</td>';
                                echo '<td>' . $value['Ticket'] . '</td>';
                                echo '<td>' . $value['IdSolicitud'] . '</td>';
                                echo '<td>' . $value['Servicio'] . '</td>';
                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                echo '<td>' . $value['Descripcion'] . '</td>';
                                echo '<td>' . $value['NombreEstatus'] . '</td>';
                                echo '<td>' . $value['IdEstatus'] . '</td>';
                                ($value['Folio'] === '0') ? $folio = '' : $folio = $value['Folio'];
                                echo '<td>' . $folio . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
            <!--Finalizando Tabla de servicios-->
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel de lista de servicios de solicitudes --> 

</div>
<!-- Finalizando #contenido de lista de servicios-->

<!-- Empezando #contenido del servicio -->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando #contenido del servicio --> 

<!--Empezando #contenido del servicio sin clasificar->-->
<div id="antesYDespues" class="content hidden"></div>
<!-- Finalizando #contenido del servicio sin clasificar--> 

<!--Empezando #contenido del servicio (version 2) -->
<div id="panelDetallesTicket" class="content hidden">

    <!--Empezando Titulo y boton regresar-->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header">Seguimiento Póliza</h1>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li id="btnAgregarVuelta" class="hidden"><a href="#"><i class="fa fa-plus"></i> Agregar Vuelta</a></li>
                    <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnExportarPDF" class="hidden"><a href="#"><i class="fa fa-check"></i> Exportar PDF</a></li>
                    <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar Apoyo</a></li>
                    <li id="btnSubirInformacionSD"><a href="#"><i class="fa fa-cloud-upload"></i> Subir Información SD</a></li>
                    <li id="btnValidarServicio" class="hidden"><a href="#"><i class="fa fa-check"></i> Validar Servicio</a></li>
                </ul>
            </div>
            <label id="btnRegresar" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div> 
    <!--Finalizando Titulo y boton regresar -->

    <!--Empezando panel con pestañas-->
    <div id="panel-ticket" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">

        <!--Empezando definición de pestañas-->
        <div class="panel-heading p-0">

            <!-- Empezando botones de cabecera -->  
            <div class="panel-heading-btn m-r-10 m-t-10">
                <label id="btnCierre" class="btn btn-info btn-xs">
                    <i class="fa fa fa-unlock-alt"></i> Cierre
                </label> 
                <label id="btnReportarProblema" class="btn btn-danger btn-xs">
                    <i class="fa fa fa-exclamation-triangle"></i> Reportar Problema
                </label> 
                <!--<a id="btnReportarProblema" href="#modalReportarProblema" class="btn btn-danger btn-xs m-r-5"><i class="fa fa fa-exclamation-triangle"></i> Reportar Problema</a>-->
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <!-- Finalizando botones de cabecera -->

            <!-- Empezando nav-tabs -->
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#General" data-toggle="tab">Información</a></li>
                    <li class=""><a href="#Solucion" data-toggle="tab">Solución</a></li>
                    <li class=""><a href="#BitacoraProblemas" data-toggle="tab">Bitácara problemas</a></li>                    
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>          
                </ul>
            </div>
            <!-- Finalizando nav-tabs -->

        </div>
        <!--Finalizando definición de pestañas-->

        <!-- Empezando contenido de pestañas -->
        <div class="tab-content">

            <!--Empezando la seccion información-->
            <div id="General" class="tab-pane fade active in" > 

                <!--Empezando Contenido de seccion-->
                <div class="panel-body">

                    <!--Empezando titulo y botones-->
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="m-t-10">Información General de solicitud y servicio</h4>
                        </div>
                        <div class="col-md-6 m-t-10 text-right">
                            <a id="btnGuardarInformacionGeneral" href="javascript:;" class="btn btn-success btn-xs m-r-5 hidden"><i class="fa fa fa-floppy-o"></i> Guardar</a>
                            <a id="btnCancelarInformacionGeneral" href="javascript:;" class="btn btn-danger btn-xs m-r-5 hidden"><i class="fa fa fa-times"></i> Cancelar</a>
                            <a id="btnEditarInformacionGeneral" href="javascript:;" class="btn btn-warning btn-xs m-r-5"><i class="fa fa fa-pencil-square-o"></i> Editar</a>
                        </div>
                    </div>
                    <!--Finalizando titulo y botones-->

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador--> 

                    <!--Empezando formulario -->
                    <form class="margin-bottom-0" id="formInformacionGeneral" data-parsley-validate="true">
                        <!--Empezando fila: Solicitud, Incidente SD, Ticket y Servicio  -->
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5 class="f-w-700">Solicitud:</h5>
                                <input id="solicitud" type="text" class="form-control" disabled/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <h5 class="f-w-700">Incidente SD:</h5>
                                    <div class="input-group">
                                        <input id="folio" type="number" class="form-control"  placeholder="Folio" disabled/>
                                        <span class="input-group-addon">
                                            <button id="btnAgregar" type="button" class="btn btn-success btn-xs" title="Agregar Folio"><i class="fa fa-plus"></i></button>
                                            <button id="btnEditarFolio" type="button" class="btn btn-success btn-xs hidden" title="Editar Folio"><i class="fa fa-pencil"></i></button>
                                            <button id="btnVerFolio" type="button" class="btn btn-info btn-xs hidden" title="Ver Información del Folio"><i class="fa fa-eye"></i></button>
                                            <button id="btnGuardar" type="button" class="btn btn-primary btn-xs hidden" title="Actualizar Folio"><i class="fa fa-floppy-o"></i></button>
                                            <button id="btnCancelar" type="button" class="btn btn-danger btn-xs hidden" title="Cancelar Actualización"><i class="fa fa-close"></i></button>                                            
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5 class="f-w-700">Ticket:</h5>
                                <input id="ticket" type="text" class="form-control" disabled/>                                
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5 class="f-w-700">Servicio</h5>
                                <input id="servicio" type="text" class="form-control" disabled/>                                
                            </div>
                        </div>
                        <!--Finalizando fila  -->

                        <!--Empezando fila: Solicita y Atiende  -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h5 class="f-w-700">Solicita:</h5>
                                <input id="solicita" type="text" class="form-control" disabled/>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h5 class="f-w-700">Atiende:</h5>
                                <input id="atiende" type="text" class="form-control" disabled/>                         
                            </div>
                        </div>
                        <!--Finalizando fila  -->

                        <!--Empezando fila: fecha solicitd, Fecha creación servicio y fecha inicio servicio -->
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <h5 class="f-w-700">Fecha Solicitud:</h5>
                                <input id="fechaSolicitud" type="text" class="form-control" disabled/>                                
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <h5 class="f-w-700">Fecha Creación Servicio:</h5>
                                <input id="fechaCreacion" type="text" class="form-control" disabled/>                                
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <h5 class="f-w-700">Fecha Inicio Servicio:</h5>
                                <input id="fechaInicio" type="text" class="form-control" disabled/>                                
                            </div>
                        </div>
                        <!--Finalizando fila  -->

                        <!--Empezando fila: cliente y sucursal -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="f-w-700">Cliente *</h5>
                                    <select id="selectCliente" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                        <option value="">Seleccionar</option>                                                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="f-w-700">Sucursal *</h5>
                                    <select id="selectSucursal" class="form-control" style="width: 100%" data-parsley-required="true"  disabled>
                                        <option value="">Seleccionar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila  -->

                    </form>
                    <!--Finalizando formulario -->
                </div>
                <!--Finalizando Contenido de seccion-->

            </div>
            <!--Finalizando la seccion información-->

            <!--Empezando la seccion solución-->
            <div class="tab-pane fade " id="Solucion"></div>
            <!--Finalizando la seccion solución-->

            <!--Empezando la seccion bitacora problemas-->
            <div class="tab-pane fade " id="BitacoraProblemas"></div>
            <!--Finalizando la seccion bitacora problemas-->

        </div> 
        <!-- Finalizando contenido de pestañas -->

    </div>
    <!--Finalizando panel con pestañas-->
</div>
<!-- Finalizando #contenido del servicio (version 2) --> 
