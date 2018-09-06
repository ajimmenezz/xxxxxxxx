<div id="seccion-datos-seguimiento" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir el servicio-->
    <div class="panel-heading p-0">
        <div class="btn-group pull-right" data-toggle="buttons">

        </div>
        <div class="panel-heading-btn m-r-10 m-t-10">
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar apoyo</a></li>
                </ul>
            </div>
            <label id="btnRegresarSeguimiento" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#Generales" data-toggle="tab">Información General</a></li>                
                <li class=""><a href="#Notas" data-toggle="tab">Conversación</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir el servicio-->

    <!--Empezando contenido de la informacion del servicio-->
    <div class="tab-content">

        <!--Empezando la seccion de generales-->
        <div class="tab-pane fade active in" id="Generales">
            <div class="panel-body">
                <div class="row m-r-10">
                    <div class="col-md-6">
                        <h3 class="m-t-10">Información Servicio</h3>
                    </div>
                    <div class="col-md-6 text-right">
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
                            <label><strong id="detallesServicio"><a>+ Detalles</a></strong></label>                        
                        </div>    
                    </div>
                </div>

                <div id="masDetalles" class="hidden">
                    <div class="row">
                        <div class="col-md-12">          
                            <div class="form-group">
                                <label for="seguimientoSinClasificar"> Descripción Servicio:</label>      
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
                            <h5><a><strong id="detallesFolio"><i class="fa fa-arrow-circle-down"></i> Folio</li></strong></a></h5>                        
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
                                    <a id="btnReasignarFolioServicioSinClasificar" href="javascript:;" class="btn btn-primary <?php echo $mostrarActulizarEliminarFolio; ?>"><i class="fa fa-external-link"></i> Reasignar SD</a>                            
                                </div>
                            </div> 
                        </div>
                    </form>

                    <div id="cargando" class="text-center hidden">
                        <img
                            width="200"
                            src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" />
                    </div>

                    <!-- Empezando informacion de Service Desk -->
                    <div id="seccionSD" class="alert alert-warning hidden"></div>  
                    <!-- Finalizando informacion de Service Desk -->

                </div>
                <!--Finalizando--> 

                <!--Empezando formulario para Generales-->
                <form class="margin-bottom-0" id="formGenerales" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos del Servicio Uber</h3>
                        </div>
                    </div>
                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando--> 
                    <div class="row m-t-10">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-700">Ticket</label>
                                <input type="text" class="form-control" id="txtTicket" placeholder="Ticket" style="width: 100%;" data-parsley-required="false"/>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">                                
                                <label class="f-w-700">No. Personas *</label>
                                <input type="number" class="form-control" id="txtPersonas" placeholder="Cantidad" style="width: 100%;" data-parsley-required="true"/>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">                                
                                <label class="f-w-700">Fecha del Servicio *</label>
                                <div id="fechaServicio" class="input-group date">
                                    <input id="txtFechaServicio"  type="text" class="form-control" placeholder="Fecha del Servicio" data-parsley-required="true" value=""/>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-700">Dirección de Origen *</label>
                                <input id="txtDireccionOrigen"  type="text" class="form-control" style="width: 100%;" placeholder="Dirección de Origen" data-parsley-required="true" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-700">Dirección Destino *</label>
                                <input id="txtDireccionDestino"  type="text" class="form-control" style="width: 100%;" placeholder="Dirección de Destino" data-parsley-required="true" value=""/>
                            </div>
                        </div>
                    </div>                    
                    <div class="row m-t-10">
                        <div class="col-md-8 col-xs-12">
                            <div class="form-group">
                                <label class="f-w-700">Proyecto / Motivo *</label>
                                <input id="txtMotivo"  type="text" class="form-control" style="width: 100%;" placeholder="Proyecto o Motivo del Servicio" data-parsley-required="true" value=""/>
                            </div>
                        </div>
                    </div>                    
                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorGenerales"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarGenerales" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar y Concluir Servicio</a>                            
                            </div>
                        </div>
                    </div>
                </form>
                <!--Finalizando formulario para Generales-->
            </div>
        </div>
        <!--Finalizando la seccion de generales-->

        <!--Empezando la seccion Notas-->
        <div class="tab-pane fade " id="Notas">
            <div class="panel-body">
                <?php echo $notas; ?>
            </div>
        </div>
        <!--Finalizando la seccion Notas-->
    </div>
    <!--Finalizando contenido de la informacion del servicio-->
</div>
