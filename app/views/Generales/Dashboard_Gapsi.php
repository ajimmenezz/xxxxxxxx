<!--Empieza dashboard principal-->
<div id="contentDashboardGapsi" class="content">
    <h1 class="page-header">Dashboard Gapsi</h1>
    <div id="panelDashboardGapsi" class="panel panel-inverse">

        <div class="panel-heading">
            <h4 class="panel-title">Gastos</h4>
        </div>

        <div class="panel-body">
            <div class="row">
                <!--grafica principal dashboard                -->
                <div class="col-md-7">                        
                    <div class="row">
                        <div id="graphDashboard" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="col-md-5">
                    <!--<div class="row">-->
                        <div class="table-responsive">
                            <!--tabla de los tipos de proyectos                            -->
                            <table id="data-table-tipo-proyectos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Tipo Proyecto</th>
                                        <th class="all">Proyectos</th>
                                        <th class="all">Gasto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['TiposProyectos'] as $valorTipoProyecto) {
                                        echo "<tr>";
                                        echo '<td>' . $valorTipoProyecto['Tipo'] . '</td>';
                                        echo '<td>' . $valorTipoProyecto['Proyectos'] . '</td>';
                                        echo '<td>$ ' . number_format($valorTipoProyecto['Importe'], 2) . '</td>';
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <!--</div>-->
                </div>
            </div>
            <div class="row  m-t-30">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                    <div class="form-group">
                        <h3 class="m-t-10" id="titulo-tabla-proyectos">Proyectos</h3>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div>    
                </div> 
            </div>            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!--tabla de todos los proyectos                    -->
                    <div class="table-responsive">
                        <table id="data-table-proyectos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Tipo</th>
                                    <th class="never">idProyecto</th>
                                    <th class="all">Proyecto</th>
                                    <th class="all">Gasto</th>
                                    <th class="all">Fecha Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['Proyectos'] as $valorProyecto) {
                                    echo '<tr>';
                                    echo '<td>' . $valorProyecto['Tipo'] . '</td>';
                                    echo '<td>' . $valorProyecto['IdProyecto'] . '</td>';
                                    echo '<td>' . $valorProyecto['Descripcion'] . '</td>';
//                                        if($valorProyecto['0'] == 'MN'){
//                                            echo '<td>MN$ '.number_format($valorProyecto['Gasto'], 2).'</td>';
//                                        }else{
//                                            if($valor['0'] == 'USD'){
//                                                echo '<td>US$ '.number_format($valorProyecto['Gasto'], 2).'</td>';
//                                            }else{
                                    echo '<td>$ ' . number_format($valorProyecto['Gasto'], 2) . '</td>';
//                                            }
//                                        }
                                    echo '<td>' . $valorProyecto['FCreacion'] . '</td>';
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>   
</div>
<!--Finaliza dashboard principal-->

<!--Empieza dashboard detallado-->
<div id="dashboardGapsiFilters" class="hidden"></div>
<!--Finaliza dashboard detallado-->

<div id="filtroFechas" class="theme-panel">
    <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn bg-green"><i class="fa fa-calendar text-white"></i></a>
    <div class="theme-panel-content">
        <h5 class="m-t-0">Filtros de fechas</h5>
        <div class="form-group">
            <label>Desde</label>
            <div class='input-group date' id='desde' values="">
                <input id='fechaComienzo' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label>Hasta</label>
            <div class='input-group date' id='hasta'>
                <input id='fechaFinal' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12">
                <a href="#" id="btnFiltrarDashboard" class="btn btn-inverse btn-success btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
            </div>
        </div>
    </div>
</div>