<div id="divListaEquiposEnviados" class="content">
    <input type="hidden" id="IdPerfil" />
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header">Seguimiento Equipos Almacén o Solicitados</h1>
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <label class="btnRegresarTabla btn btn-success hidden">
                <i class="fa fa-reply"></i> Regresar
            </label> 
            <label id="exportPdfButton" data-id="" class="btn btn-danger hidden">
                <i class="fa fa-file-pdf-o"></i> Exportar PDF
            </label> 
        </div>
    </div>
    <div id="panelTablaEquiposEnviados" class="panel panel-inverse">
        <div class="panel-heading">    
            <h4 class="panel-title">Seguimiento Equipos Almacén o Solicitados</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h4>Lista de Equipos Enviados o Solicitados</h4>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group text-right" id="botonNuevoValidacion">
                        <?php $botonNuevo = ($datos['permisoNuevoRegistro']) ? "" : "hidden"; ?>
                        <a href="javascript:;" class="btn btn-success <?php echo $botonNuevo; ?>" id="agregarEquipo"><i class="fa fa-plus"></i> Nueva Solicitud</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="underline m-b-10"></div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorTabla"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="errorFormulario"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="lista-equipos-enviados-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Servicio</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Equipo o Refacción</th>
                                    <th class="all">Fecha</th>
                                    <th class="never">IdEstatus</th>
                                    <th class="all">Estatus</th>
                                    <th class="never">IdRefaccion</th>
                                    <th class="all">Refaccion</th>
                                    <th class="all">Tipo Movimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($datos['tablaEquipos']['code'] === 200) {
                                    foreach ($datos['tablaEquipos']['datosTabla'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['IdServicio'] . '</td>';
                                        echo '<td>' . $value['Ticket'] . '</td>';
                                        echo '<td>' . $value['NombreSucursal'] . '</td>';
                                        echo '<td>' . $value['Equipo'] . '</td>';
                                        echo '<td>' . $value['FechaValidacion'] . '</td>';
                                        echo '<td>' . $value['IdEstatus'] . '</td>';
                                        echo '<td>' . $value['NombreEstatus'] . '</td>';
                                        echo '<td>' . $value['IdRefaccion'] . '</td>';
                                        echo '<td>' . $value['Refaccion'] . '</td>';
                                        echo '<td>' . $value['TipoMovimiento'] . '</td>';
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
    <div id="seccionPanelEspera" class="hidden"></div>
    <div id="seccionFormulariosRecepcionTecnico" class="hidden"></div>
    <div id="seccionFormulariosEnvSegLog" class="hidden"></div>
    <div id="seccionFormulariosRecepcionLogistica" class="hidden"></div>
    <div id="seccionFormulariosRecepcionAlmacenRegreso" class="hidden"></div>
    <div id="seccionFormulariosRevisionHistorial" class="hidden"></div>
    <div id="seccionFormulariosRecepcionLaboratorio" class="hidden"></div>
    <div id="seccionFormulariosRecepcionAlmacen" class="hidden"></div>
    <div id="seccionFormulariosAsignacionGuiaLogistica" class="hidden"></div>
    <div id="seccionFormulariosAsignacionGuia" class="hidden"></div>
    <div id="seccionFormulariosGuiaLogistica" class="hidden"></div>
    <div id="seccionFormulariosSinGuia" class="hidden"></div>
    <div id="seccionFormulariosGuia" class="hidden"></div>
    <div id="seccionFormulariosValidacion" class="hidden"></div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSolicitarCotizacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button id="btnCancelarSolicitarCotizacion" type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button id="btnAceptarSolicitarCotizacion" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Solicitar</button>
            </div>
        </div>
    </div>
</div>