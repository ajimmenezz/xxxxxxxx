<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Comprobación de Fondo Fijo</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Acciones <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php
                if (in_array(297, $permisos)) {
                    ?>
                    <li id="btnRegistrarDeposito"><a href="#"><i class="fa fa-money" aria-hidden="true"></i> Registrar Depósito</a></li>
                    <li id="btnCorteGasolina"><a href="#"><i class="fa fa-car" aria-hidden="true"></i> Ajustar Gasolina</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label> 
    </div>
</div>    
<div id="panelDetallesFondoFijo" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <h4 class="panel-title">Comprobación de Fondo Fijo</h4>
    </div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row"> 
            <!--Empezando error--> 
            <div class="col-md-12">
                <div class="errorMessageFondoFijo"></div>
            </div>
            <!--Finalizando Error-->
            <div class="col-md-12">  
                <div class="form-group">
                    <div class="col-md-12">
                        <h4><?php echo $usuario; ?></h4>
                    </div>
                    <div class="col-md-12">
                        <div class="underline m-b-15 m-t-5"></div>
                    </div>
                    <!--Finalizando Separador-->
                </div>    
            </div> 
        </div>
        <div class="row m-t-0">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-3 col-sm-4 col-xs-6">
                    <div class="widget widget-stats bg-red">
                        <div class="stats-icon"><i class="fa fa-money"></i></div>
                        <div class="stats-info">
                            <h4 class="f-w-600">SALDO RECHAZADO COBRABLE</h4>
                            <p class="f-w-600">$<?php echo number_format($saldoRechazado, 2, '.', ','); ?></p>	
                        </div>                            
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon"><i class="fa fa-money"></i></div>
                        <div class="stats-info">
                            <h4 class="f-w-600">SALDO GASOLINA</h4>
                            <p class="f-w-600">$<?php echo number_format($saldoGasolina, 2, '.', ','); ?></p>	
                        </div>                            
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="widget widget-stats bg-orange">
                            <div class="stats-icon"><i class="fa fa-money"></i></div>
                            <div class="stats-info">
                                <h4 class="f-w-600">POR AUTORIZAR</h4>
                                <p class="f-w-600">$<?php echo number_format($xautorizar, 2, '.', ','); ?></p>	
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="widget widget-stats bg-green">
                            <div class="stats-icon"><i class="fa fa-money"></i></div>
                            <div class="stats-info">
                                <h4 class="f-w-600">SALDO ACTUAL</h4>
                                <p class="f-w-600">$<?php echo number_format($saldo, 2, '.', ','); ?></p>	
                            </div>                            
                        </div>
                    </div>

                </div>
                <div class="table-responsive m-t-0">    
                    <table id="table-comprobaciones-fondo-fijo" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Fecha Autorización</th>
                                <th class="all">Fecha Movimiento</th>
                                <th class="all">Concepto</th>
                                <th class="all">¿Extraordinario?</th>                                
                                <th class="all">¿Dentro de presupuesto?</th>                                
                                <th class="all">Monto</th>
                                <th class="all">Saldo Fondo</th>
                                <th class="all">Saldo Gasolina</th>
                                <th class="all">Ticket</th>
                                <th class="all">Tipo Comprobante</th>
                                <th class="all">Estatus</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($listaComprobaciones) && count($listaComprobaciones) > 0) {
                                foreach ($listaComprobaciones as $key => $value) {
                                    $classMonto = ($value['Monto'] > 0) ? "text-success" : "text-danger";
                                    $classSaldo = ($value['Saldo'] > 0) ? "text-success" : "text-danger";
                                    $classSaldoGasolina = ($value['SaldoGasolina'] > 0) ? "text-success" : "text-danger";
                                    $saldoFila = ($value['IdEstatus'] == 7 || ($value['IdEstatus'] == 10 && $value['Cobrable'] == 1)) ? '$' . (float) $value['Saldo'] : 'N.A.';
                                    $saldoGasolina = ($value['IdEstatus'] == 7 || ($value['IdEstatus'] == 10 && $value['Cobrable'] == 1)) ? '$' . (float) $value['SaldoGasolina'] : 'N.A.';
                                    $estatus = ($value['IdEstatus'] == 10 && $value['Cobrable'] == 1) ? $value['Estatus'].' COBRABLE' : $value['Estatus'];

                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td class="text-center">' . $value['Fecha'] . '</td>'
                                    . '<td class="text-center">' . $value['FechaMovimiento'] . '</td>'
                                    . '<td class="f-w-700 f-s-14 ' . $classMonto . '">' . $value['Nombre'] . '</td>'
                                    . '<td class="text-center">' . $value['Extraordinario'] . '</td>'
                                    . '<td class="text-center">' . $value['EnPresupuesto'] . '</td>'
                                    . '<td class="text-center f-w-700 f-s-14 ' . $classMonto . '">$' . $value['Monto'] . '</td>'
                                    . '<td class="text-center f-w-700 f-s-14 ' . $classSaldo . '">' . $saldoFila . '</td>'
                                    . '<td class="text-center f-w-700 f-s-14 ' . $classSaldoGasolina . '">' . $saldoGasolina . '</td>'
                                    . '<td class="text-center">' . $value['Ticket'] . '</td>'
                                    . '<td>' . $value['TipoComprobante'] . '</td>'
                                    . '<td>' . $estatus . '</td>'
                                    . '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>
