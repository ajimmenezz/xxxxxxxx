<!-- Empezando #contenido -->
<div id="content" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Dashboard</h1>
    <!-- Finalizando titulo de la pagina -->

    <div id="panel-solicitudes-generadas" class="panel panel-inverse panel-with-tabs">
        <div class="panel-heading p-0">
            <div class="panel-heading-btn m-r-10 m-t-10"></div>
            <!-- begin nav-tabs -->
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#solicitudes-generadas" data-toggle="tab">Solicitudes Generadas</a></li>
                    <?php if(in_array("64", $datos['Usuario']['Permisos'])) {?>
                    <li class=""><a href="#servicios-area" data-toggle="tab">Servicios del Área</a></li>           
                    <?php }?>
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="tab-content">            
            <!-- Empezando panel Solicitudes Generadas-->
            <div class="tab-pane fade active in" id="solicitudes-generadas">     
                <div class="panel-body">
<!--                    <div class="row">
                        <div class="col-md-12">
                            <pre>
                                <?php // var_dump($datos['Usuario']); ?>
                            </pre>
                        </div>
                    </div>-->
                    <div class="row tile_count">
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-file-o"></i> Total de Solicitudes</span>
                            <div id="total-sol-generadas" class="count"><?php echo $datos['Generadas']['totales1'][0]['Total']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Solicitudes</i></span>
                        </div>
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-clock-o"></i> Periodo de Tiempo</span>
                            <div id="total-dias-sol-generadas" class="count"><?php echo $datos['Generadas']['totales1'][0]['Dias']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Días</i></span>
                        </div>
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-sitemap"></i> Total Departamentos</span>
                            <div id="total-dptos-sol-generadas" class="count"><?php echo $datos['Generadas']['totales2'][0]['Total']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Departamentos</i></span>
                        </div>
                        <!--<div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
                            <div class="count green">2,500</div>
                            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12"></div>
                        <div class="col-md-4 col-xs-12"></div>
                        <div class="col-md-4 col-xs-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">                        
                            <div id="solicitudes_generadas_estatus"></div>
                        </div>
                        <div class="col-md-4 col-xs-12">                        
                            <div id="solicitudes_generadas_prioridad"></div>
                        </div>
                        <div class="col-md-4 col-xs-12">                        
                            <div id="solicitudes_generadas_departamento"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb-solicitudes-generadas" class="table table-hover table-striped table-bordered no-wrap ">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Ticket</th>
                                            <th>Departamento</th>
                                            <th>Asunto</th>
                                            <th>Prioridad</th>
                                            <th>Fecha</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($datos['Generadas']['tabla'] as $key => $value) {
                                            echo ""
                                            . " <tr>"
                                            . "     <td>" . $value['Id'] . "</td>"
                                            . "     <td>" . $value['Ticket'] . "</td>"
                                            . "     <td>" . $value['Departamento'] . "</td>"
                                            . "     <td>" . $value['Asunto'] . "</td>"
                                            . "     <td>" . $value['Prioridad'] . "</td>"
                                            . "     <td>" . $value['FechaCreacion'] . "</td>"
                                            . "     <td>" . $value['Estatus'] . "</td>"
                                            . " </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <!-- Finalizando panel Solicitudes Generadas -->
            <?php if(in_array("64", $datos['Usuario']['Permisos'])) {?>
            <!-- Empezando panel de Servicios del Área -->
            <div class="tab-pane fade" id="servicios-area">  
                <div class="panel-body">
                    <div class="row tile_count">
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-file-o"></i> Total de Servicios</span>
                            <div id="total-servicios-area" class="count"><?php echo $datos['ServiciosArea']['totales1'][0]['Total']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Servicios</i></span>
                        </div>
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-clock-o"></i> Periodo de Tiempo</span>
                            <div id="total-dias-servicios-area" class="count"><?php echo $datos['ServiciosArea']['totales1'][0]['Dias']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Días</i></span>
                        </div>
                        <div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top f-w-700"><i class="fa fa-sitemap"></i> Total Departamentos</span>
                            <div id="total-dptos-servicios-area" class="count"><?php echo $datos['ServiciosArea']['totales2'][0]['Total']; ?></div>
                            <span class="count_bottom"><i class="green f-w-600">Departamentos</i></span>
                        </div>
                        <!--<div class="col-md-4 col-xs-12 text-center tile_stats_count">
                            <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
                            <div class="count green">2,500</div>
                            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12"></div>
                        <div class="col-md-4 col-xs-12"></div>
                        <div class="col-md-4 col-xs-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">                        
                            <div id="servicios-area-estatus"></div>
                        </div>
                        <div class="col-md-4 col-xs-12">                        
                            <div id="servicios-area-atiende"></div>
                        </div>
                        <div class="col-md-4 col-xs-12">                        
                            <div id="servicios-area-departamentos"></div>
                        </div>
                    </div>
                    <div class="row m-t-20 m-b-10">
                        <div class="col-md-12 col-xs-12 text-right">
                            <a href="javascript:;" id="btn-exporta-servicios-logistica" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Exporta Excel</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb-servicios-area" class="table table-hover table-striped table-bordered no-wrap ">
                                    <thead>
                                        <tr>                                            
                                            <th>Ticket</th>
                                            <th>Servicio</th>
                                            <th>Tipo Servicio</th>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Estatus</th>
                                            <th>Atiende</th>
                                        </tr>
                                    </thead>
                                    <tbody>        
                                        <?php
                                        foreach ($datos['ServiciosArea']['tabla'] as $key => $value) {
                                            echo ""
                                            . " <tr>"                                            
                                            . "     <td>" . $value['Ticket'] . "</td>"
                                            . "     <td>" . $value['Id'] . "</td>"
                                            . "     <td>" . $value['TipoServ'] . "</td>"
                                            . "     <td>" . $value['FechaCreacion'] . "</td>"
                                            . "     <td>" . $value['Descripcion'] . "</td>"
                                            . "     <td>" . $value['Estatus'] . "</td>"
                                            . "     <td>" . $value['Atiende'] . "</td>"
                                            . " </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- Finalizando panel de Servicios del Área -->      
            <?php } ?>
        </div>
    </div>
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
                        <input type='text' class="form-control" value="<?php echo $datos['Fechas'][0]['Inicio'] ?>"/>
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
                        <input type='text' class="form-control" value="<?php echo $datos['Fechas'][0]['Fin'] ?>"/>
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