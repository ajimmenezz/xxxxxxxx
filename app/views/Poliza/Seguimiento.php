<!-- Empezando #contenido -->
<div id="listaPoliza" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Seguimiento Póliza</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento póliza -->
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
            <div class="row"> 
                <!--Empezando error--> 
                <div class="col-md-12">
                    <div class="errorListaPoliza"></div>
                </div>
            </div>

            <div class="row">
                <!--Finalizando Error-->
                <div class="col-md-6">  
                    <h3 class="m-t-10">Lista General de Tickets y Tareas Pendientes</h3>
                </div>
                <div class="col-xs-12 col-md-3 text-right">
                    <a href="javascript:;" class="btn btn-info m-r-5 " id="btnMostrarServicios"><i class="fa fa-refresh"></i> Mostrar todos los tickets</a>
                </div>
                <div class="col-xs-12 col-md-3">
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

            <div class="row">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
                <!--Finalizando Separador-->
            </div> 

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
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel seguimiento poliza -->   
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de algun servicio -->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de algun servicio --> 

<!--Empezando seccion para el seguimiento de algun servicio -->
<div id="panelDetallesTicket" class="content hidden">
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Seguimiento Póliza</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right">
            <label id="btnRegresar" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div> 
    <div id="seccion-servicio-correctivo" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
        <!--Empezando Pestañas para definir la seccion-->
        <div class="panel-heading p-0">
            <div class="panel-heading-btn m-r-10 m-t-10">
                <!-- Single button -->  
                <label id="btnRegresarSeguimiento" class="btn btn-info btn-xs">
                    <i class="fa fa fa-unlock-alt"></i> Cierre
                </label> 
                <label id="btnRegresarSeguimiento" class="btn btn-danger btn-xs">
                    <i class="fa fa fa-exclamation-triangle"></i> Reportar Problema
                </label> 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <!-- begin nav-tabs -->
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#General" data-toggle="tab">Información</a></li>
                    <li class=""><a href="#EquiposInstalaciones" data-toggle="tab">Equipos</a></li>
                    <li class=""><a href="#BitacoraProblemas" data-toggle="tab">Bitácara problemas</a></li>                    
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>          
                </ul>
            </div>
        </div>
        <!--Finalizando Pestañas para definir la seccion-->

        <!--Empezando contenido de la informacion del servicio-->
        <div class="tab-content">
            <!--Empezando la seccion servicio Correctivo-->
            <div class="tab-pane fade active in" id="General">
                <div class="panel-body">

                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Información General de solicitud y servicio</h3>
                        </div>
                    </div>

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador--> 
                    <form class="margin-bottom-0" id="formServicioCorrectivo" data-parsley-validate="true">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5 class="f-w-700">Solicitud:</h5>
                                <input id="solicitud" type="text" class="form-control" disabled/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <h5 class="f-w-700">Incidente SD:</h5>
                                    <div class="input-group">
                                        <input id="folioInformacionGeneral" type="number" class="form-control"  placeholder="Folio" disabled/>
                                        <span class="input-group-addon">
                                            <button id="btnEditarFolio" type="button" class="btn btn-success btn-xs generales" title="Agregar nueva Ruta al Select"><i class="fa fa-edit"></i></button>
                                            <button id="btnEliminarFolio" type="button" class="btn btn-warning btn-xs generales" title="Agregar nueva Ruta al Select"><i class="fa fa-trash-o"></i></button>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="f-w-700">Cliente *</h5>
                                    <select id="selectCliente" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>                                                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="f-w-700">Sucursal *</h5>
                                    <div class="input-group">
                                        <select id="selectSucursal" class="form-control" style="width: 100%">
                                            <option value="">Seleccionar</option>
                                        </select>
                                        <span class="input-group-addon">
                                            <button id="btnGuardarSucursal" type="button" class="btn btn-primary btn-xs generales" title="Agregar nueva Ruta al Select"><i class="fa fa-floppy-o"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--Finalizando la seccion de servicio Correctivo-->


            <div class="tab-pane fade " id="EquiposInstalaciones">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5 class="f-w-700">Operación *</h5>
                                <select id="selectOperacionInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">                                           
                                <h5 class="f-w-700">Área de Atención *</h5>
                                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
                            </div>                               
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">                                           
                                <h5 class="f-w-700">Punto *</h5>
                                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
                            </div>                               
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5 class="f-w-700">Modelo *</h5>
                                <select id="selectModeloInstalaciones" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">                                           
                                <h5 class="f-w-700">Serie *</h5>
                                <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad"/>
                            </div>                               
                        </div>
                        <div class="col-md-2 m-t-30"> 
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="inputMultimedia" name="inputMultimedia" value="1" />
                                        Ilegible
                                    </label>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <!--Empezando error Impericia--> 
                        <div class="col-md-12">
                            <div class="errorEnviarReporteImpericia"></div>
                        </div>
                        <!--Finalizando Error Impericia-->

                        <div class="row m-t-10">
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <a id="btnAgregarEquipoInstalacion" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="data-table-equipos-instalaciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Modelo</th>
                                    <th class="all">Serie</th>
                                    <th class="all">Área</th>
                                    <th class="all">Punto</th>
                                    <th class="all">Operación</th>
                                </tr>
                            </thead>
                            <tbody>                                      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="BitacoraProblemas">
                <div class="panel-body">

                </div>
            </div>
        </div> 
    </div>
</div>
<!-- Finalizando seccion para el seguimiento de algun servicio --> 

<!--Empezando seccion para el seguimiento de un servicio sin clasificar->-->
<div id="antesYDespues" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar 