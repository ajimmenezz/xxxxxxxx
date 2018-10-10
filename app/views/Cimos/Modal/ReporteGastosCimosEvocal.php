<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Gastos CIMOS - EVocal</h1>
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
        <h4 class="panel-title">Gastos CIMOS - EVocal</h4>        
    </div>
    <div class="panel-body">        
        <div class="row"> 
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-grup">
                    <h4 class="m-t-10">Lista de Gastos CIMOS - EVocal</h4>
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
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Fecha Captura</th>
                                <th>Tipo</th>
                                <th>Tipo Servicio</th>
                                <th>Proyecto</th>
                                <th>Sucursal</th>
                                <th>Cliente</th>
                                <th>Beneficiario</th>
                                <th>Tipo Trans</th>
                                <th>Descripción</th>                                
                                <th>Importe</th>                                
                                <th>Moneda</th>                                
                                <th>Banco</th>                                
                                <th>Ref. Bancaria</th>                                
                                <th>Empresa</th>                                
                                <th>Orden de Compra</th>                                
                                <th>Ticket</th>                                
                                <th>Autorización</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($gastos as $key => $value) {
                                echo ""
                                . "<tr>"
                                . "<td>" . $value['Folio'] . "</td>"
                                . "<td>" . $value['FechaS'] . "</td>"
                                . "<td>" . $value['FCapturaS'] . "</td>"
                                . "<td>" . $value['Tipo'] . "</td>"
                                . "<td>" . $value['TipoServicio'] . "</td>"
                                . "<td>" . $value['Proyecto'] . "</td>"
                                . "<td>" . $value['Sucursal'] . "</td>"
                                . "<td>" . $value['Cliente'] . "</td>"
                                . "<td>" . $value['Beneficiario'] . "</td>"
                                . "<td>" . $value['TipoTrans'] . "</td>"
                                . "<td>" . $value['Descripcion'] . "</td>"
                                . "<td>" . $value['Importe'] . "</td>"
                                . "<td>" . $value['Moneda'] . "</td>"
                                . "<td>" . $value['Banco'] . "</td>"
                                . "<td>" . $value['RefBancaria'] . "</td>"
                                . "<td>" . $value['Empresa'] . "</td>"
                                . "<td>" . $value['OrdenCompra'] . "</td>"
                                . "<td>" . $value['Ticket'] . "</td>"
                                . "<td>" . $value['Autorizacion'] . "</td>"
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