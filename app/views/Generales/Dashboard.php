<!-- Empezando #contenido -->
<div id="content" class="content">
    <div id="initialPage">
        <!-- Empezando titulo de la pagina -->
        <h1 class="page-header">Dashboard Salas 4D</h1>
        <!-- Finalizando titulo de la pagina -->

        <div class="panel panel-inverse" id="initialPanel">
            <div class="panel-heading">
                <h3 class="panel-title">Dashboard Salas 4D</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <h3 class="m-t-10">Solicitudes por Estatus</h3>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>    
                    </div> 
                </div>            
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div id="estatus_chart" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">                    
                        <div class="table-responsive">                        
                            <h4 class="m-t-10 f-w-600">Estatus de Solicitudes</h4>                                                    
                            <table id="estatus_table" class="table table-hover table-striped table-bordered no-wrap">
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
                                    if (isset($datos['Estatus'])) {
                                        foreach ($datos['Estatus'] as $key => $value) {
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
                                        <th class="f-w-700 text-center" id="total-cell-estatus"><?php echo $total; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <h3 class="m-t-10">Solicitudes por Prioridad</h3>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>    
                    </div> 
                </div>            
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div id="prioridades_chart" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">                    
                        <div class="table-responsive">                        
                            <h4 class="m-t-10 f-w-600">Prioridades de Solicitudes</h4>                                                    
                            <table id="prioridad_table" class="table table-hover table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th class="never">Id</th>
                                        <th class="all text-center">Prioridad</th>
                                        <th class="all text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    if (isset($datos['Prioridades'])) {
                                        foreach ($datos['Prioridades'] as $key => $value) {
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
                                        <th class="f-w-700 text-center" id="total-cell-prioridad"><?php echo $total; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">                        
                        <div class="form-group">
                            <h3 class="m-t-10">Tipo de Servicio por Solicitud</h3>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>    
                    </div> 
                </div>            
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div id="tipos_chart" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">                    
                        <div class="table-responsive">                        
                            <h4 class="m-t-10 f-w-600">Tipos de Servicio</h4>                                                    
                            <table id="tipos_table" class="table table-hover table-striped table-bordered no-wrap">
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
                                    if (isset($datos['Tipos'])) {
                                        foreach ($datos['Tipos'] as $key => $value) {
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
                                        <th class="f-w-700 text-center" id="total-cell-tipos"><?php echo $total; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="secondaryPage" style="display: none"></div>
    <div id="thirdPage" style="display: none"></div>
    <div id="sucursalPage" style="display: none"></div>
    <div id="atiendePage" style="display: none"></div>
    <div id="estatusPage" style="display: none"></div>
    <div id="lastPage" style="display: none"></div>
    <div id="auxiliarPage" style="display: none"></div>
</div>
<!-- Finalizando #contenido -->


<div class="theme-panel">
    <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn bg-green"><i class="fa fa-filter text-white"></i></a>
    <div class="theme-panel-content">
        <h5 class="m-t-0">Filtros de fechas</h5>
        <div class="divider"></div>
        <div class="row m-t-10">
            <div class="col-md-12 control-label f-w-700">Desde</div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class='input-group date' id='desde'>
                        <input type='text' class="form-control" value="<?php echo $datos['Fechas'][0]['Inicio']; ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>                
            </div>
            <div class="col-md-12 control-label f-w-700">Hasta</div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class='input-group date' id='hasta'>
                        <input type='text' class="form-control" value="<?php echo $datos['Fechas'][0]['Fin']; ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 control-label f-w-700">Pasado</div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-anio-pasado" class="btn btn-info btn-block btn-xs btn-date-filter">Año</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-trimestre-pasado" class="btn btn-info btn-block btn-xs btn-date-filter">Trimestre</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-mes-pasado" class="btn btn-info btn-block btn-xs btn-date-filter">Mes</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-semana-pasado" class="btn btn-info btn-block btn-xs btn-date-filter">Semana</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 control-label f-w-700">Presente</div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-anio-presente" class="btn btn-info btn-block btn-xs btn-date-filter">Año</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-mes-presente" class="btn btn-info btn-block btn-xs btn-date-filter">Mes</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 control-label f-w-700">Anterior(es)</div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-anio-anterior" class="btn btn-info btn-block btn-xs btn-date-filter">Año</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-trimestre-anterior" class="btn btn-info btn-block btn-xs btn-date-filter">Trimestre</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-mes-anterior" class="btn btn-info btn-block btn-xs btn-date-filter">Mes</a>
            </div>
            <div class="col-md-6 col-xs-6 m-t-5 m-b-5 text-center">
                <a href="javascript:;" id="btn-semana-anterior" class="btn btn-info btn-block btn-xs btn-date-filter">7 días</a>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12">
                <a href="#" id="btnFiltrarDashboard" class="btn btn-inverse btn-block btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
            </div>
        </div>
    </div>
</div>