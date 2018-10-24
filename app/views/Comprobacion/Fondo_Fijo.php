<!--Empezando seccion para mostrar los detalles del fondo fijo -->
<div id="seccionDetallesFondoFijo" class="content">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header">Comprobación de Fondo Fijo</h1>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
            <div class="btn-group m-r-40 p-r-20">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">                
                    <li id="btnRegistrarComprobante"><a href="#"><i class="fa fa-money" aria-hidden="true"></i> Registrar Comprobante</a></li>
            </div>            
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
                            <h4><?php echo $datos['usuario']; ?></h4>
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
                    <div class="row">
                        <div class="col-md-offset-6 col-md-3 col-sm-offset-4 col-sm-4 col-xs-offset-0 col-xs-6">
                            <div class="widget widget-stats bg-orange">
                                <div class="stats-icon"><i class="fa fa-money"></i></div>
                                <div class="stats-info">
                                    <h4 class="f-w-600">POR AUTORIZAR</h4>
                                    <p class="f-w-600">$<?php echo number_format($datos['xautorizar'], 2, '.', ','); ?></p>	
                                </div>                            
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"><i class="fa fa-money"></i></div>
                                <div class="stats-info">
                                    <h4 class="f-w-600">SALDO ACTUAL</h4>
                                    <p class="f-w-600">$<?php echo number_format($datos['saldo'], 2, '.', ','); ?></p>	
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
                                    <th class="all">Monto</th>
                                    <th class="all">Saldo</th>
                                    <th class="all">Ticket</th>
                                    <th class="all">Tipo Comprobante</th>
                                    <th class="all">Estatus</th>                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['listaComprobaciones']) && count($datos['listaComprobaciones']) > 0) {
                                    foreach ($datos['listaComprobaciones'] as $key => $value) {
                                        $classMonto = ($value['Monto'] > 0) ? "text-success" : "text-danger";
                                        $classSaldo = ($value['Saldo'] > 0) ? "text-success" : "text-danger";
                                        $saldoFila = ($value['IdEstatus'] == 7) ? '$' . $value['Saldo'] : 'N.A.';

                                        echo ''
                                        . '<tr>'
                                        . '<td>' . $value['Id'] . '</td>'
                                        . '<td class="text-center">' . $value['Fecha'] . '</td>'
                                        . '<td class="text-center">' . $value['FechaMovimiento'] . '</td>'
                                        . '<td class="f-w-700 f-s-14 ' . $classMonto . '">' . $value['Nombre'] . '</td>'
                                        . '<td class="text-center">' . $value['Extraordinario'] . '</td>'
                                        . '<td class="text-center f-w-700 f-s-14 ' . $classMonto . '">$' . $value['Monto'] . '</td>'
                                        . '<td class="text-center f-w-700 f-s-14 ' . $classSaldo . '">' . $saldoFila . '</td>'
                                        . '<td class="text-center">' . $value['Ticket'] . '</td>'
                                        . '<td>' . $value['TipoComprobante'] . '</td>'
                                        . '<td>' . $value['Estatus'] . '</td>'
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
</div>
<!--Finalizando seccion para mostrar los detalles del fondo fijo -->

<!--Empezando seccion para mostrar el formulario de los depositos -->
<div id="seccionRegistrarDeposito" class="content" style="display: none"></div>
<!--Finalizando seccion para mostrar el formulario de los depositos -->