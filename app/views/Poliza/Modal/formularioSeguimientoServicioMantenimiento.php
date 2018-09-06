<!-- Empezando titulo de la pagina -->
<h1 class="page-header">Seguimiento Poliza</h1>
<!-- Finalizando titulo de la pagina -->
<input type="hidden" value="<?php echo $servicio; ?>" id="hiddenServicio" />
<div id="seccion-servicio-mantemiento" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir la seccion-->
    <div class="panel-heading p-0">
        <div class="panel-heading-btn m-r-10 m-t-10">
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnDocumentacionFirma"><a href="#"><i class="fa fa-pencil-square-o"></i> Firmar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar Apoyo</a></li>
                </ul>
            </div>
            <label id="btnRegresarSeguimientoMantenimiento" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#General" data-toggle="tab">Información General</a></li>
                <li class="hidden"><a href="#AntesDespues" data-toggle="tab">Antes y Después</a></li>
                <li class="hidden"><a href="#ProblemasAdicionales" data-toggle="tab">Problemas Adicionales</a></li>
                <li class=""><a href="#DocumentacionFirmada" data-toggle="tab">Documentación Firmada</a></li>
                <li class=""><a href="#Notas" data-toggle="tab">Conversación</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir la seccion-->

    <!--Empezando contenido de la informacion del servicio-->
    <div class="tab-content">

        <!--Empezando la seccion servicio Mantenimiento-->
        <div class="tab-pane fade active in" id="General">
            <div class="panel-body">
                <div class="row m-r-10">
                    <div class="col-md-7">
                        <h3 class="m-t-10">Información Servicio Mantenimiento</h3>
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
                            <label for="seguimientoMantenimiento"> Ticket: <strong><?php echo $datosServicio['Ticket']; ?></strong></label>
                        </div>    
                    </div> 
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoMantenimiento"> Atendido por: <strong><?php echo $datosServicio['NombreAtiende']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-4">          
                        <div class="form-group text-right">
                            <label for="seguimientoMantenimiento"> Fecha de Servicio: <strong><?php echo $datosServicio['FechaCreacion']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-2">          
                        <div class="form-group text-right">
                            <label for="seguimientoMantenimiento"><strong id="detallesServicioMantenimiento"><a>+ Detalles</a></strong></label>                        
                        </div>    
                    </div>
                </div>
                <div id="masDetalles" class="hidden">
                    <div class="row">
                        <div class="col-md-12">          
                            <div class="form-group">
                                <label for="seguimientoMantenimiento"> Descripción Servicio:</label>      
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
                                <label for="seguimientoMantenimiento"> Solicitud: <strong><?php echo $datosServicio['IdSolicitud']; ?></strong></label>
                            </div>    
                        </div> 
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label for="seguimientoMantenimiento"> Solicita: <strong><?php echo $datosServicio['NombreSolicita']; ?></strong></label>                        
                            </div>    
                        </div>
                        <div class="col-sm-3 col-md-4">          
                            <div class="form-group text-right">
                                <label for="seguimientoMantenimiento"> Fecha de Solicitud: <strong><?php echo $datosServicio['FechaCrecionSolicitud']; ?></strong></label>                        
                            </div>    
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if (!empty($datosServicio['descripcionSolicitud'])) {
                            ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="seguimientoMantenimiento"> Descripción Solicitud:</label>      
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

                <!--Empezando formulario servicio matenimiento datos generales-->
                <form class="margin-bottom-0" id="formServicioMantenimiento" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos del Mantenimiento</h3>
                        </div>
                    </div>

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando--> 

                    <!--Empezando Sucursal-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="seguimientoMantenimiento">Sucursal *</label>
                                <select id="selectSucursalesMantenimiento" class="form-control" style="width: 100%" data-parsley-required="true">
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

                    <!--Empezando mensaje de tabla-->
                    <div id="mensajeAdvertenciaReporteFirmado" class="row hidden">
                        <div class="col-md-12 m-t-20">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Al subir otro archivo se perderá el anterior.                             
                            </div>                        
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorDatosMantenimiento"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div id="divGuardarDatosMatenimiento" class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarDatosMantenimiento" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>                            
                            </div>
                        </div>
                        <div id="divConcluirServicioMantenimiento" class="col-md-12 hidden">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnConcluirServicioMantenimiento" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>                            
                            </div>
                        </div>
                    </div>

                </form>
                <!--Finalizando formulario servicio Mantenimiento-->

            </div>
        </div>
        <!--Finalizando la seccion de servicio mantenimiento-->

        <!--Empezando la seccion Antes y Despues-->
        <div class="tab-pane fade " id="AntesDespues">            
            <div class="panel-body">

                <!--Empezando Titulo de la tabla-->
                <div class="row m-r-10">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Puntos Censados</h3>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!--Finalizando--> 

                <!--Empezando mensaje error--> 
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <div class="errorPuntosCensados"></div>
                    </div>
                </div>   
                <!--Finalizando mensaje Error-->

                <!--Empezando la tabla de puntos censados-->
                <div class="row">                
                    <div class="col-md-12">
                        <table id="data-table-puntos-censados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                                
                                    <th class="all">Área Atención</th>
                                    <th class="all">Punto</th>
                                    <th class="all">Estatus</th>                          
                                    <th class="never">IdArea</th>                          
                                    <th class="never">IdServicio</th>                          
                                    <th class="never">IdModelo</th>                          
                                    <th class="never">IdServicio</th>                          
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($informacion['puntosCensos'])) {
                                    foreach ($informacion['puntosCensos'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Area'] . '</td>';
                                        echo '<td>' . $value['Punto'] . '</td>';
                                        echo '<td>' . $value['Estatus'] . '</td>';
                                        echo '<td>' . $value['IdArea'] . '</td>';
                                        echo '<td>' . $value['IdServicio'] . '</td>';
                                        echo '<td>' . $value['IdModelo'] . '</td>';
                                        echo '<td>' . $value['Serie'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando la tabla de puntos censados-->

            </div>
        </div>
        <!--Finalizando la seccion Datos-->

        <!--Empezando la seccion Problemas Adicionales-->
        <div class="tab-pane fade " id="ProblemasAdicionales">            
            <div class="panel-body">

                <!--Empezando formulario Problemas Adicionales-->
                <form class="margin-bottom-0" id="formProblemasAdicionales" data-parsley-validate="true">
                    <!--Empezando Titulo de formulario-->
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Problemas Adicionales</h3>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando--> 
                    <!--Empezando seleccion de campo-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="seguimientoCenso">¿En dónde se encuentra el problema? *</label>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando seleccion de campo-->
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input id="radioAreaAtencion" type="radio" name="areas" value="1"> Por Área<br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input id="radioAreaPunto" type="radio" name="areas" value="2"> Por Punto de Área<br>
                            </div>
                        </div>
                        <div class="col-md-7">
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Area de Atencion-->
                    <div id="divAreaAtencion" class="row hidden">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="seguimientoCenso">Área de Atención *</label>
                                <select id="selectAreasAtencionProblemasAdicionales" class="form-control" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['areaAtencion'] as $item) {
                                        echo '<option value="' . $item['IdArea'] . '">' . $item['Area'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Area y Punto-->
                    <div id="divAreaPunto" class="row hidden">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="seguimientoCenso">Área y Punto *</label>
                                <select id="selectAreaPuntoProblemasAdicionales" class="form-control" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['areaYPunto'] as $item) {
                                        echo '<option value="' . $item['IdArea'] . '|' . $item['Punto'] . '">' . $item['Area'] . ' ' . $item['Punto'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Decripcion-->
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label for="seguimientoMantenimiento">Descripción del Problema *</label>
                                <?php (empty($informacionDatosGenerales[0]['Descripcion'])) ? $descripcion = '' : $descripcion = $informacionDatosGenerales[0]['Descripcion']; ?>
                                <textarea id="inputDescripcionProblemasAdicionales" class="form-control " placeholder="Ingrese una descripción del Problema Adicional" rows="3" ><?php echo $descripcion; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando--> 

                    <!--Empezando Evidencias-->
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label for="seguimientoMantenimiento">Archivos o Evidencias del Problema *</label>
                                <input id="evidenciasProblemasAdicionales" name="evidenciasProblemasAdicionales[]" type="file" multiple/>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando boton de agregar equipo y mensaje de error-->
                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorFormularioProblemasAdicionales"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnAgregarProblemasAdicionales" href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar Problema</a>                            
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                </form>
                <!--Finalizando-->

                <!--Empezando Titulo de formulario-->
                <div class="row m-r-10">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Lista de Problemas Adicionales</h3>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!--Finalizando--> 

                <div class="table-responsive">
                    <table id="data-table-problemas-adicionales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>                                
                                <th class="all">Área Atención</th>
                                <th class="all">Punto</th>
                                <th class="all">Problema</th>                             
                                <th class="none">Evidencias</th>                             
                                <th class="never">IdArea</th>                             
                                <th class="all">Acciones</th>                             
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($informacion['problemasAdicionales'])) {
                                foreach ($informacion['problemasAdicionales'] as $key => $value) {
                                    $evidencias = explode(',', $value['Evidencias']);
                                    if ($value['Punto'] === '0') {
                                        $value['Punto'] = '-';
                                    }
                                    echo '<tr>';
                                    echo '<td>' . $value['Sucursal'] . '</td>';
                                    echo '<td>' . $value['Punto'] . '</td>';
                                    echo '<td>' . $value['Descripcion'] . '</td>';
                                    echo '<td>';
                                    foreach ($evidencias as $key => $valor) {
                                        echo '<a href="' . $valor . '" target="_blank"> <img src="' . $valor . '" title="" style="max-height:150px"/> </a>';
                                    }
                                    echo '</td>';
                                    echo '<td>' . $value['Id'] . '</td>';
                                    echo '<td><a id="btnEliminarProblemaAdicional' . $value['Id'] . '" onclick="eventoEliminarProblemaAdicional(' . $value['Id'] . ',' . $value['IdServicio'] . ');" class="btn btn-danger btn-xs "><i class="fa fa-trash-o"></i> Eliminar</a> </td>';
                                    echo '</tr>';
                                }
                            }
                            ?>                                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Problemas Adicionales-->

        <!--Empezando la seccion Documentacion Firmada-->
        <div class="tab-pane fade " id="DocumentacionFirmada">            
            <div class="panel-body">
                <!--Empezando Titulo de formulario-->
                <div class="row m-r-10">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Lista de Documentos Firmados</h3>
                    </div>
                </div>
                <!--Finalizando-->

                <!--Empezando Separador-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>
                </div>
                <!--Finalizando--> 

                <div class="table-responsive">
                    <table id="data-table-documetacion-firmada" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>                                
                                <th class="all">Fecha</th>
                                <th class="all">Recibe</th>
                                <th class="all">Correos</th>                             
                                <th class="all">Estatus</th>                                                       
                                <th class="all">PDF</th>                                                       
                            </tr>
                        </thead>
                        <tbody>                                     
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Documentacion Firmada-->

        <!--Empezando la seccion Notas-->
        <div class="tab-pane fade " id="Notas">            
            <div class="panel-body">
                <?php echo $notas; ?>
            </div>
        </div>
        <!--Finalizando la seccion Notas-->
    </div>
</div>