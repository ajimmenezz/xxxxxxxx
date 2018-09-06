<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Suscripciones Activas</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="panelReporte" class="panel panel-inverse">        
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
        </div>
        <h4 class="panel-title">Sucripciones Activas</h4>        
    </div>
    <div class="panel-body">        
        <div class="row"> 
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Lista de suscripciones activas</h4>
                </div>
            </div>                                    
            <div class="col-md-6 col-sm-6 col-xs-12">
                <button id="btnExportarExcel" class="btn btn-success pull-right f-w-700">Exportar Excel <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data-table-detalles" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">ID Suscripcion</th>
                                <th>ID Cliente</th>
                                <th>Cliente</th>
                                <th>Contrato</th>
                                <th>Paquete Incluido</th>
                                <th>Precio Real</th>
                                <th>Descuento</th>
                                <th>Precio por Contrato</th>
                                <th>Fecha de Contratación</th>
                                <th>Mes Actual</th>
                                <th>Fecha Último Cobro</th>                                
                                <th>ID Cargo OpenPay</th>                                
                                <th>Fecha Venta en MB</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $clientes = $clientes['clientes'];
                            $contratos = $contratos['contracts'];
                            foreach ($suscripciones['GetFullSubscriptions'] as $key => $value) {
                                echo ""
                                . "<tr>"
                                . "<td>" . $value['Id'] . "</td>"
                                . "<td>" . $value['IdClienteMB'] . "</td>"
                                . "<td>" . $clientes[$value['IdClienteMB']]['Nombre'] . "</td>"
                                . "<td>" . $contratos[$value['IdContratoMB']]['Nombre'] . "</td>"
                                . "<td>" . $contratos[$value['IdContratoMB']]['Item']['Nombre'] . "</td>"
                                . "<td>$" . number_format($value['CostoMB'], 2, '.', ',') . "</td>"
                                . "<td>$" . number_format($value['DescuentoMB'], 2, '.', ',') . "</td>"
                                . "<td>$" . number_format(($value['CostoMB'] - $value['DescuentoMB']), 2, '.', ',') . "</td>"
                                . "<td>" . $value['Fecha'] . "</td>"
                                . "<td>" . $value['Ciclo'] . "</td>"
                                . "<td>" . $value['FechaUltimoCobro'] . "</td>"
                                . "<td>" . $value['IdCargoOP'] . "</td>"
                                . "<td>" . $value['FechaVentaMB'] . "</td>"
                                . "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>        
</div>