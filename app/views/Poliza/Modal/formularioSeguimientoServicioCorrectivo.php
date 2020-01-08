<!-- Empezando titulo de la pagina -->
<div class="row">
    <div class="col-md-6 col-xs-6">
        <h1 class="page-header">Seguimiento Póliza</h1>
        <input type="hidden" value="<?php echo $servicio; ?>" id="hiddenServicio" />
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li id="btnAgregarAvance"><a href="#"><i class="fa fa-plus"></i> Agregar Avance</a></li>
                <li id="btnAgregarProblema"><a href="#"><i class="fa fa-plus"></i> Agregar Problema</a></li>
                <?php echo $informacion['botonAgregarVuelta'] ?>
                <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                <li id="btnEnviarReporteProblema"><a href="#"><i class="fa fa-check-square"></i> Enviar Reporte con Firma</a></li>
                <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar Apoyo</a></li>
                <li id="btnSubirInformacionSD"><a href="#"><i class="fa fa-cloud-upload"></i> Subir Información SD</a></li>
            </ul>
        </div>
        <label id="btnRegresarSeguimientoCorrectivo" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<!-- Finalizando titulo de la pagina -->
<div id="seccion-servicio-correctivo" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
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
                <li class=""><a href="#DiagnosticoEquipo" data-toggle="tab">Diagnóstico del Equipo</a></li>
                <li class=""><a href="#ProblemasServicio" data-toggle="tab">Problemas del Servicio</a></li>
                <li class=""><a href="#Solucion" data-toggle="tab">Solución</a></li>
                <li class=""><a href="#Historial" data-toggle="tab">Historial</a></li>
                <li class=""><a href="#Notas" data-toggle="tab">Conversación</a></li>
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
                        <h3 class="m-t-10">Información Servicio Correctivo</h3>
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
                            <label><strong id="detallesServicioCorrectivo"><a>+ Detalles</a></strong></label>                        
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

                <!--Empezando formulario servicio correctivo datos generales-->
                <form class="margin-bottom-0" id="formServicioCorrectivo" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos del Correctivo</h3>
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
                                <label for="selectSucursalesCorrectivo">Sucursal *</label>
                                <select id="selectSucursalesCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['sucursales'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectAreaPuntoCorrectivo">Área y Punto *</label>
                                <select id="selectAreaPuntoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Equipo-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectEquipoCorrectivo">Equipo *</label>
                                <select id="selectEquipoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                    <option data-serie="" data-terminal="" value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <!-- Se Oculta por si posteriormente se puede utilizar -->
                        <!--                        <div class="col-md-6 hidden">          
                                                    <div class="form-group text-left">
                                                        <br>
                                                        <h5 id="camposExtrasCorrectivo"><a><strong> Clic aquí si el equipo no está en el registro</strong></a></h5>                        
                                                    </div>    
                                                </div>-->
                        <div class="form-group">
                            <div class="col-md-6 m-t-20">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="inputMultimedia" name="inputMultimedia" value="1" />
                                        Multimedia
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Serie e Numero de termina-->
                    <div id="divCamposExtraCorrectivo" class="row hidden"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputSerieCorrectivo">Serie *</label>
                                <input type="text" class="form-control" id="inputSerieCorrectivo" placeholder="Serie" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputNumeroTerminalCorrectivo">Número de Terminal *</label>
                                <input type="text" class="form-control" id="inputNumeroTerminalCorrectivo" placeholder="ABCDFG99" style="width: 100%" data-parsley-required="true"/>                            
                            </div>
                        </div>
                    </div>
                    <!--Finalizando--> 

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorDatosCorrectivo"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div id="divGuardarDatosCorrectivo" class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarDatosCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>                            
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
                <!--Finalizando formulario servicio Correctivo -->

            </div>
        </div>
        <!--Finalizando la seccion de servicio Correctivo-->

        <!--Empezando la seccion Diagonostico del Equipo-->
        <div class="tab-pane fade " id="DiagnosticoEquipo">            
            <div class="panel-body">

                <!-- Empezando Titulo de Pestaña -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Diagnóstico del Equipo</h3>
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

                <div class="row m-b-15">
                    <div class="col-md-12">
                        <label>Falla reportada en sitio *</label>
                        <input id="inputFallaReportadaDiagnostico" type="text" class="form-control"  placeholder="Ingrese la persona que recibe" value="" maxlength="250"/>
                    </div>
                </div>


                <ul class="nav nav-pills">
                    <li class="active"><a href="#reporte-falso" data-toggle="tab">Reporte en Falso</a></li>
                    <li><a href="#impericia" data-toggle="tab">Impericia (Mal uso)</a></li>
                    <li><a href="#falla-equipo" data-toggle="tab">Falla de Equipo</a></li>
                    <li><a href="#falla-componente" data-toggle="tab">Falla de Componente</a></li>
                    <li><a href="#reporte-multimedia" data-toggle="tab">Reporte Multimedia</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="reporte-falso">
                        <div class="well">

                            <!-- Empezando Evidencias Reporte en Falso -->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasReporteFalsoCorrectivo">Evidencias del Equipo Funcionando *</label>
                                        <input id="evidenciasReporteFalsoCorrectivo"  name="evidenciasReporteFalsoCorrectivo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioReporteFalsoCorrectivo"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarReporteFalsoCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar y Concluir Servicio</a>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <a href="javascript:;" id="btnAgregarObservacionesReporteFalso" class="btn bg-green btn-success pull-right">
                                        <i class="fa fa-plus pull-left"></i>
                                        Agregar Observación
                                    </a>
                                </div>
                            </div>
                            <div class="hidden" id="divFormAgregarObservaciones">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <h3>Agregar Observación</h3>
                                        <div class="underline"></div>
                                    </div>
                                </div>
                                <form id="formAgregarObservacionesReporteFalso">
                                    <div class="row m-t-20">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Observación *</label>
                                                <textarea id="txtAgregarObservacion" class="form-control" rows="3" placeholder="Ingresa la observación ....."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Agregar Archivos o Imagenes</label>
                                                <input id="archivosAgregarObservacionesReporteFalso"  name="archivosAgregarObservacionesReporteFalso[]" type="file" multiple/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="errorAgregarObservacionesReporteFalso"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 text-center">
                                            <a id="btnConfirmarAgregarObservacionesReporteFalso" class="btn btn-primary" >
                                                <i class="fa fa-floppy-o"></i> Guardar
                                            </a>
                                            <a id="btnCancelarAgregarObservacionesReporteFalso" class="btn btn-danger">
                                                <i class="fa fa-ban"></i> Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row m-t-15">
                                <div class="col-md-12">                            
                                    <fieldset>
                                        <legend class="pull-left width-full f-s-17">Bitácora Observaciones del Diagnóstico.</legend>
                                    </fieldset> 
                                </div>
                            </div>
                            <div id="divBitacoraReporteFalso">
                                <?php echo $bitacoraReporteFalso; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="impericia">
                        <div class="well">

                            <div class="mensajeImpericia row hidden">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        No existen registros de Impericia para el equipo en el Catálogo de Tipo de Falla.                             
                                    </div>                        
                                </div>
                            </div>

                            <!--Empezando Tipo de Falla y Falla-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectImpericiaTipoFallaEquipoCorrectivo">Tipo de Falla *</label>
                                        <select id="selectImpericiaTipoFallaEquipoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if ($informacion['tiposFallasEquiposImpericia'] !== FALSE) {
                                                if ($informacion['tiposFallasEquiposImpericia'] !== NULL) {
                                                    foreach ($informacion['tiposFallasEquiposImpericia'] as $item) {
                                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectImpericiaFallaDiagnosticoCorrectivo">Falla *</label>
                                        <select id="selectImpericiaFallaDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Evidencias Impericia-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasImpericiaCorrectivo">Evidencias de la Impericia *</label>
                                        <input id="evidenciasImpericiaCorrectivo"  name="evidenciasImpericiaCorrectivo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Observaciones Imepericia-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesImpericiaCorrectivo">Observaciones del Servicio *</label>
                                        <textarea id="inputObservacionesImpericiaCorrectivo" class="form-control " placeholder="Observaciones del diagnóstico de impericia." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioImpericiaCorrectivo"></div>
                                </div>
                                <!--Finalizando Error-->

                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarImpericiaCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="falla-equipo">
                        <div class="well">

                            <div class="mensajeTipoFalla row hidden">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        No existe registros en Catálogo Tipos de Fallas.                             
                                    </div>                        
                                </div>
                            </div>

                            <!--Empezando Tipo de Falla y Falla-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectTipoFallaEquipoCorrectivo">Tipo de Falla *</label>
                                        <select id="selectTipoFallaEquipoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if ($informacion['tiposFallasEquipos'] !== FALSE) {
                                                if ($informacion['tiposFallasEquipos'] !== NULL) {
                                                    foreach ($informacion['tiposFallasEquipos'] as $item) {
                                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectFallaDiagnosticoCorrectivo">Falla *</label>
                                        <select id="selectFallaDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Evidencias Fallas de Equipo-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasFallaEquipoCorrectivo">Evidencias de la Falla *</label>
                                        <input id="evidenciasFallaEquipoCorrectivo"  name="evidenciasFallaEquipoCorrectivo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Evidencias Fallas de Equipo-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesFallaEquipoCorrectivo">Observaciones del Servicio *</label>
                                        <textarea id="inputObservacionesFallaEquipoCorrectivo" class="form-control " placeholder="Observaciones del diagnóstico de falla." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioFallaEquipoCorrectivo"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarFallaEquipoCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="falla-componente">
                        <div class="well">

                            <div class="mensajeRefaccion row hidden">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        No existe registros en Catálogo Refacciones.                             
                                    </div>                        
                                </div>
                            </div>

                            <!--Empezando Tipo de Falla y Falla-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectComponenteDiagnosticoCorrectivo">Componente *</label>
                                        <select id="selectComponenteDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if ($informacion['catalogoComponentesEquipos'] !== FALSE) {
                                                if ($informacion['catalogoComponentesEquipos'] !== NULL) {
                                                    foreach ($informacion['catalogoComponentesEquipos'] as $item) {
                                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectTipoFallaComponenteCorrectivo">Tipo de Falla *</label>
                                        <select id="selectTipoFallaComponenteCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectFallaComponenteDiagnosticoCorrectivo">Falla *</label>
                                        <select id="selectFallaComponenteDiagnosticoCorrectivo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!-- Empezando Evidencias Falla de Componente -->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasFallaComponenteCorrectivo">Evidencias de la Falla *</label>
                                        <input id="evidenciasFallaComponenteCorrectivo"  name="evidenciasFallaComponenteCorrectivo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!-- Finalizando -->

                            <!-- Empezando Observaciones Falla de Componente -->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesFallaComponenteCorrectivo">Observaciones del Servicio *</label>
                                        <textarea id="inputObservacionesFallaComponenteCorrectivo" class="form-control " placeholder="Observaciones del diagnóstico de falla." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioFallaComponenteCorrectivo"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarFallaComponenteCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Diagnóstico</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="reporte-multimedia">
                        <div class="well">

                            <!-- Empezando Evidencias Reporte Multimedia -->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasReporteMultimediaCorrectivo">Evidencias *</label>
                                        <input id="evidenciasReporteMultimediaCorrectivo"  name="evidenciasReporteMultimediaCorrectivo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Obervaciones Reporte Mutlimedia-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesReporteMultimediaCorrectivo">Observaciones del Servicio *</label>
                                        <textarea id="inputObservacionesReporteMultimediaCorrectivo" class="form-control " placeholder="Observaciones del diagnóstico de reporte para multimedia." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioReporteMultimediaCorrectivo"></div>
                                </div>
                                <!--Finalizando Error-->
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarReporteMultimediaCorrectivo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar y Concluir Servicio</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Diagnostico del Equipo-->

        <!--Empezando la seccion Problemas Servicio-->
        <div class="tab-pane fade " id="ProblemasServicio">            
            <div class="panel-body">
                <!-- Empezando Titulo de Pestaña -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Problemas del Servicio</h3>
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

                <ul class="nav nav-pills">
                    <li class="active"><a href="#solicitud-refaccion" data-toggle="tab">Solicitud de Refacción</a></li>
                    <li><a href="#solicitud-equipo" data-toggle="tab">Solicitud de Equipo</a></li>
                    <li><a href="#equipo-garantia" data-toggle="tab">Equipo a Garantía</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="solicitud-refaccion">
                        <div class="well">

                            <!--Empezando formulario Solicitud de Refaccion-->
                            <form class="margin-bottom-0" id="formSolicitudRefaccion" data-parsley-validate="true">
                                <div class="row m-r-10">
                                    <div class="col-md-12">
                                        <h3 class="m-t-10">Solicitar Refacciones</h3>
                                    </div>
                                </div>

                                <!--Empezando Separador-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                                <!--Finalizando--> 

                                <div class="mensajeRefaccion row hidden">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            No existe registros en Catálogo Refacciones.                             
                                        </div>                        
                                    </div>
                                </div>

                                <!--Empezando Refaccion, Cantidad-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectRefaccionSolicitud">Refacción *</label>
                                            <select id="selectRefaccionSolicitud" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                if ($informacion['catalogoComponentesEquipos'] !== FALSE) {
                                                    if ($informacion['catalogoComponentesEquipos'] !== NULL) {
                                                        foreach ($informacion['catalogoComponentesEquipos'] as $item) {
                                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">                                           
                                            <label for="inputCantidadRefaccionSolicitud">Cantidad *</label>
                                            <input id="inputCantidadRefaccionSolicitud" type="number" class="form-control"  placeholder="Cantidad" disabled/>
                                        </div>                               
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <br>
                                            <a id="btnAgregarSolicitudRefaccion" href="javascript:;" class="btn btn-success m-t-4 "><i class="fa fa-plus"></i> Agregar</a>                            
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <div class="table-responsive">
                                    <table id="data-table-solicitud-refacciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                
                                                <th class="never">IdRefaccion</th>
                                                <th class="all">Refacción</th>
                                                <th class="all">Cantidad</th>                                                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <!--Empezando mensaje de tabla-->
                                <div class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                                        </div>                        
                                    </div>
                                </div>
                                <!--Finalizando mensaje de tabla-->

                                <div class="row m-t-10">

                                    <!--Empezando error--> 
                                    <div class="col-md-12">
                                        <div class="errorRefaccionSolicitud"></div>
                                    </div>
                                    <!--Finalizando Error-->
                                </div>

                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <label>
                                            <input type="radio" name="radioSolicitar" value="almacen"/> Solicitar a Almacén
                                        </label>
                                    </div>

                                    <div class="col-md-4 col-sm-4">
                                        <label>
                                            <input  type="radio" name="radioSolicitar" value="ti" /> Solicitar TI
                                        </label>
                                    </div>

                                    <div class="col-md-3 col-sm-3">
                                        <label>
                                            <input type="radio" name="radioSolicitar" value="multimedia" /> Asignar a Multimedia
                                        </label>
                                    </div>
                                </div>


                                <div class="row m-t-10">
                                    <!--Empezando error Impericia--> 
                                    <div class="col-md-12">
                                        <div class="errorEnviarReporteImpericia"></div>
                                    </div>
                                    <!--Finalizando Error Impericia-->
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <a id="btnGuardarSolicitudRefaccion" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Solicitar Refacciones</a>                            
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <!--Finalizando formulario Solicitud de Refaccion-->

                            <!--Empezando Titulo de formulario-->
                            <div class="row m-r-20">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Solicitudes de Refacción</h3>
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
                                <table id="data-table-servicios-solicitudes-refacciones" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th class="never">IdServicio</th>
                                            <th class="all">Solicitante</th>
                                            <th class="all">Fecha de Solicitud</th>
                                            <th class="all">Refacciones</th>                             
                                            <th class="all">Estatus</th>                                                      
                                            <th class="never">IdSolicitudes</th>                                                      
                                        </tr>
                                    </thead>
                                    <tbody>                                      
                                    </tbody>
                                </table>
                            </div>

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorTablaSolicitudes"></div>
                                </div>
                                <!--Finalizando Error-->
                            </div>

                            <div class="row">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        Para eliminar un registro seleccione la solicitud.                            
                                    </div>                        
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="solicitud-equipo">
                        <div class="well">

                            <!--Empezando formulario Solicitud de Equipo-->
                            <form class="margin-bottom-0" id="formSolicitudEquipo" data-parsley-validate="true">
                                <div class="row m-r-10">
                                    <div class="col-md-12">
                                        <h3 class="m-t-10">Solicitar Equipos</h3>
                                    </div>
                                </div>

                                <!--Empezando Separador-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                                <!--Finalizando--> 

                                <!--Empezando Refaccion, Cantidad-->
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="selectEquipoSolicitud">Equipo *</label>
                                            <select id="selectEquipoSolicitud" class="form-control" style="width: 100%" disabled>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                if ($informacion['EquiposXLinea'] !== FALSE) {
                                                    if ($informacion['EquiposXLinea'] !== NULL) {
                                                        foreach ($informacion['EquiposXLinea'] as $item) {
                                                            echo '<option value="' . $item['IdMod'] . '">' . $item['Linea'] . ' - ' . $item['Marca'] . ' - ' . $item['Modelo'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <br>
                                            <a id="btnAgregarSolicitudEquipo" href="javascript:;" class="btn btn-success m-t-4 "><i class="fa fa-plus"></i> Agregar</a>                            
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <div class="table-responsive">
                                    <table id="data-table-solicitud-equipos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                
                                                <th class="never">IdEquipo</th>
                                                <th class="all">Equipo</th>
                                                <th class="all">Cantidad</th>                                                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <!--Empezando mensaje de tabla-->
                                <div class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                                        </div>                        
                                    </div>
                                </div>
                                <!--Finalizando mensaje de tabla-->

                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <label>
                                            <input type="radio" name="radioSolicitar" value="almacen"/> Solicitar a Almacén.
                                        </label>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <label>
                                            <input type="radio" name="radioSolicitar" value="ti" /> Solicitar TI.
                                        </label>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <label>
                                            <input type="radio" name="radioSolicitar" value="multimedia" /> Asignar a Multimedia
                                        </label>
                                    </div>
                                </div>

                                <div class="row m-t-10">
                                    <!--Empezando error--> 
                                    <div class="col-md-12">
                                        <div class="errorEquipoSolicitud"></div>
                                    </div>
                                    <!--Finalizando Error-->

                                    <!--Empezando error Impericia--> 
                                    <div class="col-md-12">
                                        <div class="errorEnviarReporteImpericia"></div>
                                    </div>
                                    <!--Finalizando Error Impericia-->

                                    <div class="row m-t-10">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <a id="btnGuardarSolicitudEquipo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Solicitar Equipos</a>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <!--Finalizando formulario Solicitud de Equipo-->

                            <!--Empezando Titulo de formulario-->
                            <div class="row m-r-20">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Solicitudes de Equipo</h3>
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
                                <table id="data-table-servicios-solicitudes-equipos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th class="never">IdServicio</th>
                                            <th class="all">Solicitante</th>
                                            <th class="all">Fecha de Solicitud</th>
                                            <th class="all">Equipo(s)</th>                             
                                            <th class="all">Estatus</th>                                                      
                                            <th class="never">IdSolicitudes</th>                                                      
                                        </tr>
                                    </thead>
                                    <tbody>                                     
                                    </tbody>
                                </table>
                            </div>

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorTablaSolicitudes"></div>
                                </div>
                                <!--Finalizando Error-->
                            </div>

                            <div class="row">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        Para eliminar un registro seleccione la solicitud.                            
                                    </div>                        
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="equipo-garantia">
                        <div class="well">

                            <!--Empezando Titulo de formulario-->
                            <div class="row m-r-10">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Información del equipo de respaldo</h3>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Separador-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorInformacionRespaldo"></div>
                                </div>
                                <!--Finalizando Error-->
                            </div>
                            <!--Finalizando--> 

                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <label>
                                        <input id="dejarEquipoRespaldo" type="radio" name="radioEquipoRespaldo" value="dejar"/> Dejar equipo de respaldo.
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-6">
                                    <label>
                                        <input id="noSeCuentaEquipoRespaldo" type="radio" name="radioEquipoRespaldo" value="noSeCuenta" /> No se cuenta con equipo de respaldo.
                                    </label>
                                </div>
                            </div>

                            <!--Empezando Dejar Equipo-->
                            <div id="dejarEquipoGarantia" class="hidden">
                                <div class="row m-t-20">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectEquipoRespaldo">Equipo *</label>
                                            <select id="selectEquipoRespaldo" class="form-control" style="width: 100%" disabled>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                if ($informacion['EquiposXLinea'] !== FALSE) {
                                                    if ($informacion['EquiposXLinea'] !== NULL) {
                                                        foreach ($informacion['EquiposXLinea'] as $item) {
                                                            echo '<option value="' . $item['IdMod'] . '">' . $item['Linea'] . ' - ' . $item['Marca'] . ' - ' . $item['Modelo'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputSerieRespaldo">Serie *</label>
                                            <input type="text" class="form-control" id="inputSerieRespaldo" placeholder="Serie" style="width: 100%" data-parsley-required="true" disabled/>                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <!--Empezando error--> 
                                    <div class="col-md-12">
                                        <div class="errorEquipoRespaldo"></div>
                                    </div>
                                    <!--Finalizando Error-->

                                    <!--Empezando error Impericia--> 
                                    <div class="col-md-12">
                                        <div class="errorEnviarReporteImpericia"></div>
                                    </div>
                                    <!--Finalizando Error Impericia-->

                                    <div class="row m-t-10">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <a id="btnGuardarInformacionGarantia" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>                            
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--Finalizando-->

                            <div id="noEquipoGarantia" class="hidden">
                                <div class="row m-t-10">
                                    <div class="col-md-9 col-sm-12 col-md-offset-3">
                                        <a id="btnAutorizadoSinRespaldo" href="javascript:;" class="btn btn-warning btn-sm m-r-5 disabled"><i class="fa fa-check-square-o"></i> Autorizado sin Respaldo</a>                            
                                        <a id="btnSolicitarEquipoRespaldo" href="javascript:;" class="btn btn-primary btn-sm m-r-5 disabled"><i class="fa fa-external-link"></i> Solicitar Equipo de Respaldo</a>                            
                                    </div>
                                </div>
                                <div id="mensajebotonesEquipoRespaldo" class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Para habilitar los botones es necesario guardar los datos de la pestaña Información General.                             
                                        </div>                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="informacionAutorisacionSinRespaldo" class="hidden">
                            <div class="well">

                                <!--Empezando Titulo de formulario-->
                                <div class="row m-r-10">
                                    <div class="col-md-12">
                                        <h3 class="m-t-10">Autorización sin Respaldo</h3>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">          
                                        <div class="form-group">
                                            <label> Autoriza:</label>      
                                            <br>
                                            <?php (empty($informacion['correctivoGarantiaRespaldo'][0]['Autoriza'])) ? $AutorizaRespaldo = '' : $AutorizaRespaldo = $informacion['correctivoGarantiaRespaldo'][0]['Autoriza']; ?>
                                            <strong id="divAutoriza"><?php echo $AutorizaRespaldo; ?></strong>
                                        </div>  
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">          
                                        <div class="form-group">
                                            <label> Evidencia de Autorización:</label>
                                            <br>
                                            <div id="divEvidenciasAtorizacion">
                                                <?php
                                                $evidencias = explode(',', $informacion['correctivoGarantiaRespaldo'][0]['Evidencia']);
                                                foreach ($evidencias as $item) {
                                                    echo '<div class = "evidencia2">';
                                                    echo '<a href = "' . $item . '" data-lightbox="image-' . $item . $informacion['correctivoGarantiaRespaldo'][0]['Id'] . '">';
                                                    echo '<img src = "' . $item . '" alt = "Lights" style = "width:100%">';
                                                    echo '</a>';
                                                    echo '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="informacionSolicitudEquipoRespaldo" class="hidden">
                            <div class="well">

                                <!--Empezando Titulo de formulario-->
                                <div class="row m-r-10">
                                    <div class="col-md-12">
                                        <h3 class="m-t-10">Solicitud de Equipo de Respaldo</h3>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>                               
                                <div class="row">
                                    <div class="col-md-6">          
                                        <div class="form-group">
                                            <label> Asignado:</label>      
                                            <br>
                                            <?php (empty($informacion['solicitudEquipoRespaldo'][0]['Atiende'])) ? $AtiendeRespaldo = '' : $AtiendeRespaldo = $informacion['solicitudEquipoRespaldo'][0]['Atiende']; ?>
                                            <strong id="divAtiende"><?php echo $AtiendeRespaldo; ?></strong>
                                        </div>  
                                    </div> 
                                    <div class="col-md-6">          
                                        <div class="form-group">
                                            <label> Fecha de Asignación:</label>      
                                            <br>
                                            <?php (empty($informacion['solicitudEquipoRespaldo'][0]['FechaCreacion'])) ? $FechaCreacionRespaldo = '' : $FechaCreacionRespaldo = $informacion['solicitudEquipoRespaldo'][0]['FechaCreacion']; ?>
                                            <strong id="divFechaAtiende"><?php echo $FechaCreacionRespaldo; ?></strong>
                                        </div>  
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div id="entregaEnvioEquipo" class="hidden">
                            <!--Empezando Titulo de formulario de entrega y envio de equipo-->
                            <div class="row m-r-10">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Entrega o Envío de Equipo</h3>
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

                            <ul class="nav nav-pills">
                                <li class="active"><a href="#entrega-equipo" data-toggle="tab">Entrega del Equipo (Local - Trigo)</a></li>
                                <li><a href="#eviar-equipo" data-toggle="tab">Enviar Equipo (Foraneo)</a></li>
                                <li><a href="#entrega-ti" data-toggle="tab">Entrega a TI</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active in" id="entrega-equipo">
                                    <div class="well">
                                        <div id="firmaEntregaEquipo" class="row hidden">
                                            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
                                                <h5 class="f-w-700 text-center">Firma de Entrega</h5>
                                                <?php (empty($informacion['entregaEquipo'][0]['Firma'])) ? $firmaEntrega = '' : $firmaEntrega = $informacion['entregaEquipo'][0]['Firma']; ?>
                                                <img style="max-height: 120px;" src="<?php echo $firmaEntrega; ?>" alt="Firma de Entrega" />
                                                <?php (empty($informacion['entregaEquipo'][0]['Recibe'])) ? $Recibe = '' : $Recibe = $informacion['entregaEquipo'][0]['Recibe']; ?>
                                                <h6 class="f-w-700 text-center"><?php echo $Recibe; ?></h6>            
                                                <?php (empty($informacion['entregaEquipo'][0]['Fecha'])) ? $FechaEntrega = '' : $FechaEntrega = $informacion['entregaEquipo'][0]['Fecha']; ?>
                                                <h6 class="f-w-700 text-center"><?php echo $FechaEntrega; ?></h6>            
                                            </div>
                                        </div>
                                        <div id="botonEntregaEquipo" class="row m-t-10">
                                            <!--Empezando error--> 
                                            <div class="col-md-12">
                                                <div class="errorEntregaEquipo"></div>
                                            </div>
                                            <!--Finalizando Error-->
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <br>
                                                    <a id="btnGuardarEntregarEquipo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Firma y Entregar Equipo</a>                            
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-10">
                                            <div class="col-md-12">
                                                <div id="errorEntregaEquipo"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="eviar-equipo">
                                    <div class="well"> 
                                        <ul class="nav nav-pills">
                                            <li class="active"><a href="#formaEnvio" data-toggle="tab">Paqueteria o Consolidado</a></li>
                                            <li class=""><a href="#entregaEquipo" data-toggle="tab">Entrega</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade active in" id="formaEnvio">

                                                <!--Empezando formulario para Consolidado o Paqueteria-->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="comoEnvia">¿Como se envia? *</label>
                                                            <select id="selectTipoEnvioGarantia" class="form-control" style="width: 100%">
                                                                <option value="">Seleccionar</option>
                                                                <option value="2">Paqueteria</option>
                                                                <option value="3">Consolidado</option>
                                                            </select>  
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="selectListaTipoEnvioGarantia">Paquetería o Consolidado *</label>
                                                            <select id="selectListaTipoEnvioGarantia" class="form-control" style="width: 100%" disabled>
                                                                <option value="">Seleccionar</option>
                                                            </select>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputGuiaGarantia">Guía *</label>
                                                            <?php (empty($informacion['envioEquipo'][0]['Guia'])) ? $valor = '' : $valor = $informacion['envioEquipo'][0]['Guia']; ?>
                                                            <input id="inputGuiaGarantia" type="text" class="form-control"  placeholder="Ingrese el dato" value="<?php echo $valor; ?>"/>
                                                        </div>
                                                    </div>                               
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">                                    
                                                        <div class="form-group">
                                                            <label for="inputComentariosEnvioGarantia">Comentarios de Envío</label>
                                                            <?php (empty($informacion['envioEquipo'][0]['ComentariosEnvio'])) ? $valor = '' : $valor = $informacion['envioEquipo'][0]['ComentariosEnvio']; ?>
                                                            <textarea id="inputComentariosEnvioGarantia" class="form-control " placeholder="Ingrese los comentarios" rows="3" ><?php echo $valor; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">                                    
                                                        <div class="form-group">
                                                            <label for="evidenciaEnvioGarantia">Foto de Guía *</label>
                                                            <input id="evidenciaEnvioGarantia"  name="evidenciasEnvioGarantia[]" type="file" multiple/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--Empezando el mensaje de error-->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="errorGuardarEnvioGarantia"></div>
                                                    </div>
                                                </div>
                                                <!--Finalizando el mensaje de error-->

                                                <!--Empezando botones para guardar el envio-->
                                                <div class="row">
                                                    <div class="col-md-12 text-center m-t-20">
                                                        <button id="btnGuardarEnvioGarantia" type="button" class="btn btn-sm btn-primary" ><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                                                    </div>
                                                </div>
                                                <!--Finalizando botones para guardar el envio-->

                                            </div>
                                            <div class="tab-pane fade" id="entregaEquipo">
                                                <div id="mensajeEntregaGarantia" class="row">
                                                    <div class="col-md-12 m-t-20">
                                                        <div class="alert alert-warning fade in m-b-15">                            
                                                            Para habilitar los campos es necesario guardar la información de Paqueteria o Consolidado.                             
                                                        </div>                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="entregaFechaGarantia">Fecha y Hora *</label>
                                                            <?php (empty($informacion['envioEquipo'][0]['FechaCapturaRecepcion'])) ? $valor = '' : $valor = $informacion['envioEquipo'][0]['FechaCapturaRecepcion']; ?>
                                                            <div id="entregaFechaGarantia" class="input-group date">
                                                                <input id="entregaFechaEnvioGarantia" type="text" class="form-control entregaGarantia" placeholder="Fecha" value="<?php echo $valor; ?>" disabled/><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                            </div>                                           
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="selectEquipoRespaldoEntregaEnvioGarantia">¿Quién Recibe? *</label>
                                                            <select id="selectEquipoRespaldoEntregaEnvioGarantia" class="form-control entregaGarantia" style="width: 100%" disabled>
                                                                <option value="">Seleccionar</option>
                                                                <?php
                                                                foreach ($informacion['listaUsuarios'] as $item) {
                                                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>                               
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">                                    
                                                        <div class="form-group">
                                                            <label for="inputComentarioEntregaEnvioGarantia">Comentarios de Entrega</label>
                                                            <?php (empty($informacion['envioEquipo'][0]['ComentariosEntrega'])) ? $valor = '' : $valor = $informacion['envioEquipo'][0]['ComentariosEntrega']; ?>
                                                            <textarea id="inputComentarioEntregaEnvioGarantia" class="form-control entregaGarantia" placeholder="Ingrese los comentarios" rows="3" disabled><?php echo $valor; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">                                    
                                                        <div class="form-group">
                                                            <label for="evidenciaEntregaEnvioGarantia">Evidencias Entrega *</label>
                                                            <input id="evidenciaEntregaEnvioGarantia" name="evidenciasEntregaEnvioGarantia[]" type="file" multiple/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Empezando el mensaje de error-->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div id="errorGuardarEnvioEntregaGarantia"></div>
                                                    </div>
                                                </div>
                                                <!--Finalizando el mensaje de error-->

                                                <!--Empezando botones para guardar entrega-->
                                                <div class="row">
                                                    <div class="col-md-12 text-center m-t-20">
                                                        <button id="btnGuardarEnvioEntregaGarantia" type="button" class="btn btn-sm btn-primary entregaGarantia" disabled><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                                                    </div>
                                                </div>
                                                <!--Finalizando botones para guardar entrega-->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="entrega-ti">
                                    <div class="well">
                                        <div id="firmaEntregaTI" class="row hidden">
                                            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
                                                <h5 class="f-w-700 text-center">Firma de Entrega</h5>
                                                <?php (empty($informacion['entregaEquipo'][0]['Firma'])) ? $firmaEntrega = '' : $firmaEntrega = $informacion['entregaEquipo'][0]['Firma']; ?>
                                                <img style="max-height: 120px;" src="<?php echo $firmaEntrega; ?>" alt="Firma de Entrega" />
                                                <?php (empty($informacion['entregaEquipo'][0]['NombreRecibe'])) ? $Recibe = '' : $Recibe = $informacion['entregaEquipo'][0]['NombreRecibe']; ?>
                                                <h6 class="f-w-700 text-center"><?php echo $Recibe; ?></h6>            
                                                <?php (empty($informacion['entregaEquipo'][0]['Fecha'])) ? $FechaEntrega = '' : $FechaEntrega = $informacion['entregaEquipo'][0]['Fecha']; ?>
                                                <h6 class="f-w-700 text-center"><?php echo $FechaEntrega; ?></h6>            
                                            </div>
                                        </div>
                                        <div id="botonEntregaTI" class="row m-t-10">
                                            <!--Empezando error--> 
                                            <div class="col-md-12">
                                                <div class="errorEntregaTI"></div>
                                            </div>
                                            <!--Finalizando Error-->
                                            <div class="col-md-12">
                                                <div class="form-group text-center">
                                                    <br>
                                                    <a id="btnGuardarEntregarTI" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Firma y Entregar a TI</a>                            
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-10">
                                            <div class="col-md-12">
                                                <div id="errorEntregaTI"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Problemas Servicio-->

        <!--Empezando la seccion Solucion-->
        <div class="tab-pane fade " id="Solucion">            
            <div class="panel-body">

                <!-- Empezando Titulo de Pestaña -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Solución del Servicio</h3>
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

                <ul class="nav nav-pills">
                    <li class="active"><a href="#reparacion-sin-Equipo" data-toggle="tab">Reparación sin Equipo</a></li>
                    <li><a href="#reparacion-con-refaccion" data-toggle="tab">Reparación con Refacción</a></li>
                    <li><a href="#cambio-equipo" data-toggle="tab">Cambio de Equipo</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="reparacion-sin-Equipo">
                        <div class="well">

                            <div class="mensajeSolucion row hidden">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        No existe registros en Catálogo Soluciones.                             
                                    </div>                        
                                </div>
                            </div>

                            <!--Empezando Solucion-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="selectSolucionReparacionSinEquipo">Solución *</label>
                                        <select id="selectSolucionReparacionSinEquipo" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                            <option value="">Seleccionar</option>
                                            <?php
                                            if ($informacion['catalogoSolucionesEquipo'] !== FALSE) {
                                                if ($informacion['catalogoSolucionesEquipo'] !== NULL) {
                                                    foreach ($informacion['catalogoSolucionesEquipo'] as $item) {
                                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!-- Empezando Evidencias Solucion -->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasSolucionReparacionSinEquipo">Evidencias de Solución *</label>
                                        <input id="evidenciasSolucionReparacionSinEquipo"  name="evidenciasSolucionReparacionSinEquipo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Obervaciones Solucion-->
                            <div class="row">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesSolucion">Observaciones de la Solución *</label>
                                        <textarea id="inputObservacionesSolucionReparacionSinEquipo" class="form-control " placeholder="Observaciones de la solución." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando error--> 
                            <div class="row m-t-10">
                                <div class="col-md-12">
                                    <div class="errorFormularioSolucionReparacionSinEquipo"></div>
                                </div>
                            </div>
                            <!--Finalizando Error-->

                            <div class="row m-t-10">
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarReparacionSinEquipo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarConcluirReparacionSinEquipo" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Guardar y Concluir Servicio</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reparacion-con-refaccion">
                        <div class="well">

                            <div class="mensajeRefaccion row hidden">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        No existe registros en Catálogo Refacciones.                             
                                    </div>                        
                                </div>
                            </div>

                            <?php
                            if (!$usarStock) {
                                ?>
                                <!--Empezando Refaccion, Cantidad-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectRefaccionSolucionReparacionConRefaccion">Refacción *</label>
                                            <select id="selectRefaccionSolucionReparacionConRefaccion" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                if ($informacion['catalogoComponentesEquipos'] !== FALSE) {
                                                    if ($informacion['catalogoComponentesEquipos'] !== NULL) {
                                                        foreach ($informacion['catalogoComponentesEquipos'] as $item) {
                                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">                                           
                                            <label for="inputCantidadRefaccionSolicitudReparacionConRefaccion">Cantidad *</label>
                                            <input id="inputCantidadRefaccionSolicitudReparacionConRefaccion" type="number" class="form-control"  placeholder="Cantidad" disabled/>
                                        </div>                               
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <br>
                                            <a id="btnAgregarReparacionConRefaccion" href="javascript:;" class="btn btn-success m-t-4 "><i class="fa fa-plus"></i> Agregar</a>                            
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <!--Empezando Tabla Refaccion y Cantidad-->
                                <div class="table-responsive">
                                    <table id="data-table-reparacion-refaccion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                
                                                <th class="never">IdRefaccion</th>
                                                <th class="all">Refacción</th>
                                                <th class="all">Cantidad</th>                                                  
                                            </tr>
                                        </thead>
                                        <tbody>                                      
                                        </tbody>
                                    </table>
                                </div>
                                <!--Finalizando-->

                                <!--Empezando mensaje de tabla-->
                                <div class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                                        </div>                        
                                    </div>
                                </div>
                                <!--Finalizando mensaje de tabla-->

                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                        if (!isset($inventarioComponentes) || count($inventarioComponentes) <= 0) {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 m-b-10">
                                                    <div class="alert alert-warning fade in f-s-15">
                                                        <strong>Warning! </strong>Al parecer no cuentas con refacciones para este equipo.
                                                    </div>                        
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="table-responsive">
                                            <table id="data-table-reparacion-refaccion-stock" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>                                
                                                        <th class="never">IdRefaccion</th>
                                                        <th class="all">Refacción</th>
                                                        <th class="never">Cantidad</th>                                                     
                                                        <th class="never">IdInventario</th>
                                                        <th class="all">Serie</th>
                                                        <th class="all"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>    
                                                    <?php
                                                    if (isset($inventarioComponentes) && count($inventarioComponentes) > 0) {
                                                        foreach ($inventarioComponentes as $key => $value) {
                                                            $checked = ($value['Usado'] <= 0) ? 'fa-square-o' : 'fa-check-square-o';
                                                            echo ''
                                                            . '<tr>'
                                                            . ' <td>' . $value['IdProducto'] . '</td>'
                                                            . ' <td>' . $value['Producto'] . '</td>'
                                                            . ' <td>' . $value['Cantidad'] . '</td>'
                                                            . ' <td>' . $value['IdInventario'] . '</td>'
                                                            . ' <td>' . $value['Serie'] . '</td>'
                                                            . ' <td class="text-center"><i data-id="' . $value['IdInventario'] . '" class="checkRefaccionesStock fa fa-2x ' . $checked . '"></i></td>'
                                                            . '</tr>';
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <!--Empezando Evidencias Solucion-->
                            <div class="row m-t-20">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasSolucionReparacionConRefaccion">Evidencias de la Solución *</label>
                                        <input id="evidenciasSolucionReparacionConRefaccion"  name="evidenciasSolucionReparacionConRefaccion[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Observaciones Solucion-->
                            <div class="row m-t-20">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesSolucionReparacionConRefaccion">Observaciones de la Solución *</label>
                                        <textarea id="inputObservacionesSolucionReparacionConRefaccion" class="form-control " placeholder="Observaciones de la solución." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <div class="row m-t-10">
                                <!--Empezando error--> 
                                <div class="col-md-12">
                                    <div class="errorFormularioSolucionReparacionConRefaccion"></div>
                                </div>
                                <!--Finalizando Error-->
                            </div>
                            <div class="row m-t-10">
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarSolucionReparacionConRefaccion" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarConcluirSolucionReparacionConRefaccion" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Guardar y Concluir Servicio</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cambio-equipo">
                        <div class="well">
                            <?php
                            if (!$usarStock) {
                                ?>                               
                                <!--Empezando Equipo y Numero de Serie-->
                                <div class="row m-t-20">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectEquipoSolucionCambioEquipo">Equipo *</label>
                                            <select id="selectEquipoSolucionCambioEquipo" class="form-control" style="width: 100%" disabled>
                                                <option value="">Seleccionar</option>
                                                <?php
                                                if ($informacion['EquiposXLinea'] !== FALSE) {
                                                    if ($informacion['EquiposXLinea'] !== NULL) {
                                                        foreach ($informacion['EquiposXLinea'] as $item) {
                                                            echo '<option value="' . $item['IdMod'] . '">' . $item['Linea'] . ' - ' . $item['Marca'] . ' - ' . $item['Modelo'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputSerieSolucionCambioEquipo">Número de Serie *</label>
                                            <input type="text" class="form-control" id="inputSerieSolucionCambioEquipo" placeholder="Serie" style="width: 100%" disabled/>                            
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando-->

                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                        if (!isset($inventarioEquipos) || count($inventarioEquipos) <= 0) {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 m-b-10">
                                                    <div class="alert alert-warning fade in f-s-15">
                                                        <strong>Warning! </strong>Al parecer no cuentas con equipos compatibles. Si crees que es un error recarga la página o contacta al administrador.
                                                    </div>                        
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="table-responsive">
                                            <table id="data-table-reparacion-cambio-stock" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>                                
                                                        <th class="never">IdEquipo</th>
                                                        <th class="all">Equipo</th>
                                                        <th class="never">Cantidad</th>                                                     
                                                        <th class="never">IdInventario</th>
                                                        <th class="all">Serie</th>
                                                        <th class="all"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>    
                                                    <?php
                                                    if (isset($inventarioEquipos) && count($inventarioEquipos) > 0) {
                                                        foreach ($inventarioEquipos as $key => $value) {
                                                            $checked = ($value['Usado'] <= 0) ? 'fa-square-o' : 'fa-check-square-o';
                                                            echo ''
                                                            . '<tr>'
                                                            . ' <td>' . $value['IdProducto'] . '</td>'
                                                            . ' <td>' . $value['Producto'] . '</td>'
                                                            . ' <td>' . $value['Cantidad'] . '</td>'
                                                            . ' <td>' . $value['IdInventario'] . '</td>'
                                                            . ' <td>' . $value['Serie'] . '</td>'
                                                            . ' <td class="text-center"><i data-id="' . $value['IdInventario'] . '" data-id-producto="' . $value['IdProducto'] . '" data-serie="' . $value['Serie'] . '" class="checkEquipoStock fa fa-2x ' . $checked . '"></i></td>'
                                                            . '</tr>';
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <!--Empezando Evidencias Solucion-->
                            <div class="row m-t-20">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="evidenciasSolucionCambioEquipo">Evidencias de la Solución *</label>
                                        <input id="evidenciasSolucionCambioEquipo"  name="evidenciasSolucionCambioEquipo[]" type="file" multiple/>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando Evidencias Solucion-->
                            <div class="row m-t-20">
                                <div class="col-md-12">                                    
                                    <div class="form-group">
                                        <label for="inputObservacionesSolucionCambioEquipo">Observaciones de la Solución *</label>
                                        <textarea id="inputObservacionesSolucionCambioEquipo" class="form-control " placeholder="Observaciones de la solución." rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Finalizando-->

                            <!--Empezando error--> 
                            <div class="row m-t-10">
                                <div class="col-md-12">
                                    <div class="errorFormularioSolucionCambioEquipo"></div>
                                </div>
                            </div>
                            <!--Finalizando Error-->

                            <div class="row m-t-10">
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarSolucionCambioEquipo" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <a id="btnGuardarConcluirSolucionCambioEquipo" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Guardar y Concluir Servicio</a>                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Solucion-->

        <!--Empezando la seccion Historial-->
        <div class="tab-pane fade " id="Historial">
            <?php echo $historialAvancesProblemas; ?>
        </div>
        <!--Finalizando la seccion Historial-->

        <!--Empezando la seccion Notas-->
        <div class="tab-pane fade " id="Notas">            
            <div class="panel-body">
                <?php echo $notas; ?>
            </div>
        </div>
        <!--Finalizando la seccion Notas-->
    </div> 
