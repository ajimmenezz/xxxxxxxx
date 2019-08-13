<div id="seccionCuentas" class="content">
    <h1 class="page-header">Saldos técnico</h1>
    <div id="panelCuentas" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div id="listaCuentasAsignadas">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Técnicos asignados</h4>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-cuentas" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="none">IdTipoCuenta</th>
                                        <th class="none">IdUsuario</th>
                                        <th class="all">Persona</th>
                                        <th class="all">Tipo Cuenta</th>
                                        <th class="all">Saldo</th>
                                        <th class="all">Fecha de Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //var_dump($datos['SaldoTecnico']);
                                    //var_dump($_SESSION);
                                   // var_dump($datos);
                                    if (isset($datos['SaldoTecnico']) && !empty($datos['SaldoTecnico'])) {
                                        foreach ($datos['SaldoTecnico'] as $key => $value) {
                                            echo ""
                                                . "<tr>"
                                                . " <td>" . $value['idUsuario'] . "</td>"
                                                . " <td>" . $value['IdTipoCuenta'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['TipoCuenta'] . "</td>"
                                                . " <td>$" . number_format((float)$value['Saldo'], 2) . "</td>"                                                
                                                . " <td>" . $value['Fecha'] . "</td>"
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

<div id="seccionDetalleCuenta" class="content" style="display:none"></div>

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
