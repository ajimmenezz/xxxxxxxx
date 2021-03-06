<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Solicitudes filtradas por Prioridad</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnBackToInitial" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="secondaryPanel" class="panel panel-inverse">        
    <div class="panel-heading">
        <h4 class="panel-title">Solicitudes filtradas por prioridad "<?php echo $prioridad; ?>"</h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Solicitudes con Prioridad <?php echo $prioridad; ?></h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div> 
        <div class="row m-t-10">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="div_chart_estatus_filtered" style="display: none;">
                    <a class="pull-right f-s-14 f-w-600" href="#" id="btnShowEstatusTable">Ver Tabla(Grid)</a>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="estatus_chart_filtered" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="table-responsive" id="div_table_estatus_filtered">
                    <a class="pull-right f-s-14 f-w-600" href="#" id="btnShowEstatusChart">Ver Gráfica</a>
                    <h4 class="m-t-10 f-w-600">Estatus de Solicitudes</h4>                                                    
                    <table id="estatus_table_filtered" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all text-center">Estatis</th>
                                <th class="all text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($generalesEstatus)) {
                                foreach ($generalesEstatus as $key => $value) {
                                    $total += $value['Total'];
                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td class="text-center">' . $value['Nombre'] . '</td>'
                                    . '<td class="text-center">' . $value['Total'] . '</td>'
                                    . '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="f-w-700 text-center">Totales</th>
                                <th class="f-w-700 text-center" id="total-cell-estatus-filtered"><?php echo $total; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">   
                <div id="div_chart_tipos_filtered" style="display: none;">
                    <a class="pull-right f-s-14 f-w-600" href="#" id="btnShowTiposTable">Ver Tabla(Grid)</a>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="tipos_chart_filtered" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="table-responsive" id="div_table_tipos_filtered">    
                    <a class="pull-right f-s-14 f-w-600" href="#" id="btnShowTiposChart">Ver Gráfica</a>
                    <h4 class="m-t-10 f-w-600">Tipos de Servicio</h4>                                                    
                    <table id="tipos_table_filtered" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all text-center">Tipo Servicio</th>
                                <th class="all text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($generalesTipos)) {
                                foreach ($generalesTipos as $key => $value) {
                                    $total += $value['Total'];
                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td class="text-center">' . $value['Nombre'] . '</td>'
                                    . '<td class="text-center">' . $value['Total'] . '</td>'
                                    . '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="f-w-700 text-center">Totales</th>
                                <th class="f-w-700 text-center" id="total-cell-tipos-filtered"><?php echo $total; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row m-t-25">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Lista de Solicitudes con Prioridad <?php echo $prioridad; ?></h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div> 
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="table-solicitudes-secondary" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="all"># Solicitud</th>
                                <th class="all"># Ticket</th>
                                <th class="all">Estatus</th>
                                <th class="all">Prioridad</th>
                                <th class="all">Fecha</th>
                                <th class="all">Solicita</th>
                                <th class="all">Asunto</th>
                                <th class="all"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($lista)) {
                                foreach ($lista as $key => $value) {
                                    switch ($value['Prioridad']) {
                                        case 'Baja':
                                            $flag = '<i class="fa fa-2x fa-flag fa-inverse text-success"></i>';
                                            break;
                                        case 'Media':
                                            $flag = '<i class="fa fa-2x fa-flag fa-inverse text-warning"></i>';
                                            break;
                                        case 'Alta':
                                            $flag = '<i class="fa fa-2x fa-flag fa-inverse text-danger"></i>';
                                            break;
                                    }
                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td>' . $value['Ticket'] . '</td>'
                                    . '<td>' . $value['Estatus'] . '</td>'
                                    . '<td>' . $value['Prioridad'] . '</td>'
                                    . '<td>' . $value['FechaCreacion'] . '</td>'
                                    . '<td>' . $value['Solicita'] . '</td>'
                                    . '<td>' . $value['Asunto'] . '</td>'
                                    . '<td>' . $flag . '</td>'
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
</div>