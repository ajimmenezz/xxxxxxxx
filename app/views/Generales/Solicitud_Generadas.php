<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitudes</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel solicitudes generadas-->
    <div id="tablaSolicitudes" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">            
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Solicitudes Generadas</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <!--Empezando tabla de solicitudes generadas-->
            <div>
                <div class="row">
                    <div class="col-md-12">          
                        <div class="form-group">
                            <table id="data-table-solicitudes-generadas" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Numero de solicitud</th>
                                        <th class="all">Asunto</th>
                                        <th class="all">Ticket</th>
                                        <th class="all">Departamento</th>
                                        <th class="desktop">Fecha</th>
                                        <th class="desktop">Estatus</th>
                                        <th class="all">Prioridad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['solicitudesGeneradas'] as $key => $value) {
                                        $colorBandera = '';
                                        switch ($value['IdPrioridad']) {
                                            case 0:
                                                $colorBandera = 'text-default';
                                                break;
                                            case 1:
                                                $colorBandera = 'text-danger';
                                                break;
                                            case 2:
                                                $colorBandera = 'text-warning';
                                                break;
                                            case 3:
                                                $colorBandera = 'text-success';
                                                break;
                                        }
                                        echo '<tr>';
                                        echo '<td>' . $value['Numero'] . '</td>';
                                        echo '<td>' . $value['Asunto'] . '</td>';
                                        echo '<td>' . $value['Ticket'] . '</td>';
                                        echo '<td>' . $value['Departamento'] . '</td>';
                                        echo '<td>' . $value['Fecha'] . '</td>';
                                        echo '<td>' . $value['Estatus'] . '</td>';
                                        echo '<td><i class="fa fa-2x fa-flag fa-inverse ' . $colorBandera . '"></i></td>';
                                        echo '</tr>';
                                    }
                                    ?>    
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>
            </div>
            <!--Finalizando tabla de solicitudes generadas-->         
        </div>    
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel solicitudes generadas --> 

    <!-- Empezando panel de seguimiento de solicitud generada -->
    <div id="informacionSolicitud" class="panel panel-inverse panel-with-tabs hidden" data-sortable-id="ui-unlimited-tabs-1">
        <!--Empezando Pestañas para definir el servicio-->
        <div class="panel-heading p-0">
            <div class="btn-group pull-right" data-toggle="buttons">

            </div>
            <div class="panel-heading-btn m-r-10 m-t-10">                
                <label id="btnCerrarActualizarSolicitud" class="btn btn-success btn-xs">
                    <i class="fa fa fa-reply"></i> Regresar
                </label>                                    
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <!-- begin nav-tabs -->
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">                    
                    <li class="tab-option active" id="tab-menu-Solicitud"><a href="#Solicitud" data-toggle="tab">Solicitud</a></li>                    
                    <li class="tab-option" id="tab-menu-Seguimiento"><a href="#Seguimiento" data-toggle="tab">Seguimiento</a></li>
                    <li class="tab-option" id="tab-menu-Notas"><a href="#Notas" data-toggle="tab">Conversación</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir el servicio-->
        <!--Empezando contenido de la informacion del servicio-->
        <div class="tab-content">
            <!--Empezando la seccion de generales-->
            <div class="tab-pane fade active in" id="Solicitud">
                <div class="panel-body">                    
                    <!--Empezando Formulario para actualizar solicitud -->
                    <form id="formActualizarSolicitud" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                        <!-- Empezando titulo de la solicitud -->
                        <div class="row">
                            <div class="col-md-12">          
                                <div class="form-group">
                                    <div id="actualizarSolicitud" class="m-t-10">                                
                                        <div id="numeroSolicitudInterna" class="col-md-12 text-center">
                                            <h4></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Finalizando titulo de la solicitud -->
                        <!--Empezando fila select area-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectArea">Área *</label>
                                    <select id="selectAreasSolicitud" class="form-control" name="areaSolicitud" style="width: 100%" data-parsley-required="true" >
                                        <option value="">Seleccionar</option>
                                    </select>                            
                                </div>
                            </div>
                            <!--Finalizando fila select area-->
                            <!--Empezando fila select area--> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectDepartamento">Departamento *</label>
                                    <select id="selectDepartamentoSolicitud" class="form-control" name="departamentoSolicitud" style="width: 100%" data-parsley-required="true" >
                                        <option value="">Seleccionar</option>                   
                                    </select>                            
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectPrioridad">Prioridad *</label>
                                    <select id="selectPrioridadSolicitud" class="form-control" name="prioridadSolicitud" style="width: 100%" data-parsley-required="true" >
                                        <option value="">Seleccionar</option>
                                    </select>                            
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila select area-->

                        <div class="row m-t-10">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputFolioSolicitud">Folio</label>
                                    <input type="text" class="form-control" id="inputFolioSolicitud" placeholder="Ingresa el Folio"  style="width: 100%" maxlength="100"/>                            
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectClienteSolicitudGeneradas">Cliente</label>
                                    <select id="selectClienteSolicitudGeneradas" class="form-control" name="clienteSolicitudGeneradas" style="width: 100%">
                                        <option value="">Seleccionar</option>
                                    </select>                            
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selectSucursalSolicitudGeneradas">Sucursal</label>
                                    <select id="selectSucursalSolicitudGeneradas" class="form-control" style="width: 100%" disabled>
                                        <option value="">Seleccionar</option>
                                    </select>                            
                                </div>
                            </div>
                        </div>

                        <!--Empezando fila asunto--> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputAsunto">Asunto *</label>
                                    <input type="text" class="form-control" id="inputAsuntoSolicitudGeneradas" placeholder="Ingresa el Asunto de la Solicitud" style="width: 100%" data-parsley-required="true" maxlength="100"/>                            
                                </div>
                            </div>
                        </div>    
                        <!--Finalizando fila asunto-->  

                        <!--Empezando fila descripción--> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacionesProyecto">Descripción *</label>
                                    <textarea id="textareaDescripcionSolicitud" class="form-control nuevoProyecto" name="descricpcionSolicitud" placeholder="Ingresa la descripción del problema... " rows="3" data-parsley-required="true" ></textarea>
                                </div>
                            </div>
                        </div>    
                        <!--Finalizando fila descripción-->      

                        <!--Empezando input para evidencias -->
                        <div class="row">
                            <div id="inputEvidencias" class="col-md-12"></div>
                        </div>
                        <!--Finalizando input para evidencias -->   

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha programada</label>
                                    <div id="Fecha" class="input-group date">
                                        <input id="inputProgramada"  type="text" class="form-control" placeholder="Fecha" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>                                           
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha límite de atención</label>
                                    <div id="FechaLimite" class="input-group date">
                                        <input id="inputLimiteAtencion"  type="text" class="form-control" placeholder="Fecha" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>                                           
                                </div>
                            </div>
                        </div>

                        <!--Empezando botones -->
                        <div class="row m-t-15">
                            <div class="col-md-12">
                                <div class="form-group text-center">                                    
                                    <button id="btnActualizarSolicitud" type="button" class="btn btn-sm btn-success m-r-5" >Actualizar Solicitud</button>
                                    <button id="btnCancelarSolicitud" type="button" class="btn btn-sm btn-danger m-r-5" >Cancelar Solicitud</button>
                                    <!--<button id="btnCerrarActualizarSolicitud" type="button" class="btn btn-sm btn-primary m-r-5" >Regresar</button>-->
                                </div>
                            </div>
                        </div>
                        <!--Finalizando botones -->
                    </form>    
                    <!--Finalizando Formulario para actualizar solicitud-->   
                </div>
            </div>
            <!--Finalizando la seccion de generales-->

            <!--Empezando la seccion de notas-->
            <div class="tab-pane fade in" id="Notas">
                <div class="panel-body">
                    <div class="row>">
                        <div class="col-md-12 col-xs-12">
                            <a href="javascript:;" id="btnAgregarNotaSolicitud" class="btn bg-green btn-success pull-right">
                                <i class="fa fa-plus pull-left"></i>
                                Agregar nota
                            </a>
                        </div>
                    </div>
                    <div class="row hidden" id="divFormAgregarNotaSolicitud">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <h3>Agregar nota</h3>
                                    <div class="underline"></div>
                                </div>
                            </div>
                            <form id="formAgregarNotasSolicitud">
                                <div class="row m-t-20">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Nota *</label>
                                            <textarea id="txtAgregarNotasSolicitud" class="form-control" rows="3" placeholder="Ingresa la nota ....."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="errorAgregarNotaSolicitud"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-center">
                                        <a id="btnConfirmarAgregarNotaSolicitud" class="btn btn-success" >
                                            <i class="fa fa-floppy-o"></i> Guardar Nota
                                        </a>
                                        <a id="btnCancelarAgregarNotaSolicitud" class="btn btn-danger">
                                            <i class="fa fa-ban"></i> Cancelar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!--Empezando la seccion de notas-->
                    <div id="seccionNotas" class="row m-t-15">
                        <div class="col-md-12 ">
                            <label><h4><strong>Conversaciones de la solicitud</strong></h4></label>
                            <div class="height-sm" data-scrollbar="true">
                                <ul id="listaNotas" class="media-list media-list-with-divider media-messaging"></ul>
                            </div>
                        </div>
                    </div>                
                    <!--Finalizando la seccion de notas-->
                </div>                    
            </div>
            <!--Finalizando la seccion de notas-->

            <!--Empezando la seccion de notas-->
            <div class="tab-pane fade in" id="Seguimiento">
                <div class="panel-body">
                    <div class="row" id="divSeguimiento">      
                    </div>
                </div>                    
            </div>
            <!--Finalizando la seccion de notas-->
        </div>
        <!-- Finalizando panel de seguimiento de solicitud generada -->
    </div>
    <!-- Finalizando panel de seguimiento de solicitud generada -->

    <!-- Empezando panel detalles del servicio-->
    <div id="divDetallesServicio" class="panel panel-inverse hidden">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">            
            <div class="panel-heading-btn">
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li id="btnValidarServicio" class="hidden"><a href="#" class='disabled'><i class="fa fa-check"></i> Validar Servicio</a></li>
                        <li id="btnRechazarServicio" class="hidden"><a href="#"><i class="fa fa-mail-reply-all"></i> Rechazar Servicio</a></li>
                        <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    </ul>
                </div>
                <a href="javascript:;" id="btnRegresarDetalles" class="btn btn-success btn-xs"><i class="fa fa-reply"></i> Regresar</a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                                            
            </div>
            <div class="col-md-12">
                <div class="errorDetallesServicio"></div>
            </div>
            <h4 class="panel-title">Detalles del Servicio</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">     
        </div>    
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel detalles del servicio --> 