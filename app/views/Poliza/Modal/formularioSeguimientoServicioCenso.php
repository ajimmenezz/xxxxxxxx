<input type="hidden" value="<?php echo $servicio; ?>" id="hiddenServicio" />
<div id="seccion-servicio-censo" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
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
                    <li id="btnRestaurarCenso"><a href="#"><i class="fa fa-undo"></i> Restaurar Último Censo</a></li>
                    <li id="btnCancelarServicioSeguimiento"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnDocumentacionFirma"><a href="#"><i class="fa fa-pencil-square-o"></i> Firmar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    <li id="btnNuevoServicioSeguimiento"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnReasignarServicio"><a href="#"><i class="fa fa-mail-reply-all"></i> Reasignar Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar apoyo</a></li>
                </ul>
            </div>
            <label id="btnRegresarSeguimientoCenso" class="btn btn-success btn-xs">
                <i class="fa fa fa-reply"></i> Regresar
            </label>                                    
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <!-- begin nav-tabs -->
        <div class="tab-overflow">
            <ul class="nav nav-tabs nav-tabs-inverse">
                <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                <li class="active"><a href="#General" data-toggle="tab">Información General</a></li>
                <!--<li class="hidden"><a href="#Datos" data-toggle="tab">Datos</a></li>-->
                <li class="hidden"><a href="#AreaPuntos" data-toggle="tab">Puntos por Área</a></li>
                <li class="hidden"><a href="#EquiposPunto" data-toggle="tab">Equipos por Punto</a></li>
                <li class=""><a href="#DocumentacionFirmada" data-toggle="tab">Documentación Firmada</a></li>
                <!--<li class=""><a href="#Notas" data-toggle="tab">Conversación</a></li>-->
                <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
    </div>
    <!--Finalizando Pestañas para definir la seccion-->

    <!--Empezando contenido de la informacion del servicio-->
    <div class="tab-content">

        <!--Empezando la seccion servicio Censo-->
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
                    </div>
                </div>
                <!--Finalizando Separador--> 

                <!--Empezando informacion del servicio-->
                <div class="row">
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoCenso"> Ticket: <strong><?php echo $datosServicio['Ticket']; ?></strong></label>
                        </div>    
                    </div> 
                    <div class="col-sm-3 col-md-3">          
                        <div class="form-group">
                            <label for="seguimientoCenso"> Atendido por: <strong><?php echo $datosServicio['NombreAtiende']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-4">          
                        <div class="form-group text-right">
                            <label for="seguimientoCenso"> Fecha de Servicio: <strong><?php echo $datosServicio['FechaCreacion']; ?></strong></label>                        
                        </div>    
                    </div>
                    <div class="col-sm-3 col-md-2">          
                        <div class="form-group text-right">
                            <label for="seguimientoCenso"><strong id="detallesServicioCenso"><a>+ Detalles</a></strong></label>                        
                        </div>    
                    </div>
                </div>
                <div id="masDetalles" class="hidden">
                    <div class="row">
                        <div class="col-md-12">          
                            <div class="form-group">
                                <label for="seguimientoCenso"> Descripción Servicio:</label>      
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
                                <label for="seguimientoCenso"> Solicitud: <strong><?php echo $datosServicio['IdSolicitud']; ?></strong></label>
                            </div>    
                        </div> 
                        <div class="col-sm-3 col-md-3">          
                            <div class="form-group">
                                <label for="seguimientoCenso"> Solicita: <strong><?php echo $datosServicio['NombreSolicita']; ?></strong></label>                        
                            </div>    
                        </div>
                        <div class="col-sm-3 col-md-4">          
                            <div class="form-group text-right">
                                <label for="seguimientoCenso"> Fecha de Solicitud: <strong><?php echo $datosServicio['FechaCrecionSolicitud']; ?></strong></label>                        
                            </div>    
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        if (!empty($datosServicio['descripcionSolicitud'])) {
                            ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="seguimientoCenso"> Descripción Solicitud:</label>      
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

                <!--Empezando formulario servicio censo datos generales-->
                <form class="margin-bottom-0" id="formServicioCenso" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos Generales</h3>
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
                                <label for="seguimientoCenso">Sucursal *</label>
                                <select id="selectSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
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
                                <label for="seguimientoCenso">Descripción *</label>
                                <?php (empty($informacionDatosGenerales[0]['Descripcion'])) ? $descripcion = '' : $descripcion = $informacionDatosGenerales[0]['Descripcion']; ?>
                                <textarea id="inputDescripcionCenso" class="form-control " placeholder="Ingrese una descripción del Servicio" rows="3" ><?php echo $descripcion; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Finalizando--> 

                    <div class="row m-t-10">
                        <!--Empezando error--> 
                        <div class="col-md-12">
                            <div class="errorDatosGeneralesCenso"></div>
                        </div>
                        <!--Finalizando Error-->
                        <div id="divBotonesServicioCenso" class="col-md-12"> 
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarDatosGenerales" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>
                                <a id="btnConcluirServicioCenso" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>                            
                            </div>
                        </div>
                        <div id="divGuardarCambiosServicioCenso" class="col-md-12 hidden">
                            <div class="form-group text-center">
                                <br>
                                <a id="btnGuardarCambiosServicioCenso" href="javascript:;" class="btn btn-inverse m-r-5 "><i class="fa fa-unlock-alt"></i> Guardar cambios y Concluir servicio</a>                            
                            </div>
                        </div>
                    </div>

                </form>
                <!--Finalizando formulario servicio censo-->

            </div>
        </div>
        <!--Finalizando la seccion de servicio censo-->

        <div class="tab-pane fade" id="AreaPuntos">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Puntos por Área de Atención</h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div id="contentAreaPuntos"></div>                
            </div>
        </div>

        <div class="tab-pane fade" id="EquiposPunto">            
            <div class="panel-body">                
                <div id="contentEquiposPunto"></div>                
                <div id="formularioCapturaCenso" style="display:none"></div>                
            </div>
        </div>

        <!--Empezando la seccion Datos-->
        <!--        <div class="tab-pane fade " id="Datos">            
                    <div class="panel-body">
        
                        Empezando la tabla de servcicio de Censos
                        <div class="row">                
                            <div class="col-md-12">
                                <table id="data-table-censo-modelos" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th class="all">Área Atención</th>
                                            <th class="all">Punto</th>
                                            <th class="all">Modelo</th>
                                            <th class="all">Serie</th>
                                            <th class="all">Número Terminal</th>
                                            <th class="never">IdAreaAtencion</th>                                
                                            <th class="never">IdModelo</th>                                
                                        </tr>
                                    </thead>
                                    <tbody>
        <?php
//                                if (!empty($informacionDatosCenso)) {
//                                    foreach ($informacionDatosCenso as $key => $value) {
//                                        echo '<tr>';
//                                        echo '<td>' . $value['Sucursal'] . '</td>';
//                                        echo '<td>' . $value['Punto'] . '</td>';
//                                        echo '<td>' . $value['Linea'] . ' - ' . $value['Marca'] . ' - ' . $value['Modelo'] . '</td>';
//                                        echo '<td>' . $value['Serie'] . '</td>';
//                                        echo '<td>' . $value['Extra'] . '</td>';
//                                        echo '<td>' . $value['IdArea'] . '</td>';
//                                        echo '<td>' . $value['IdModelo'] . '</td>';
//                                        echo '</tr>';
//                                    }
//                                }
        ?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        Finalizando la tabla de servcicio de Censos
        
                        Empezando mensaje de tabla
                        <div class="row">
                            <div class="col-md-12 m-t-20">
                                <div class="alert alert-warning fade in m-b-15">                            
                                    Para eliminar el registro de la tabla solo tiene que dar click sobre la fila para eliminarlo.                            
                                </div>                        
                            </div>
                        </div>
                        Finalizando mensaje de tabla
        
                        Empezando formulario servicio censo datos Censo
                        <form class="margin-bottom-0" id="formServicioCenso" data-parsley-validate="true">
                            <div class="row m-r-10">
                                <div class="col-md-10 col-xs-8">
                                    <h3 class="m-t-10">Datos del Censo</h3>
                                </div>
                            </div>
        
                            Empezando Separador
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>
                            Finalizando 
        
                            Empezando Area de Atencion, Punto y Modelo
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="seguimientoCenso">Área de Atención *</label>
                                        <select id="selectAreasAtencion" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
        <?php
        foreach ($areasAtencion as $item) {
//                                        echo '<option value="' . $item['Id'] . '">' . $item['Nombre'] . '</option>';
        }
        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">                                           
                                        <label for="seguimientoCenso">Punto *</label>
                                        <input id="inputPuntoCenso" type="number" class="form-control"  placeholder="Cantidad"/>
                                    </div>                               
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seguimientoCenso">Modelo *</label>
                                        <select id="selectModelosCenso" class="form-control" style="width: 100%" data-parsley-required="true">
                                            <option value="">Seleccionar</option>
        <?php
        foreach ($modelos as $item) {
//                                        echo '<option value="' . $item['IdMod'] . '">' . $item['Linea'] . ' - ' . $item['Marca'] . ' - ' . $item['Modelo'] . '</option>';
        }
        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            Finalizando
        
        
                            Empezando Serie e Numero de termina
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seguimientoCenso">Serie *</label>
                                        <input type="text" class="form-control" id="inputSerieCenso" placeholder="Serie" style="width: 100%" data-parsley-required="true"/>                            
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seguimientoCenso">Número de Terminal *</label>
                                        <input type="text" class="form-control" id="inputNumeroTerminalCenso" placeholder="ABCDFG99" style="width: 100%" data-parsley-required="true"/>                            
                                    </div>
                                </div>
                            </div>
                            Finalizando 
        
                            <div class="row">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        Para guardar el nuevo dato necesita dar clic en el botón Agregar para colocarlo en la tabla y después dar clic en el botón Guardar para tener el nuevo registro.                             
                                    </div>                        
                                </div>
                            </div>
        
                            <div class="row m-t-10">
                                Empezando error 
                                <div class="col-md-12">
                                    <div class="errorDatosCenso"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnAgregaEquipoCenso" href="javascript:;" class="btn btn-success m-r-5"><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                                Finalizando Error
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <br>
                                        <a id="btnGuardarServicioCenso" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar</a>                            
                                    </div>
                                </div>
                            </div>
        
                        </form>
                        Finalizando formulario servicio censo
                    </div>
                </div>-->
        <!--Finalizando la seccion Datos-->

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

        <!--Finalizando contenido de la informacion del censo-->
    </div>
</div>