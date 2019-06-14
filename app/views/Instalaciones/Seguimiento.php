<div id="seccionPendientes" class="content">
    <h1 class="page-header">Seguimiento Instalaciones</h1>
    <div id="panelSeguimiento" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div id="listaInstalaciones">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Instalaciones Pendientes</h4>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-servicios" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="none">Id Servicio</th>
                                        <th class="all">Ticket</th>
                                        <th class="all">Folio SD</th>
                                        <th class="all">Tipo Servicio</th>
                                        <th class="all">Atiende</th>
                                        <th class="all">Sucursal</th>
                                        <th class="all">Fecha</th>
                                        <th class="all">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($datos['Pendientes']) && !empty($datos['Pendientes'])) {
                                        foreach ($datos['Pendientes'] as $key => $value) {
                                            echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Ticket'] . "</td>"
                                                . " <td>" . $value['Folio'] . "</td>"
                                                . " <td>" . $value['TipoServicio'] . "</td>"
                                                . " <td>" . $value['Atiende'] . "</td>"
                                                . " <td>" . $value['Sucursal'] . "</td>"
                                                . " <td>" . $value['FechaCreacion'] . "</td>"
                                                . " <td>" . $value['Estatus'] . "</td>"
                                                . "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="formularioDepositar" style="display:none"></div>
        </div>
    </div>
</div>

<div id="seccionFormulario" class="content" style="display:none"></div>

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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>