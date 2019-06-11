<div id="seccionCuentas" class="content">
    <h1 class="page-header">Autorizar Movimientos</h1>
    <div id="panelAutorizaciones" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Pendientes por autorizar</h4>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="table-movimientos" class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th class="none">Id</th>
                                    <th class="all">Usuario</th>
                                    <th class="all">Tipo Cuenta</th>
                                    <th class="all">Concepto</th>
                                    <th class="all">Fecha</th>
                                    <th class="all">Tipo de Comprobante</th>
                                    <th class="all">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['Pendientes']) && !empty($datos['Pendientes'])) {
                                    foreach ($datos['Pendientes'] as $key => $value) {
                                        echo ""
                                            . "<tr>"
                                            . " <td>" . $value['Id'] . "</td>"
                                            . " <td>" . $value['Usuario'] . "</td>"
                                            . " <td>" . $value['TipoCuenta'] . "</td>"
                                            . " <td>" . $value['Concepto'] . "</td>"
                                            . " <td>" . $value['FechaRegistro'] . "</td>"
                                            . " <td>" . $value['TipoComprobante'] . "</td>"
                                            . " <td>$" . number_format(abs((float)$value['Monto']), 2) . "</td>"
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
    </div>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>