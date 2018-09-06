<div id="seccion-servicio-sin-clasificar" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
    <!--Empezando Pestañas para definir la seccion-->
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
                    <li id="btnAgregarAvance"><a href="#"><i class="fa fa-plus"></i> Agregar Avance</a></li>
                    <li id="btnAgregarProblema"><a href="#"><i class="fa fa-plus"></i> Agregar Problema</a></li>
                    <?php echo $botonAgregarVuelta ?>
                    <li id="btnCancelarServicioSinEspecificar"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    <li id="btnNuevoServicioSinEspecificar"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar apoyo</a></li>
                </ul>
            </div>
            <label id="btnRegresarSeguimientoSinEspecificar" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#General" data-toggle="tab">Información General</a></li>
                <li class=""><a href="#Historial" data-toggle="tab">Historial</a></li>
                <li class=""><a href="#Notas" data-toggle="tab">Conversación</a></li>
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir la seccion-->

    <!--Empezando contenido de la informacion del servicio-->
    <div class="tab-content">

        <!--Empezando la seccion servicio sin clasificar-->
        <div class="tab-pane fade active in" id="General">
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
                        <input type="hidden" value="<?php echo $servicio; ?>" id="hiddenServicio" />
                    </div>
                </div>
                <!--Finalizando Separador--> 

                <!--Empezando informacion del servicio-->
                <div class="row">
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoSinClasificar"> Ticket: <strong><?php echo $datosServicio['Ticket']; ?></strong></label>
                        </div>    
                    </div> 
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoSinClasificar"> Atendido por: <strong id="nombreAtiende"><?php echo $datosServicio['NombreAtiende']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-4">          
                        <div class="form-group text-right">
                            <label for="seguimientoSinClasificar"> Fecha de Servicio: <strong><?php echo $datosServicio['FechaCreacion']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-2">          
                        <div class="form-group text-right">
                            <label for="seguimientosinClasificar"><strong id="detallesServicioSinClasificar"><a>+ Detalles</a></strong></label>                        
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
                                <label for="seguimientoSinClasificar"> Solicitud: <strong><?php echo $datosServicio['IdSolicitud']; ?></strong></label>
                            </div>    
                        </div> 
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label for="seguimientoSinClasificar"> Solicita: <strong><?php echo $datosServicio['NombreSolicita']; ?></strong></label>                        
                            </div>    
                        </div>
                        <div class="col-sm-3 col-md-4">          
                            <div class="form-group text-right">
                                <label for="seguimientoSinClasificar"> Fecha de Solicitud: <strong><?php echo $datosServicio['FechaCrecionSolicitud']; ?></strong></label>                        
                            </div>    
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if (!empty($datosServicio['descripcionSolicitud'])) {
                            ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="seguimientoSinClasificar"> Descripción Solicitud:</label>      
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


                <!--Empezando informacion del servicio-->
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

                <!--Empezando formulario servicio sin clasificar-->
                <form class="margin-bottom-0" id="formServicioSinClasificar" data-parsley-validate="true">
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

                    <!--Empezando Sucursal-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="seguimientoSinClasificar">Sucursal</label>
                                <select id="selectSucursalesSinClasificar" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($sucursales as $item) {
                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
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
                                <label for="seguimientoSinClasificar">Descripción de la Solución *</label>
                                <?php (empty($informacionServicioGeneral[0]['Descripcion'])) ? $descripcion = '' : $descripcion = $informacionServicioGeneral[0]['Descripcion']; ?>
                                <textarea id="inputDescripcionSinClasificar" class="form-control " placeholder="Ingrese una descripción" rows="3" ><?php echo $descripcion; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <!--Empezando Decripcion-->
                    <div class="row">
                        <div class="col-md-12">                                    
                            <div class="form-group">
                                <label for="seguimientoSinClasificar">Evidencias</label>
                                <input id="evidenciaSinClasificar"  name="evidenciasSinClasificar[]" type="file" multiple/>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando-->

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorGeneralServicioSinClasificar"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <a id="btnGuardarServicioSinClasificar" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <a id="btnConcluirServicioSinClasificar" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>                            
                            </div>
                        </div>
                    </div>

                </form>
                <!--Finalizando formulario servicio sin clasificar-->
            </div>
        </div>
        <!--Finalizando la seccion de servicio sin clasificar-->

        <!--Empezando la seccion Notas-->
        <div class="tab-pane fade " id="Historial">            
            <div class="panel-body">
                <!-- begin timeline -->
                <ul id="listaHistorial" class="timeline">
                    <?php
                    foreach ($avanceServicio as $key => $item) {
                        $arrayArchivos = explode(",", $item['Archivos']);
                        $arrayFecha = explode(' ', $item['Fecha']);
                        ?>
                        <li>

                            <!-- begin timeline-time -->
                            <div class="timeline-time">
                                <span class="date"><?php echo $arrayFecha[0] ?></span>
                                <span class="time"><?php echo $arrayFecha[1] ?></span>
                            </div>
                            <!-- end timeline-time -->

                            <!-- begin timeline-icon -->
                            <div class="timeline-icon">
                                <?php (($item['IdTipo'] === '1')) ? $icono = 'fa fa-check' : $icono = 'fa fa-ban'; ?>
                                <?php (($item['IdTipo'] === '1')) ? $colorIcono = '' : $colorIcono = 'background:#ff5b57'; ?>
                                <a href="javascript:;" style="<?php echo $colorIcono ?>"><i class="<?php echo $icono ?>"></i></a>
                            </div>
                            <!-- end timeline-icon -->

                            <!-- begin timeline-body -->
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <div class="row">
                                        <div class="col-md-9 col-xs-9">
                                            <?php (empty($item['Foto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $item['Foto']; ?>
                                            <span class="userimage"><img src="<?php echo $foto ?>" alt="" /></span>
                                            <span class="username"><?php echo $item['Usuario'] ?></span>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                            <?php (($item['IdTipo'] === '1')) ? $colorTituloAvance = 'color:#337ab7' : $colorTituloAvance = 'color:#ff5b57'; ?>
                                            <span class="pull-right text-muted"><h4 style="<?php echo $colorTituloAvance ?>"><?php echo $item['TipoAvance'] ?></h4></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="timeline-content">
                                    <p>
                                        <?php echo $item['Descripcion'] ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <?php
                                    foreach ($arrayArchivos as $key => $value) {
                                        if ($value != '') {
                                            echo '<div class="thumbnail-pic m-5 p-5">';
                                            $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                                            switch ($ext) {
                                                case 'png': case 'jpeg': case 'jpg': case 'gif':
                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                    break;
                                                case 'xls': case 'xlsx':
                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                    break;
                                                case 'doc': case 'docx':
                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                    break;
                                                case 'pdf':
                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                    break;
                                                default :
                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                    break;
                                            }
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                                $contadorTabla = count($item[0]['tablaEquipos']);
                                if ($contadorTabla > 0) {
                                    ?>
                                    <div class="timeline-footer">
                                        <br>
                                        <div class="row m-r-10">
                                            <div class="col-md-12">
                                                <h4 class="m-t-10">Lista de Equipos o Materiales</h4>
                                            </div>
                                        </div>

                                        <!--Empezando Separador-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="underline m-b-15 m-t-15"></div>
                                            </div>
                                        </div>
                                        <!--Finalizando Separador-->

                                        <div class="table-responsive">
                                            <table id="data-table-avance-servicio-<?php echo $item['Id']; ?>" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="never">Tipo Item</th>
                                                        <th class="all">Descripción</th>
                                                        <th class="all">Serie</th>
                                                        <th class="all">Cantidad</th>
                                                        <th class="all">Tipo de Falla</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($item[0]['tablaEquipos'] as $key => $value) {
                                                        switch ($value['IdItem']) {
                                                            case '1':
                                                                $tipoItem = 'Equipo';
                                                                break;
                                                            case '2':
                                                                $tipoItem = 'Material';
                                                                break;
                                                            case '3':
                                                                $tipoItem = 'Refacción';
                                                        }
                                                        echo '<tr>';
                                                        echo '<td>' . $tipoItem . '</td>';
                                                        echo '<td>' . $value['EquipoMaterial'] . '</td>';
                                                        echo '<td>' . $value['Serie'] . '</td>';
                                                        echo '<td>' . $value['Cantidad'] . '</td>';
                                                        echo '<td>' . $value['TipoDiagnostico'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <!-- end timeline-body -->
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        <!-- begin timeline-icon -->
                        <div class="timeline-icon">
                            <a href="javascript:;" style="background:#707478"><i class="fa fa-spinner"></i></a>
                        </div>
                        <!-- end timeline-icon -->
                        <!-- begin timeline-body -->
                        <div class="timeline-body">
                            Fin del Historial...
                        </div>
                        <!-- begin timeline-body -->
                    </li>
                </ul>
                <!-- end timeline -->
            </div>
        </div>
        <!--Finalizando la seccion Notas-->

        <!--Empezando la seccion Notas-->
        <div class="tab-pane fade " id="Notas">            
            <div class="panel-body">
                <?php echo $notas; ?>
            </div>
        </div>
        <!--Finalizando la seccion Notas-->

        <!--Finalizando contenido de la informacion del servicio-->
    </div>
</div>
