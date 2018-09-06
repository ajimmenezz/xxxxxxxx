<div id="seccion-datos-logistica" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir el servicio-->
    <div class="panel-heading p-0">
        <div class="btn-group pull-right" data-toggle="buttons"></div>
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
            <label id="btnRegresarSeguimientoLogistica" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#Generales" data-toggle="tab">Información General</a></li>
                <li class=""><a href="#EquiposMateriales" data-toggle="tab">Equipos y Materiales</a></li>
                <li class="hidden"><a href="#Envio" data-toggle="tab">Punto a Punto</a></li>                
                <li class="hidden"><a href="#Recoleccion" data-toggle="tab">Recolección y Almacenamiento</a></li>
                <li class="hidden"><a href="#Distribucion" data-toggle="tab">Recolección y Distribución</a></li>
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
                            <label for="seguimientoLogistica"> Ticket: <strong><?php echo $datosServicio['Ticket']; ?></strong></label>
                        </div>    
                    </div> 
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoLogistica"> Atendido por: <strong><?php echo $datosServicio['NombreAtiende']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-4">          
                        <div class="form-group text-right">
                            <label for="seguimientoLogistica"> Fecha de Servicio: <strong><?php echo $datosServicio['FechaCreacion']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-2">          
                        <div class="form-group text-right">
                            <label for="seguimientoLogistica"><strong id="detallesLogistica"><a>+ Detalles</a></strong></label>                        
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
                                <label for="seguimientoLogistica"> Solicitud: <strong><?php echo $datosServicio['IdSolicitud']; ?></strong></label>
                            </div>    
                        </div> 
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label for="seguimientoLogistica"> Solicita: <strong><?php echo $datosServicio['NombreSolicita']; ?></strong></label>                        
                            </div>    
                        </div>
                        <div class="col-sm-3 col-md-4">          
                            <div class="form-group text-right">
                                <label for="seguimientoLogistica"> Fecha de Solicitud: <strong><?php echo $datosServicio['FechaCrecionSolicitud']; ?></strong></label>                        
                            </div>    
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if (!empty($datosServicio['descripcionSolicitud'])) {
                            ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="seguimientoLogistica"> Descripción Solicitud:</label>      
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
                        if ($informacion['folio'][0]['Folio'] === NULL || $informacion['folio'][0]['Folio'] === '0') {
                            $tituloFolio = 'Sin Folio';
                            $folioTexto = '';
                            $mostrarGuardarFolio = '';
                            $mostrarActulizarEliminarFolio = 'hidden';
                        } else {
                            $tituloFolio = 'Cuenta con Folio';
                            $folioTexto = $informacion['folio'][0]['Folio'];
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
                <form class="margin-bottom-0" id="formGeneralesLogistica" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos del trafico</h3>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seguimientoLogistica">Tipo de Trafico *</label>
                                <select id="selectTTLogistica" class="form-control generales" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['tiposTrafico'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seguimientoLogistica">Ruta</label>
                                <div class="input-group">
                                    <select id="selectRutaLogistica" class="form-control generales" style="width: 100%">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        foreach ($informacion['rutas'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Codigo'] . ' (' . $item['Chofer'] . ' ' . $item['Paterno'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="input-group-addon">
                                        <button id="btnAgregarRuta" type="button" class="btn btn-success btn-xs generales" title="Agregar nueva Ruta al Select"><i class="fa fa-plus"></i></button>
                                        <a id="btnEmpezarRutaSeguimiento" href="javascript:;" class="btn btn-warning btn-xs hidden" ><i class="fa fa-truck"></i> Empezar Ruta</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seguimientoLogistica">Tipo de Origen *</label>
                                <select id="selectTipoOrigen" class="form-control generales" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['tiposOrigenDestino'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="seleccionTipoOrigen" class="hidden">                 
                            <div id="tipoOrigen">
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seguimientoLogistica">Tipo de Destino *</label>
                                <select id="selectTipoDestino" class="form-control generales" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['tiposOrigenDestino'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="seleccionTipoDestino" class="hidden">                 
                            <div id="tipoDestino">
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorGeneralesLogistica"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarGenerales" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                            </div>
                        </div>
                    </div>
                </form>
                <!--Finalizando formulario para Generales-->
            </div>
        </div>
        <!--Finalizando la seccion de generales-->

        <!--Empezando la seccion de Equipos-->
        <div class="tab-pane fade" id="EquiposMateriales">
            <div class="panel-body">
                <?php
                $hidden = '';
                if (!empty($informacion['datosRecoleccion'])) {
                    $hidden = 'hidden';
                }
                ?>
                <!--Empezando titulo de la seccion de equipos Materiales-->
                <div class="row m-b-30">
                    <div class="col-md-12">
                        <div class="subtitulo-contenido">
                            <h5 class="elemento-1 subtitulo"><strong>Producto de Recoleccion o Entrega</strong></h5>
                            <div class="elemento-2 botones">
                                <button id="btnDescargarFormato" type="button" class="btn btn-xs btn-default <?php echo $hidden ?>" title="Descargar Formato"><i class="fa fa-cloud-download"></i> <span class="textoBtn">Descargar Formato</span></button>
                                <button id="btnSubirFormato" type="file" class="btn btn-xs btn-info <?php echo $hidden ?>" title="Subir Formato"><i class="fa fa-cloud-upload"></i> <span class="textoBtn">Subir Formato</span></button>
                            </div>
                        </div>  
                        <div class="underline "></div>
                    </div>
                </div>
                <!--Finalizando titulo de la seccion de equipos Materiales-->

                <!--Empezando mensaje de confirmacion-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="confirmacionFormato"></div>
                    </div>
                </div> 
                <!--Finalizando mensaje de confirmacion-->

                <!--Empezando la tabla de servcicio de materiales-->
                <div class="row">                
                    <div class="col-md-12">
                        <table id="data-table-servicio-materiales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>                                
                                    <th class="all">Equipo</th>
                                    <th class="desktop tablet-l">Serie</th>
                                    <th class="all">Cantidad</th>
                                    <th class="never">IdTipo</th>
                                    <th class="never">IdModelo</th>                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($informacion['datosTrafico']['Material'])) {
                                    foreach ($informacion['datosTrafico']['Material'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Serie'] . '</td>';
                                        echo '<td>' . $value['Cantidad'] . '</td>';
                                        echo '<td>' . $value['IdTipoEquipo'] . '</td>';
                                        echo '<td>' . $value['IdModelo'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>                                        
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finalizando la tabla de servcicio de materiales-->

                <!--Empezando mensaje de tabla-->
                <div class="row">
                    <div class="col-md-12 m-t-20">
                        <div class="alert alert-warning fade in m-b-15">                            
                            Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                        </div>                        
                    </div>
                </div>
                <!--Finalizando mensaje de tabla-->

                <!--Empezando mensaje de error-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="errorGuardarMaterial"></div>
                    </div>
                </div> 
                <!--Finalizando mensaje de error-->

                <!--Empezando Boton para guardar los cambios del tabla materiales-->
                <div class="row">
                    <div class="col-md-12 m-t-10 m-t-20 text-center">
                        <button id="btnGuardarMaterialTrafico" type="button" class="btn btn-sm btn-success <?php echo $hidden ?>"><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                    </div>
                </div>
                <!--Finalizando Boton para guardar los cambios del tabla materiales-->



                <!--Empezando la seccion de los fomulario para agregar elementos a la tabla de material-->
                <div id="formulariosMaterial" class="<?php echo $hidden ?>">

                    <!--Empezando Subtitulo-->
                    <div id="tituloFormulariosMaterial" class="row m-t-20 <?php echo $hidden ?>">
                        <div class="col-md-12">
                            <div class="subtitulo-contenido"><h5 class="elemento-1"><strong>Definiendo Material</strong></h5></div>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Subtitulo-->

                    <!--Empezando la seccion de los fomulario para agregar elementos a la tabla de material-->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#Equipos" data-toggle="tab">Equipos</a></li>
                                <li><a href="#Material" data-toggle="tab">Material y Herramienta</a></li>                        
                                <li><a href="#Otros" data-toggle="tab">Otros</a></li>
                            </ul>
                            <div class="tab-content">

                                <!--Empezando formulario para Equipos-->
                                <div class="tab-pane fade active in" id="Equipos">

                                    <div class="subtitulo-contenido">
                                        <h3 class="elemento-1">Equipos</h3>
                                        <h3 class="elemento-2">
                                            <a id="btnAgregaEquipo" href="javascript:;" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus"></i> Agregar</a>
                                        </h3>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">                                       
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-12 p-t-15">
                                                        <label for="equipo">Equipo</label>
                                                    </div>
                                                    <div class="col-md-offset-3 col-md-3 col-xs-12 text-right">
                                                        <div class="input-group">
                                                            <input type="text" id="inputFiltraEquipos" class="form-control" placeholder="Filtrar Equipos" />
                                                            <div class="input-group-btn">                                                                          
                                                                <button type="button" class="btn btn-success" id="btnFiltrarEquipos">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-md-12 col-xs-12">
                                                        <select id="selectEquipo" class="form-control materialProyecto" style="width: 100%">
                                                            <option value="">Seleccionar</option>
                                                            <?php
                                                            foreach ($informacion['ListaMaterial'] as $item) {
                                                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12 p-t-15">                                                
                                                        <label for="cantidadEquipo">Cantidad</label>
                                                    </div>
                                                    <div class="row m-t-10">
                                                        <div class="col-md-12 col-xs-12 p-t-10">
                                                            <input id="inputCantidadEquipo" type="number" class="form-control"  placeholder="Cantidad"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                               
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">                                    
                                            <div class="form-group">
                                                <label for="numeroSerie">Numero de series</label>
                                                <ul id="inputNumeroSerieTags"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando formulario para Equipos-->

                                <!--Empezando formulario para Material-->
                                <div class="tab-pane fade" id="Material">
                                    <div class="subtitulo-contenido"><h3 class="elemento-1">Material</h3><h3 class="elemento-2"><a id="btnAgregaMaterial" href="javascript:;" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus"></i> Agregar</a></h3></div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-12 p-t-15">
                                                        <label for="material">Material o Herramienta</label>
                                                    </div>
                                                    <div class="col-md-offset-3 col-md-3 col-xs-12 text-right">
                                                        <div class="input-group">
                                                            <input type="text" id="inputFiltraMaterialHerramientas" class="form-control" placeholder="Filtrar Equipos" />
                                                            <div class="input-group-btn">                                                                          
                                                                <button type="button" class="btn btn-success" id="btnFiltrarMaterialHerramientas">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-md-12 col-xs-12">
                                                        <select id="selectMaterial" class="form-control materialProyecto" style="width: 100%">
                                                            <option value="">Seleccionar</option>
                                                            <?php
                                                            foreach ($informacion['ListaMaterial'] as $item) {
                                                                echo '<option value="' . $item['Id'] . '">' . $item['Equipo'] . '</option>';
                                                            }
                                                            ?>
                                                        </select> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12 p-t-15">                                                
                                                        <label for="cantidadMaterial">Cantidad</label>
                                                    </div>
                                                    <div class="row m-t-10">
                                                        <div class="col-md-12 col-xs-12 p-t-10">
                                                            <input id="inputCantidadMaterial" type="number" class="form-control"  placeholder="Cantidad"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                               
                                    </div>
                                </div>
                                <!--Finalizando formulario para Material-->

                                <!--Empezando formulario para otros-->
                                <div class="tab-pane fade" id="Otros">
                                    <div class="subtitulo-contenido"><h3 class="elemento-1">Otros</h3><h3 class="elemento-2"><a id="btnAgregarOtros" href="javascript:;" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus"></i> Agregar</a></h3></div>                            
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="otroProducto">Descripción del producto</label>
                                                <input id="inputOtro" type="text" class="form-control"  placeholder="Producto"/>                                           
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="cantidadOtro">Cantidad</label>
                                                <input id="inputCantidadOtro" type="number" class="form-control"  placeholder="Cantidad"/>
                                            </div>
                                        </div>                               
                                    </div>
                                </div>
                                <!--Finalizando formulario para otros-->

                            </div>
                        </div>
                    </div>
                    <!--Finalizando la seccion de los fomulario para agregar elementos a la tabla de material-->

                    <!--Empezando mensaje de error-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="errorAgregar"></div>
                        </div>
                    </div> 
                    <!--Finalizando mensaje de error-->
                </div>
            </div>
        </div>
        <!--Finalizando la seccion de Equipos-->

        <!--Empezando la seccion de Envio (Punto a Punto)-->
        <div class="tab-pane fade" id="Envio">
            <div class="panel-body">
                <!--Empezando Subtitulo-->
                <div class="row m-t-20">
                    <div class="col-md-12">
                        <div class="subtitulo-contenido"><h5 class="elemento-1"><strong>Informacion del envio</strong></h5></div>
                        <div class="underline m-b-15 "></div>
                    </div>
                </div>
                <!--Finalizando Subtitulo-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fechaEnvio">Fecha y Hora *</label>
                            <div id="envioFecha" class="input-group date">
                                <input id="fechaEnvio"  type="text" class="form-control" placeholder="Fecha" value=""/>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="comoEnvia">¿Como se envia? *</label>
                            <select id="selectTipoEnvio" class="form-control" style="width: 100%">
                                <option value="">Seleccionar</option>
                                <?php
                                foreach ($informacion['ListaTiposEnvio'] as $item) {
                                    echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                }
                                ?>
                            </select>  
                        </div>
                    </div>
                </div>              

                <!--Empezando Separador-->
                <div id="titulo" class="row m-t-20 hidden">
                    <div class="col-md-12">                    
                        <div class="subtitulo-contenido"><h5 class="elemento-1"><strong>Información  de Envio y Entrega</strong></h5></div>
                        <div class="underline m-b-15 "></div>
                    </div>
                </div>
                <!--Finalizando Separador-->

                <!--Empezando seccion de consolidado, paqueteria y entrega-->
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-pills">
                            <li class="hidden"><a href="#ConsolidadoPaqueteria" data-toggle="tab"><span class="tipoEnvio"></span></a></li>
                            <li class="hidden"><a href="#EntregaMaterial" data-toggle="tab">Entrega</a></li>
                        </ul>
                        <div class="tab-content">
                            <!--Empezando formulario para Consolidado o Paqueteria-->
                            <div class="tab-pane fade hidden" id="ConsolidadoPaqueteria">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tipoEnvio"></label>
                                            <select id="selectListaTipoEnvio" class="form-control" style="width: 100%" >
                                                <option value="">Seleccionar</option>
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="datoTipoEnvio">Guia *</label>
                                            <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['Guia']; ?>
                                            <input id="inputDatoGuia" type="text" class="form-control"  placeholder="Ingrese el dato" value="<?php echo $valor; ?>"/>
                                        </div>
                                    </div>                               
                                </div>
                                <div class="row">
                                    <div class="col-md-12">                                    
                                        <div class="form-group">
                                            <label for="numeroSerie">Comentarios de Envío</label>
                                            <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['ComentariosEnvio']; ?>
                                            <textarea id="inputComentariosEnvio" class="form-control " placeholder="Ingrese los comentarios" rows="3" ><?php echo $valor; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">                                    
                                        <div class="form-group">
                                            <label for="numeroSerie">Evidencias Envío *</label>
                                            <input id="evidenciaEnvio"  name="evidenciasEnvio[]" type="file" multiple/>
                                        </div>
                                    </div>
                                </div>

                                <!--Empezando mensaje de evidencia-->
                                <div class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Nota: La evidencia no se guarda con el boton de guargar cambios.                      
                                        </div>                        
                                    </div>
                                </div>
                                <!--Finalizando mensaje de evidencia-->
                            </div>
                            <!--Finalizando formulario para Consolidado o Paqueteria-->

                            <!--Empezando formulario para Entrega de Envio-->
                            <div class="tab-pane fade hidden" id="EntregaMaterial">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="listatipoenvio">Fecha y Hora *</label>
                                            <div id="entregaFecha" class="input-group date">
                                                <input id="entregaFechaEnvio"  type="text" class="form-control" placeholder="Fecha" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>                                           
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quienRecibe">¿Quien Recibe? *</label>
                                            <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['NombreRecibe']; ?>
                                            <input id="inputRecibeEnvio" type="text" class="form-control"  placeholder="Ingrese la persona que recibe" value="<?php echo $valor; ?>"/>
                                        </div>
                                    </div>                               
                                </div>
                                <div class="row">
                                    <div class="col-md-12">                                    
                                        <div class="form-group">
                                            <label for="comentarioEntrega">Comentarios de Entrega</label>
                                            <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['ComentariosEntrega']; ?>
                                            <textarea id="inputComentarioEntregaEnvio" class="form-control " placeholder="Ingrese los comentarios" rows="3"><?php echo $valor; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">                                    
                                        <div class="form-group">
                                            <label for="evidenciaEntrega">Evidencias Entrega *</label>
                                            <input id="evidenciaEntregaEnvio"  name="evidenciasEntregaEnvio[]" type="file" multiple/>
                                        </div>
                                    </div>
                                </div>
                                <!--Empezando mensaje de evidencia-->
                                <div class="row">
                                    <div class="col-md-12 m-t-20">
                                        <div class="alert alert-warning fade in m-b-15">                            
                                            Nota: La evidencia no se guarda con el boton de guargar cambios.                      
                                        </div>                        
                                    </div>
                                </div>
                                <!--Finalizando mensaje de evidencia-->
                            </div>
                            <!--Finalizando formulario para Entrega de Envio-->
                        </div>
                    </div>                
                </div>
                <!--Finalizando seccion de consolidado, paqueteria y entrega-->

                <!--Empezando el mensaje de error-->
                <div class="row">
                    <div class="col-md-12">
                        <div id="errorGeneralEnvio"></div>
                    </div>
                </div>
                <!--Finalizando el mensaje de error-->

                <!--Empezando botones para guardar o concluir servicio-->
                <div class="row">
                    <div class="col-md-12 text-center m-t-20">
                        <button id="btnGuardarInformacionEnvio" type="button" class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                        <button id="btnConcluirServicioEnvio" type="button" class="btn btn-sm btn-danger btnConcluirServicio" ><i class="fa fa-unlock-alt"></i> Concluir Servicio</button>
                    </div>
                </div>
                <!--Finalizando botones para guardar o concluir servicio-->

            </div>
        </div>
        <!--Finalizando la seccion de Envio (Punto a Punto)-->

        <!--Empezando la seccion Recoleccion (Recolección y Almacenamiento)-->
        <div id="Recoleccion" class="tab-pane fade" >
            <div class="panel-body">
                <!--Empezando Subtitulo-->
                <div class="row m-t-20 tituloRecoleccionAlmacenamiento">
                    <div class="col-md-12">
                        <div class="subtitulo-contenido"><h5 class="elemento-1"><strong>Información de la recolección</strong></h5></div>
                        <div class="underline m-b-15 "></div>
                    </div>
                    <!--Finalizando Subtitulo-->

                    <!--Empezando Formulario Recoleccion-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaEnvio">Fecha y Hora de Recolección *</label>
                                <div id="recoleccionFecha" class="input-group date">
                                    <input id="fechaRecoleccion"  type="text" class="form-control" placeholder="Fecha" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datosRecoleccion">¿Quién Entrega? *</label>
                                <input id="inputEntregaRecoleccion" type="text" class="form-control"  placeholder="Nombre" />
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="datosRecoleccion">Observaciones de Recolección</label>
                                <textarea id="textareaObservacionesRecoleccion" class="form-control" name="observacionesRecoleccion" placeholder="Ingresa observaciones" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label for="evidenciaRecoleccion">Evidencias de Recolección *</label>
                                <input id="evidenciaRecoleccion"  name="evidenciasRecoleccion[]" type="file" multiple data-parsley-required="true"/>
                            </div>
                        </div>
                    </div>

                    <!--Empezando mensaje de evidencia-->
                    <div class="row">
                        <div class="col-md-12 m-t-20">
                            <div class="alert alert-warning fade in m-b-15">                            
                                Nota: La evidencia no se guarda con el boton de guargar cambios.                      
                            </div>                        
                        </div>
                    </div>
                    <!--Finalizando mensaje de evidencia-->

                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorGeneralRecoleccion"></div>
                        </div>
                    </div>
                </div>
                <div class="row botonesRecoleccion">
                    <div class="col-md-12 text-center m-t-20">
                        <button id="btnGuardarInformacionRecoleccion" type="button" class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                        <button id="btnConcluirServicioRecoleccion" type="button" class="btn btn-sm btn-danger btnConcluirServicio" ><i class="fa fa-unlock-alt"></i> Concluir Servicio</button>
                    </div>
                    <!--Finalizando Formulario Recoleccion-->
                </div>
            </div>
        </div>
        <!--Finalizando la seccion Recoleccion (Recolección y Almacenamiento)-->

        <!--Empezando la seccion Distribucion (Recolección y Distribución)-->
        <div id="Distribucion" class="tab-pane fade " >
            <div class="panel-body">
                <!--Empezando Subtitulo para tabla de distribucion-->
                <div id="tituloTablaDistribucion" class="row m-t-20">
                    <div class="col-md-12">                    
                        <div class="subtitulo-contenido">
                            <h5 class="elemento-1 subtitulo"><strong>Destinos</strong></h5>
                            <div class="elemento-2 botones">
                                <button id="btnGenerarDestinoDistribucion" type="button" class="btn btn-xs btn-success" ><i class="fa fa-plus"></i> Agregar Destino</button>
                                <button id="btnRegresarTablaDestinos" type="button" class="btn btn-xs btn-success hidden" ><i class="fa fa-reply"></i> Regresar a Destinos</button>
                            </div>
                        </div>
                        <div class="underline m-b-15 "></div>
                    </div>
                </div>
                <!--Finalizando Subtitulo para tabla de distribucion-->

                <!--Empezando Tabla destinos-->
                <div id="seccionTablaDestinosDistribucion">                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="data-table-distribucion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">Id</th>
                                            <th class="all">Tipo</th>
                                            <th class="all">Destino</th> 
                                            <th class="all">Estatus</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($informacion['listaEnviosDistribucion'])) {
                                            foreach ($informacion['listaEnviosDistribucion'] as $value) {
                                                echo '<tr>';
                                                echo '<td>' . $value['Id'] . '</td>';
                                                echo '<td>' . $value['TipoDestino'] . '</td>';
                                                echo '<td>' . $value['NombreDestino'] . '</td>';
                                                echo '<td>' . $value['Estatus'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!--Empezando botones para guardar, concluir y cancelar destino-->
                    <div class="row">
                        <div class="col-md-12 text-center m-t-20">
                            <button id="btnConcluirServicioEnvioDistribucion" type="button" class="btn btn-sm btn-danger btnConcluirServicio" ><i class="fa fa-unlock-alt"></i> Concluir Servicio</button>
                        </div>
                    </div>
                    <!--Finalizando botones para guardar, concluir y cancelar destino-->
                </div>
                <!--Finalizando Tabla destinos-->

                <!--Empezando Formulario para agregar Recolección-->
                <div id="seccionFormularioRecoleccion" class="hidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaHora">Fecha y Hora de Recolección *</label>
                                <div id="recoleccionFechaDistricion" class="input-group date">
                                    <input id="fechaRecoleccionDistribucion"  type="text" class="form-control" placeholder="Fecha" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="entrega">¿Quién Entrega? *</label>
                                <input id="inputEntregaRecoleccionDistricion" type="text" class="form-control"  placeholder="Nombre" />
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones de Recolección</label>
                                <textarea id="textareaObservacionesRecoleccionDistribucion" class="form-control" name="observacionesRecoleccion" placeholder="Ingresa observaciones" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label for="evidencia">Evidencias de Recolección *</label>
                                <input id="evidenciaRecoleccionDistribucion"  name="evidenciasRecoleccionDistribucion[]" type="file" multiple data-parsley-required="true"/>
                            </div>
                        </div>
                    </div>

                    <!--Empezando mensaje de error-->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorGeneralRecoleccionDistribucion"></div>
                        </div>
                    </div>
                    <!--Finalizando mensaje de error-->



                    <!--Empezando botones -->
                    <div class="row">
                        <div class="col-md-12 text-center m-t-20">
                            <button id="btnGuardarRecoleccionDistribucion" type="button" class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i> Guardar Cambios</button>                        
                        </div>
                    </div>
                    <!--Empezando botones-->
                </div>
                <!--Finlizando Formulario para agregar Recolección-->

                <!--Empezando seccion para definir la distribucion de la recoleccion-->
                <div id="seccionFormularioGenerarDestino" class="hidden">

                    <form id="formDestinoDistribucion" class="margin-bottom-0"  data-parsley-validate="true">
                        <!--Empezando fila de los campos tipo destino-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="material">Tipo Destino *</label>
                                    <select id="selectDestinoDistribucion" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        foreach ($informacion['tiposOrigenDestino'] as $item) {
                                            echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="seleccionTipoDestinoDistribucion" class="hidden">                 
                                <div id="destinoDistribucion">
                                </div>
                            </div>
                        </div>
                        <!--Finalizando fila de los campos tipo destino-->
                    </form>

                    <form id="formMaterialDestinoDistribucion" class="margin-bottom-0"  data-parsley-validate="true">
                        <!--Empezando fila de material-->
                        <div id="seccionDefinirMaterial" class="row hidden">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="material">Material *</label>
                                    <select id="selectMaterialDistribuir" class="form-control" style="width: 100%" data-parsley-required="true" disabled>
                                        <option value="">Seleccionar</option>                               
                                    </select>
                                </div>
                            </div>
                            <div id="contenedorSeriesDistribucion" class="col-md-4 hidden">
                                <div class="form-group">
                                    <label for="serie">Serie *</label>
                                    <select id="selectSerieMaterialDistribucion" class="form-control" style="width: 100%" multiple="multiple">
                                        <option value="">Seleccionar</option>                               
                                    </select>                                
                                </div>
                            </div>
                            <div id="contenedorCantidadDistribucion" class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidadMaterial">Cantidad *</label>
                                    <input id="cantidadMaterial"  type="text" class="form-control" data-parsley-required="true" disabled/>
                                </div>
                            </div>
                        </div>    
                        <!--Finalizando fila de material-->
                    </form>

                    <!--Empezando mensaje de error-->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorAgregarMaterialDistribucion"></div>
                        </div>
                    </div>
                    <!--Finalizando mensaje de error-->

                    <!--Empezando fila para tipo destino y boton agregar-->
                    <div class="row">             
                        <div class="col-md-12 text-center">
                            <label for="botonAgregar">&nbsp;</label>
                            <div class="form-grup">
                                <button id="btnGuardarDestinoDistribucion" type="button" class="btn btn-sm btn-success" ><i class="fa fa-plus"></i> Generar Nuevo Destino</button>
                                <button id="btnAgregarMaterialDistribucion" type="button" class="btn btn-sm btn-success hidden" ><i class="fa fa-plus"></i> Agregar Material</button>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando fila para tipo destino y boton agregar-->

                    <div id="secctionTablaMaterialDestino" class="hidden">
                        <!--Empezando Subtitulo para la lista de equipos que se distribuiran-->
                        <div id="tituloNuevaDistribucion" class="row m-t-20">
                            <div class="col-md-12">                    
                                <div class="subtitulo-contenido">
                                    <h5 class="elemento-1 subtitulo"><strong>Equipos Para Entregar</strong></h5>
                                </div>
                                <div class="underline m-b-15 "></div>
                            </div>
                        </div>
                        <!--Finalizando Subtitulo para formulario de una nueva distribucion-->

                        <!--Empezando Lista de equipos definidos para distribucion-->
                        <div class="row m-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <table id="data-table-equipos-distribucion" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>                                        
                                                <th class="all">Equipo</th>
                                                <th class="all">Serie</th>
                                                <th class="all">Cantidad</th>
                                                <th class="never">IdTipoEquipo</th>
                                                <th class="never">IdModelo</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--Finalizando Lista de equipos definidos para distribucion-->

                        <!--Empezando mensaje de tabla-->
                        <div class="row">
                            <div class="col-md-12 m-t-20">
                                <div class="alert alert-warning fade in m-b-15">                            
                                    Para eliminar el registro de la tabla solo tiene que dar click sobre fila para eliminarlo.                            
                                </div>                        
                            </div>
                        </div>
                        <!--Finalizando mensaje de tabla-->

                        <!--Empezando mensaje de error-->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="errorGuardarNuevoDestinoDistribucion"></div>
                            </div>
                        </div>
                        <!--Finalizando mensaje de error-->

                        <!--Empezando botones generales-->
                        <div class="row ">
                            <div class="col-md-12 text-center m-t-20">
                                <button id="btnGuardarMaterialDestinoDistribucion" type="button" class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i> Guardar Material Destino</button>
                            </div>
                        </div>
                        <!--Empezando botones generales-->
                    </div>

                </div>
                <!--Finalizando seccion para definir la distribucion de la recoleccion-->

                <!--Empezando seccion para definir el envido de una distribucion-->
                <div id="seccionEnvioDistribucion" class="hidden">     

                    <!--Empezando formulario tipo de envio-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fechaEnvio">Fecha y Hora *</label>
                                <div id="envioFechaDistribucion" class="input-group date">
                                    <input id="fechaEnvioDistribucion"  type="text" class="form-control" placeholder="Fecha" value=""/>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comoEnvia">¿Como se envia? *</label>
                                <select id="selectTipoEnvioDistribucion" class="form-control" style="width: 100%">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['ListaTiposEnvio'] as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>  
                            </div>
                        </div>
                    </div>                   
                    <!--Finalizando formulario tipo de envio-->

                    <!--Empezando Separador-->
                    <div id="tituloDistribucion" class="row m-t-20 hidden">
                        <div class="col-md-12">                    
                            <div class="subtitulo-contenido"><h5 class="elemento-1"><strong>Información  de Envio y Entrega</strong></h5></div>
                            <div class="underline m-b-15 "></div>
                        </div>
                    </div>
                    <!--Finalizando Separador-->

                    <!--Empezando pestañas de consolidado, paqueteria y entrega-->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills">
                                <li class="hidden"><a href="#ConsolidadoPaqueteriaDistribucion" data-toggle="tab"><span class="tipoEnvioDistribucion"></span></a></li>
                                <li class="hidden"><a href="#EntregaMaterialDistribucion" data-toggle="tab">Entrega</a></li>
                            </ul>
                            <div id="contenedorPestañasEnvioDistribucion" class="tab-content hidden">
                                <!--Empezando formulario para Consolidado o Paqueteria-->
                                <div id="ConsolidadoPaqueteriaDistribucion" class="tab-pane fade hidden" >
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tipoEnvioDistribucion"></label>
                                                <select id="selectListaTipoEnvioDistribucion" class="form-control" style="width: 100%" >
                                                    <option value="">Seleccionar</option>
                                                </select>                                            
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="datoTipoEnvio">Guia *</label>                                                
                                                <input id="inputDatoGuiaDistribucion" type="text" class="form-control"  placeholder="Ingrese el dato" value="<?php echo $valor; ?>"/>
                                            </div>
                                        </div>                               
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                    
                                            <div class="form-group">
                                                <label for="numeroSerie">Comentarios de Envío</label>                                                
                                                <textarea id="inputComentariosEnvioDistribucion" class="form-control " placeholder="Ingrese los comentarios" rows="3" ><?php echo $valor; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                    
                                            <div class="form-group">
                                                <label for="numeroSerie">Evidencias Envío *</label>
                                                <input id="evidenciaEnvioDistribucion"  name="evidenciasEnvioConsolidadoPaqueteriaDistribucion[]" type="file" multiple/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando formulario para Consolidado o Paqueteria-->

                                <!--Empezando formulario para Entrega de Envio-->
                                <div id="EntregaMaterialDistribucion" class="tab-pane fade hidden" >
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="listatipoenvio">Fecha y Hora *</label>
                                                <div id="entregaFechaDistribucion" class="input-group date">
                                                    <input id="entregaFechaEnvioDistribucion"  type="text" class="form-control" placeholder="Fecha" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>                                           
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quienRecibe">¿Quien Recibe? *</label>
                                                <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['NombreRecibe']; ?>
                                                <input id="inputRecibeEnvioDistribucion" type="text" class="form-control"  placeholder="Ingrese la persona que recibe" value="<?php echo $valor; ?>"/>
                                            </div>
                                        </div>                               
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                    
                                            <div class="form-group">
                                                <label for="comentarioEntrega">Comentarios de Entrega</label>
                                                <?php (empty($informacion['datosEnvio'])) ? $valor = '' : $valor = $informacion['datosEnvio']['ComentariosEntrega']; ?>
                                                <textarea id="inputComentarioEntregaEnvioDistribucion" class="form-control " placeholder="Ingrese los comentarios" rows="3"><?php echo $valor; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                    
                                            <div class="form-group">
                                                <label for="evidenciaEntrega">Evidencias Entrega *</label>
                                                <input id="evidenciaEntregaEnvioDistribucion"  name="evidenciasEntregaEnvioDistribucion[]" type="file" multiple/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Finalizando formulario para Entrega de Envio-->
                            </div>
                        </div>                
                    </div>
                    <!--Empezando pestañas de consolidado, paqueteria y entrega-->

                    <!--Empezando el mensaje de error-->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorGeneralEnvioDistribucion"></div>
                        </div>
                    </div>
                    <!--Finalizando el mensaje de error-->

                    <!--Empezando botones para guardar, concluir y cancelar destino-->
                    <div id="seccionBotonesDestinoDistribucion" class="row">
                        <div class="col-md-12 text-center m-t-20">
                            <button id="btnGuardarInformacionEnvioDistribucion" type="button" class="btn btn-sm btn-info" ><i class="fa fa-floppy-o"></i> Guardar Cambios</button>
                            <button id="btnConcluirDestinoEnvioDistribucion" type="button" class="btn btn-sm btn-success " ><i class="fa fa-unlock-alt"></i> Concluir Destino</button>
                            <button id="btnCancelarDestinoEnvioDistribucion" type="button" class="btn btn-sm btn-danger" >Cancelar Destino</button>
                        </div>
                    </div>
                    <!--Finalizando botones para guardar, concluir y cancelar destino-->

                </div>
                <!--Finalizando seccion para definir el envido de una distribucion-->
            </div>
        </div>
        <!--Finalizando la seccion Distribucion (Recolección y Distribución)-->

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
