<div id="saldoTecnico" class="content">
    <h1 class="page-header">Saldos técnico</h1>
    <div id="panelCuentas" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div id="listaCuentasAsignadas">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Lista de técnicos</h4>
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
                                    <?php

                                    if (isset($datos['SaldoTecnico']) && !empty($datos['SaldoTecnico'])) {
                                        echo "<tr>";
                                        echo "<th class='none'>IdUsuario</th>";
                                        echo "<th class='all'>Técnico</th>";
                                        foreach ($datos['SaldoTecnico']['TiposSaldo'] as $key => $value1) {
                                            echo "" . " <th> SALDO " . $value1['Nombre'] . "</th>";
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </thead>
                                <tbody>
                                    <?php
                                    //                                    var_dump($datos['SaldoTecnico']);
                                    if (isset($datos['SaldoTecnico']) && !empty($datos['SaldoTecnico'])) {
                                        foreach ($datos['SaldoTecnico']['datosTecnico'] as $key => $value) {
                                            if ($value['E1'] == "" or $value['E1'] == Null or $value['E1'] == 0) {
                                                $value['E1'] = "0.00";
                                            }
                                            if ($value['E2'] == "" or $value['E2'] == Null or $value['E2'] == 0) {
                                                $value['E2'] = "0.00";
                                            }
                                            if ($value['E3'] == "" or $value['E3'] == Null or $value['E3'] == 0) {
                                                $value['E3'] = "0.00";
                                            }
                                            echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td> $" . number_format($value['E1'], 2, '.', ',') . "</td>"
                                                . " <td> $" . number_format($value['E2'], 2, '.', ',') . "</td>"
                                                . " <td> $" . number_format($value['E3'], 2, '.', ',') . "</td>"
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
<div id="movimientosID"></div>
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