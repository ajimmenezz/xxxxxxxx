<div id="panel-catalogo-checklist">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <h1 class="page-header">Seguimiento Checklist</h1>
            <input type="hidden" value="<?php echo $datosServicio['IdServicio'] ?>" id="hiddenServicio" />
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li id="btnCancelarServicioChecklist"><a href="#"><i class="fa fa-times"></i> Cancelar Servicio</a></li>
                    <li id="btnGeneraPdfServicio"><a href="#"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a></li>
                    <li id="btnNuevoServicio"><a href="#"><i class="fa fa-plus"></i> Nuevo Servicio</a></li>
                    <li id="btnNuevaSolicitud"><a href="#"><i class="fa fa-puzzle-piece"></i> Solicitar Apoyo</a></li>
                </ul>
            </div>
            <label id="btnRegresarSeguimiento" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>

    <div id="seguimiento-checklist" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
        <div class="panel-heading p-0">
            <div class="panel-heading-btn m-r-10 m-t-10">                                 
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse" id="tab-checklist">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#informacionRevision" data-toggle="tab">Información General</a></li>
                    <li class="disabled"><a href="#revisionArea">Revisión Fisica Área</a></li>
                    <li class="disabled"><a href="#revisionPunto">Revisión Fisica Punto</a></li>
                    <li class="disabled"><a href="#revisionTecnica">Revisión Tecnica</a></li>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in panel-body" id="informacionRevision">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>Información General</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                        <?php
                        if (!empty($datosServicio['Folio'])) {
                            ?>
                            <h5 id='folioSeguimiento' class="m-t-20"> Folio <a  TITLE="Muestra la informacion de Service Desk"><?php echo $datosServicio['Folio']; ?></a></h5>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 underline m-b-10"></div>
                </div>
                <div Id="errorMessage"></div>
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
                            <label><strong id="detallesServicioChecklist"><a>+ Detalles</a></strong></label>                        
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>

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
                <form class="margin-bottom-0" id="formServicioPreventivoSalas4xd" data-parsley-validate="true">
                    <div class="row m-r-10">
                        <div class="col-md-12">
                            <h3 class="m-t-10">Datos</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectSucursales">Sucursal *</label>
                                <select id="selectSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                    foreach ($informacion['sucursalesXSolicitudCliente'] as $item) {
                                        $select = ($informacion['sucursal'] == $item['Id']) ? 'selected' : '';
                                        echo '<option value="' . $item['Id'] . '" ' . $select . '>' . $item['Nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div id="errorInformacionGeneral"></div>
                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <br>
                                <a id="guardarSucursalChecklist" href="javascript:;" class="btn btn-primary m-r-5 "><i class="fa fa-floppy-o"></i> Guardar Información</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <br>
                                <a id="concluirServicioChecklist" href="javascript:;" class="btn btn-danger m-r-5 "><i class="fa fa-unlock-alt"></i> Concluir Servicio</a>
                            </div>
                        </div>    
                    </div>

                </form>
            </div>
            <div class="tab-pane fade panel-body " id="revisionArea">
                <div class="row panel panel-inverse" data-sortable-id="form-stuff-3">
                    <ul id="" class="nav nav-pills categoriaRevisionArea">
                        <?php
                        foreach ($catalogoCategorias as $value) {
                            if ($value['Flag'] == 1) {
                                echo '<li><a id="categoria-' . $value['Id'] . '" href="#categoria-' . $value['Id'] . '" data-toggle="tab" data-id-categoria = "' . $value['Id'] . '">' . $value['Nombre'] . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    <div id="errorRevisionArea" class="row"></div>
                    <div id="listaPregunta" class="table-responsive hidden">
                        <table id="tabla-categorias" class="table table-striped table-bordered table-condensed" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Área Atención</th>
                                    <th class="never">Id área atencion</th>
                                    <th class="all">Concepto o Pregunta</th>
                                    <th class="all"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="guardarListaPregunta" class="row m-t-15 hidden">
                        <div class="col-md-6 col-xs-6 text-right">
                            <label id="guardarRevisionFisicaArea" class="btn btn-success">
                                <i class="fa fa-floppy-o">&nbsp;</i>Guardar información
                            </label> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade panel-body " id="revisionPunto">
                <div class="row panel panel-inverse" data-sortable-id="form-stuff-3">
                    <ul  class="nav nav-pills categoriaRevisionPunto">
                        <?php
                        foreach ($catalogoCategorias as $value) {
                            if ($value['Flag'] == 1) {
                                echo '<li><a id="categoriaPunto-' . $value['Id'] . '" href="#categoriaPunto-' . $value['Id'] . '" data-toggle="tab" data-id-categoria-punto = "' . $value['Id'] . '">' . $value['Nombre'] . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    <div id="errorRevisionPunto"></div>
                    <!--<div class="row">-->
                    <div class="row col-md-12 hidden" id="checklistRevisionPunto">
                        <div class="area"></div><br/>
                    </div>
                    <!--</div>-->
                </div>
            </div>
            <div class="tab-pane fade panel-body " id="revisionTecnica">
                <div class="row panel panel-inverse" data-sortable-id="form-stuff-3">                    
                    <div id="revisionTecnica"></div>
                </div>
            </div>
        </div>
    </div>
</div>
