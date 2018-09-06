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
                    <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
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
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group text-right">
                            <label><strong id="detallesServicio"><a>+ Detalles</a></strong></label>                        
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

                <!--Empezando formulario para Generales-->
                <form class="margin-bottom-0" id="formGenerales" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos del Seguimiento Personal del Proyecto</h3>
                        </div>
                    </div>
                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando--> 
                    <!--Empezando seccion para agregar eventuales al proyecto--> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventual">Eventual</label>
                                <select id="selectAsistentesProyecto" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    if (!empty($informacion['asistentes'])) {
                                        foreach ($informacion['asistentes'] as $item) {
                                            echo '<option value="' . $item['Id'] . '" data-Datos = "' . $item['ApPaterno'] . ',' . $item['ApMaterno'] . ',' . $item['NSS'] . ',' . $item['Tel1'] . ',' . $item['FechaCaptura'] . '">' . $item['Nombres'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="asistentes">&nbsp;</label>
                            <div class="form-group">
                                <a id="btnAgregaAsistente" href="javascript:;" class="btn btn-success m-r-5"><i class="fa fa-plus"></i> Agregar</a>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando seccion para agregar eventuales al proyecto--> 

                    <!--Empezando separador-->
                    <div class="row">        
                        <div class="col-md-12">        
                            <div id="errorAgregarAsistente"></div>
                        </div>  
                    </div>
                    <!--Finalizando separador-->

                    <!--Empezando la tabla de eventuales para el proyecto-->
                    <div class="row">        
                        <div class="col-md-12">        
                            <table id="data-table-eventuales-proyecto" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="none">Id</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">Apellido Paterno</th>
                                        <th class="all">Apellido Materno</th>                    
                                        <th class="all">NSS</th>
                                        <th class="all">Telefono</th>             
                                        <th class="all">Fecha de Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($informacion['asistentesProyecto'])) {
                                        foreach ($informacion['asistentesProyecto'] as $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['IdUsuario'] . '</td>';
                                            echo '<td>' . $value['Nombres'] . '</td>';
                                            echo '<td>' . $value['ApPaterno'] . '</td>';
                                            echo '<td>' . $value['ApMaterno'] . '</td>';
                                            echo '<td>' . $value['NSS'] . '</td>';
                                            echo '<td>' . $value['Tel1'] . '</td>';
                                            echo '<td>' . $value['FechaCaptura'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>  
                    </div>
                    <!--Finalizando la tabla de eventuales para el proyecto-->

                    <!--Empezando separador-->
                    <div class="row">        
                        <div class="col-md-12 m-t-10">        
                            <div class="alert alert-warning fade in">                            
                                <strong>Warning!</strong> Para eliminar el registro de la tabla solo tiene que dar click sobre fila.                                          
                            </div>  
                        </div>  
                    </div>
                    <!--Finalizando separador-->

                    <!--Empezando separador-->
                    <div class="row">        
                        <div class="col-md-12">        
                            <div id="errorAgregarAsistente"></div>
                        </div>  
                    </div>
                    <!--Finalizando separador-->

                    <!--Empezando botones generelaes del seguimiento de un servicio--> 
                    <div class="row m-t-20">
                        <div class="col-md-12 text-right">        
                            <button id="btnConcluirServicio" type="button" class="btn btn-sm btn-danger">Concluir Servicio</button>
                        </div>
                    </div>
                    <!--Finalizando botones generelaes del seguimiento de un servicio--> 
                    <!--Finalizando la seccion para el seguimiento del personal de proyecto-->
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

