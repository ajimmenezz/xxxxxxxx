<!-- Empezando titulo de la pagina -->
<div class="row">
    <div class="col-md-6 col-xs-6">
        <h1 class="page-header">Seguimiento Salas X4D</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>   
                <li id="btnconcluirServicio"><a href="#"><i class="fa fa-check-circle"></i> Concluir servicio</a></li> 
                <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar Apoyo</a></li>                
            </ul>
        </div>
        <label id="btnRegresarSeguimientoMantenimientoSalas" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<!-- Finalizando titulo de la pagina -->
<div id="seccion-servicio-mantto-salas" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir la seccion-->
    <div class="panel-heading p-0">
        <div class="panel-heading-btn m-r-10 m-t-10">
            <!-- Single button -->                                  
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#General" data-toggle="tab">Información General</a></li>
                <li class="hidden"><a href="#DefinicionActividades" data-toggle="tab">Definición de Actividades</a></li>
                <li class="hidden"><a href="#AsignacionActividades" data-toggle="tab">Asignación de Actividades</a></li>
                <li class=""><a href="#ActividadesAsignadas" data-toggle="tab">Actividades Asignadas</a></li>
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
                    <div class="col-md-7">
                        <h3 class="m-t-10">Información General de Servicio</h3>
                    </div>
                    <div class="col-md-5 text-right">
                        <?php
                        if (!empty($datosServicio['Folio'])) {
                            ?>
                            <h5 id='folioSeguimiento' class="m-t-20"> Folio <a  TITLE="Muestra la informacion de Service Desk"><?php echo $datosServicio['Folio']; ?></a></h5>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador--> 

                <!--Empezando informacion del servicio-->
                <div class="row">
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label> Ticket: <strong><?php echo $datosServicio['Ticket']; ?></strong></label>
                        </div>    
                    </div> 
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label> Atendido por: <strong><?php echo $datosServicio['NombreAtiende']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-4">          
                        <div class="form-group text-right">
                            <label> Fecha de Servicio: <strong><?php echo $datosServicio['FechaCreacion']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-2">          
                        <div class="form-group text-right">
                            <label><strong id="detallesServicioPreventivoSalas4xd"><a>+ Detalles</a></strong></label>                        
                        </div>    
                    </div>
                </div>
                <div id="masDetalles" class="hidden">
                    <div class="row">
                        <div class="col-md-12">          
                            <div class="form-group">
                                <label> Descripción Servicio:</label>      
                                <br>
                                <strong><?php echo $datosServicio['DescripcionServicio']; ?></strong>
                            </div>    
                        </div>
                    </div>

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador--> 

                    <div class="row">
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label> Solicitud: <strong><?php echo $datosServicio['IdSolicitud']; ?></strong></label>
                            </div>    
                        </div> 
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label> Solicita: <strong><?php echo $datosServicio['NombreSolicita']; ?></strong></label>                        
                            </div>    
                        </div>
                        <div class="col-sm-3 col-md-4">          
                            <div class="form-group text-right">
                                <label> Fecha de Solicitud: <strong><?php echo $datosServicio['FechaCrecionSolicitud']; ?></strong></label>                        
                            </div>    
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if (!empty($datosServicio['descripcionSolicitud'])) {
                            ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Descripción Solicitud:</label>      
                                    <br>
                                    <strong><?php echo $datosServicio['descripcionSolicitud']; ?></strong>
                                </div>    
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!--Finalizando informacion del servicio-->

                <!--Empezando seccion de Folio-->
                <div class="row">
                    <div class="col-md-offset-9 col-md-3">          
                        <div class="form-group text-right">
                            <h5><a><strong id="detallesFolio"><i class="fa fa-arrow-circle-down"></i> Folio</strong></a></h5>                        
                        </div>    
                    </div>
                </div>

                <div id="masDetallesFolio" class="hidden">
                    <form id="formFolioSinClasificar" data-parsley-validate="true">
                        <?php
                        if ($folio[0]['Folio'] === NULL || $folio[0]['Folio'] === '0') {
                            $tituloFolio = 'Sin Folio';
                            $folioTexto = '';
                            $mostrarGuardarFolio = '';
                            $mostrarActulizarEliminarFolio = 'hidden';
                        } else {
                            $tituloFolio = 'Cuenta con Folio';
                            $folioTexto = $folio[0]['Folio'];
                            $mostrarGuardarFolio = 'hidden';
                            $mostrarActulizarEliminarFolio = '';
                        }
                        ?>

                        <div class="row m-r-10">
                            <div class="col-md-6">
                                <h3 class="m-t-10"><div id="tituloFolio"><?php echo $tituloFolio; ?></div></h3>
                            </div>
                            <!--Empezando Separador-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>
                            <!--Finalizando Separador--> 
                        </div>

                        <div class="row">
                            <!--Empezando error--> 
                            <div class="col-md-12">
                                <div class="errorFolioSolicitudSinClasificar"></div>
                            </div>
                            <!--Finalizando Error-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Folio</label>
                                    <input id="inputFolioServicioSinClasificar" type="text" class="form-control" placeholder="<?php echo $folioTexto; ?>" data-parsley-type="number"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-left m-t-1">
                                    <a id="btnGuardarFolioServicioSinClasificar" href="javascript:;" class="btn btn-primary m-t-20 <?php echo $mostrarGuardarFolio; ?>"><i class="fa fa-save"></i> Guardar</a>                            
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-left m-t-5">
                                    <a id="btnActualizarFolioServicioSinClasificar" href="javascript:;" class="btn btn-success <?php echo $mostrarActulizarEliminarFolio; ?>"><i class="fa fa-pencil"></i> Actualizar</a>                            
                                    <a id="btnEliminarFolioServicioSinClasificar" href="javascript:;" class="btn btn-danger <?php echo $mostrarActulizarEliminarFolio; ?>"><i class="fa fa-eraser"></i> Eliminar</a>                            
                                </div>
                            </div> 
                        </div>
                    </form>

                    <!-- Empezando informacion de Service Desk -->
                    <div id="seccionSD" class="alert alert-warning hidden"></div>  
                    <!-- Finalizando informacion de Service Desk -->

                </div>

                <!--Empezando formulario servicio preventivo datos generales-->
                <form class="margin-bottom-0" id="formServicioPreventivoSalas4xd" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos</h3>
                        </div>
                    </div>

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando--> 

                    <!--Empezando Sucursal y Area y Punto-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectSucursalesPreventivo">Sucursal *</label>
                                <select id="selectSucursalesPreventivo" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['sucursales'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <!--Finalizando-->

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div id="errorDatosPreventivoSalas4xd"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarDatosPreventivoSalas4xd" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>                            
                            </div>
                        </div>
                        <div id="divConcluirServicioCorrectivo" class="col-md-12 hidden">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnConcluirServicioCorrectivo" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>                            
                            </div>
                        </div>
                    </div>

                </form>
                <!--Finalizando formulario servicio Preventivo -->

            </div>
        </div>
        <!--Finalizando la seccion de servicio Correctivo-->

        <!--Empezando la seccion Diagonostico del Equipo-->
        <div class="tab-pane fade " id="DefinicionActividades">            
            <div class="panel-body">

                <!-- Empezando Titulo de Pestaña -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Definicion de Actividades</h3>
                    </div>
                </div>
                <!-- Finalizando Titulo de Pestaña -->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!--Finalizando Separador-->

                <div class="row">                            
                    <div class="col-md-12">
                        <div class="panel panel-inverse" data-sortable-id="tree-view-3">
                            <div class="panel-heading">
                                <div class="panel-heading-btn"></div>
                                <h4 class="panel-title">Actividades de Mantenimiento </h4>
                            </div>                                    
                            <div class="panel-body">                                        
                                <div id="jstree-default"></div>
                            </div>
                        </div>
                    </div>                                                       
                </div>  
                
                <!--Empezando error--> 
                <div class="row">                    
                    <div class="col-md-12">
                        <div class="errorDefinicionActividades"></div>
                    </div>
                </div>
                <!--Terminando error--> 

                <!--Empezando botones para guardar entrega-->
                <div class="row">
                    <div class="col-md-12 text-center m-t-20">
                        <button id="btnGuardarActividades" type="button" class="btn btn-sm btn-primary entregaGarantia"><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                    </div>
                </div>
                <!--Finalizando botones para guardar entrega-->

            </div>

        </div>
        <!--Finalizando la seccion Diagnostico del Equipo-->


        <!--Empezando la seccion Asignacion de Actividades-->
        <div class="tab-pane fade " id="AsignacionActividades">            
            <div class="panel-body">
            </div>
        </div>
        <!-- end col-6 -->

        <!--Empezando la seccion Acctidades Asignadas-->
        <div class="tab-pane fade " id="ActividadesAsignadas">  
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table id="data-table-actividades-asignadas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Actividad</th>
                                <th class="all">Actividad Padre</th>
                                <th class="all">Atiende</th>
                                <th class="all">Fecha de Asignación</th>
                                <th class="all">Estatus</th>
                                <th class="never">IdSistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($informacion['actividades'])) {
                                foreach ($informacion['actividades'] as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['IdManttoActividades'] . '</td>';
                                    echo '<td>' . $value['Actividad'] . '</td>';
                                    echo '<td>' . $value['ActividadPadre'] . '</td>';
                                    echo '<td>' . $value['NombreAtiende'] . '</td>';
                                    echo '<td>' . $value['Fecha'] . '</td>';
                                    echo '<td>' . $value['Estatus'] . '</td>';
                                    echo '<td>' . $value['IdSistema'] . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>   
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
