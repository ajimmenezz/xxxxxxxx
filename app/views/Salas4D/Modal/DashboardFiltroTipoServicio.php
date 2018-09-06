<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Tipos de Servicio</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnBackToInitial" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>    
<div id="secondaryPanel" class="panel panel-inverse">        
    <div class="panel-heading">
        <?php
        $titulo = '';
        if (isset($tipo) && $tipo != '') {
            $titulo .= ' - ' . $tipo;
        }
        if (isset($estatusSolicitud) && $estatusSolicitud != '') {
            $titulo .= ' - ' . $estatusSolicitud;
        }
        if (isset($prioridad) && $prioridad != '') {
            $titulo .= ' - Prioridad: ' . $prioridad;
        }
        ?>
        <h4 class="panel-title"><?php echo $titulo; ?></h4>        
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10"><?php echo $titulo; ?></h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="estatus_tipos_chart_filtered" style="width: 100%; height: 400px;  max-height:400px"></div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">                    
                <div class="table-responsive">                        
                    <h4 class="m-t-10 f-w-600">Estatus de Servicios</h4>                                                    
                    <table id="estatus_tipos_table_filtered" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all text-center">Estatus</th>
                                <th class="all text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($estatusServicios)) {
                                foreach ($estatusServicios as $key => $value) {
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
                                <th class="f-w-700 text-center" id="total-cell-estatus_tipos_filtered"><?php echo $total; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row m-t-5">            
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="div-chart-sucursales-servicios" style="display: none;">
                    <a class="pull-right f-s-14 f-w-600" id="btnShowTableSucursalesServicios">Ver Tabla(Grid)</a>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="chart-sucursales-servicios" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="table-responsive" id="div-table-sucursales-servicios">
                    <a class="pull-right f-s-14 f-w-600" id="btnShowChartSucursalesServicios">Ver Gráfica</a>
                    <h4 class="m-t-10 f-w-600">Sucursales de Servicios</h4>                                                    
                    <table id="table-sucursales-servicios" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all text-center">Sucursal</th>
                                <th class="all text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($generalesSucursales)) {
                                foreach ($generalesSucursales as $key => $value) {
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
                                <th class="f-w-700 text-center" id="total-cell-sucursales-servicios"><?php echo $total; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div id="div-chart-atiende-servicios" style="display: none;">
                    <a class="pull-right f-s-14 f-w-600" id="btnShowTableAtiendeServicios">Ver Tabla(Grid)</a>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="chart-atiende-servicios" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="table-responsive" id="div-table-atiende-servicios">
                    <a class="pull-right f-s-14 f-w-600" id="btnShowChartAtiendeServicios">Ver Gráfica</a>
                    <h4 class="m-t-10 f-w-600">Atiende el Servicio</h4>                                                    
                    <table id="table-atiende-servicios" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all text-center">Atiende</th>
                                <th class="all text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($generalesAtiende)) {
                                foreach ($generalesAtiende as $key => $value) {
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
                                <th class="f-w-700 text-center" id="total-cell-atiende-servicios"><?php echo $total; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>            
        </div>

        <div class="row m-t-25">
            <div class="col-md-12">                        
                <div class="form-group">
                    <h3 class="m-t-10">Lista de Servicios <?php echo $tipo; ?></h3>
                    <div class="underline m-b-15 m-t-15"></div>
                </div>    
            </div> 
        </div> 
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">                    
                    <table id="table-servicios-secondary" class="table table-hover table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th class="all"># Servicio</th>
                                <th class="all"># Ticket</th>
                                <th class="all">Sucursal</th>
                                <th class="all">Estatus</th>
                                <th class="all">Tipo Servicio</th>
                                <th class="all">Fecha</th>
                                <th class="all">Atiende</th>
                                <th class="all">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($lista)) {
                                foreach ($lista as $key => $value) {
                                    echo ''
                                    . '<tr>'
                                    . '<td>' . $value['Id'] . '</td>'
                                    . '<td>' . $value['Ticket'] . '</td>'
                                    . '<td>' . $value['Sucursal'] . '</td>'
                                    . '<td>' . $value['Estatus'] . '</td>'
                                    . '<td>' . $value['Tipo'] . '</td>'
                                    . '<td>' . $value['Fecha'] . '</td>'
                                    . '<td>' . $value['Atiende'] . '</td>'
                                    . '<td>' . $value['Descripcion'] . '</td>'
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