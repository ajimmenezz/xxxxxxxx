<!-- Empieza contenido #sidebar-right -->
<div id="sidebar-right" class="sidebar sidebar-right hidden">
    <!-- Empieza sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!--Empieza seccion filtros-->
        <div class="col-md-12">
            <div class="col-md-12"><br></div>
            <div class="row">
                <div class="col-md-12">                       
                    <div  id="tableGastos" class="table-responsive">
                        <table id="data-tipo-gastos" class="table table-bordered" style="cursor:pointer; background: white" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Tipo</th>
                                    <th class="all">Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Empieza Seccion de detalles -->
                <div class="col-md-12">
                    <p>
                        <a id="verDetalles" href="javascript:;" class="btn btn-primary btn-block hidden">Ver Detalles <i class="fa fa-angle-double-right"></i></a>
                    </p>
                    <p>
                        <a id="ocultarDetalles" href="javascript:;" class="btn btn-primary btn-block hidden">Ocultar Detalles <i class="fa fa-angle-double-left"></i></a>
                    </p>
                </div>
                <!--Finaliza Seccion de detalles -->
                <!--Empieza Seccion de filtros agregados -->
                <div class="col-md-12">
                    <div  id="seccionFiltros"></div>
                </div>
                <!--Finaliza Seccion de filtros agregados -->
            </div>
            <!--Empieza selector modena-->
            <div class="col-md-12">
                <div class="radio">
                    <label style="color: #A8ACB1">
                        <input type="radio" name="optionsRadiosMoneda" value="MN"/>Peso
                    </label>
                </div>
                <div class="radio">
                    <label style="color: #A8ACB1">
                        <input type="radio" name="optionsRadiosMoneda" value="USD" />Dolar
                    </label>
                </div>
            </div>
            <!--Finaliza selector modena-->
            <!--Empieza filtro de fecha-->
            <div class="row">
                <div class="input-group input-daterange">
                    <div class="col-md-12">
                        <input id="fechaComienzo" name="startDate" type="text" class="form-control"> 
                        <span class="input-group-addon calendarDesde">
                            <label>Desde <span class="glyphicon glyphicon-calendar"></span></label>
                        </span>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <input id="fechaFin" name="endDate" type="text" class="form-control">
                        <span class="input-group-addon calendarHasta">
                            <label>Hasta <span class="glyphicon glyphicon-calendar"></span></label>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="#" id="btnFiltrarDashboard" class="btn btn-inverse btn-success btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
                </div>
            </div>
            <!--Finaliza filtro de fecha-->
            <!--Empieza selector de filtros -->
            <div id="selectFiltros" class="row">
                <div class="col-md-12">
                    <div class="divider"></div>
                    <div class="form-group" id="hideproyecto">
                        <label style="color: #A8ACB1">Proyectos</label>
                        <select id="selectproyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                        </select>
                    </div>
                    <div class="form-group" id="hideservicio">
                        <label style="color: #A8ACB1">Servicios</label>
                        <select id="selectservicio" class="form-control efectoDescuento" name="SelectServicio" style="width: 100%">
                        </select>
                    </div>
                    <div class="form-group" id="hidesucursal">
                        <label style="color: #A8ACB1">Sucursal</label>
                        <select id="selectsucursal" class="form-control efectoDescuento" name="SelectSucursal" style="width: 100%">
                        </select>
                    </div>
                    <div class="form-group" id="hidecategoria">
                        <label style="color: #A8ACB1">Categoria</label>
                        <select id="selectcategoria" class="form-control efectoDescuento" name="SelectCategoria" style="width: 100%">
                        </select>
                    </div>
                    <div class="form-group" id="hidesubcategoria">
                        <label style="color: #A8ACB1">SubCategoria</label>
                        <select id="selectsubcategoria" class="form-control efectoDescuento" name="SelectSubCategoria" style="width: 100%">
                        </select>
                    </div>
                    <div class="form-group" id="hideconcepto">
                        <label style="color: #A8ACB1">Concepto</label>
                        <select id="selectconcepto" class="form-control efectoDescuento" name="SelectConcepto" style="width: 100%">
                        </select>
                    </div>
                </div>
            </div>
            <!--Finaliza selector de filtros-->
            <div class="col-md-12"><br></div>
        </div>
        <!-- Termina seccion filtros -->
    </div>
    <!--Termina sidebar scrollbar-->
</div>
<div class="sidebar-bg sidebar-right"></div>
<!-- Finaliza contenido #sidebar-right -->


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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-12">
                        <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoTipoProyectos"></label></h4>
                    </div>
                </div>
                <div id="graficaMN" class="col-md-7">                        
                    <div class="row">
                        <div id="graphDashboard" style="width: 100%; height: 400px;  max-height:400px"></div>                        
                    </div>
                </div>
                <div id="graficaUSD" class="col-md-7 hidden">                        
                    <div class="row">
                        <div id="graphDashboardUSD" style="width: 100%; height: 400px;  max-height:400px"></div>                        
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
                                foreach ($datos['Datos']['TiposProyectos'] as $key => $value) {                               
                                    echo "<tr>";
                                    echo '<td>' . key($value) . '</td>';
                                    echo '<td>' . $value['Total'] . '</td>';
                                    echo '<td>$ ' . number_format($value[key($value)], 2) . '</td>';
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
                    <div class="col-md-6">
                        <h3 class="m-t-10" id="titulo-tabla-proyectos">Proyectos</h3>
                    </div>
                    <div class="col-md-6">
                        <h4 class="m-t-10" style="text-align: right;"><label id="gastoProyectos"></label></h4>
                    </div>
                    <div class="col-md-12">
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
                                    <?php
//                                    if ($datos['Proyectos'][0] === 'MN') {
                                    echo '<th class="all">Gasto</th>';
//                                    } else {
//                                        echo '<th class="all">Gasto USD</th>';
//                                    }
                                    ?>
                                    <th class="all">Fecha Inicio</th>
                                    <th class="all">Último movimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['Datos']['Proyectos'] as $valorProyecto) {
                                    echo '<tr>';
                                    echo '<td>' . $valorProyecto[0]['tipo'] . '</td>';
                                    echo '<td>' . $valorProyecto[0]['idProyecto'] . '</td>';
                                    echo '<td>' . $valorProyecto[0]['proyecto'] . '</td>';
                                    echo '<td>$ ' . number_format($valorProyecto[0]['gasto'], 2) . '</td>';
                                    echo '<td>' . $valorProyecto[0]['fechaCreacion'] . '</td>';
                                    echo '<td>' . $valorProyecto[0]['ultimoMovimiento'] . '</td>';
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

<!--Empieza dashboard filtros-->
<div id="dashboardGapsiFilters" class="hidden"></div>
<!--Finaliza dashboard filtros-->

<!--Empieza dashboard detalles-->
<div id="dashboardGapsiDetalles" class="hidden"></div>
<!--Finaliza dashboard detalles-->

<!--Empieza panel filtros principal-->
<div id="filtroFechas" class="theme-panel">
    <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn bg-green"><i class="fa fa-calendar text-white"></i></a>
    <div class="theme-panel-content">
        <!--Empieza selector modena-->
        <div class="col-md-12">
            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadiosMonedaPrincipal" value="MN" checked="checked"/>Peso
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadiosMonedaPrincipal" value="USD" />Dolar
                </label>
            </div>
            <div class="divider"></div>
        </div>
        <!--Finaliza selector modena-->
        <h5 class="m-t-0">Filtros de fechas</h5>
        <!--Empieza filtro Fecha-->
        <div class="input-group input-daterange">
            <div class="col-md-12">
                <input id="fechaComienzoPrincipal" name="startDate" type="text" class="form-control"> 
                <span class="input-group-addon calendarDesdePrincipal">
                    <label>Desde <span class="glyphicon glyphicon-calendar"></span></label>
                </span>
                <br>
            </div>
            <div class="col-md-12">
                <input id="fechaFinPrincipal" name="endDate" type="text" class="form-control">
                <span class="input-group-addon calendarHastaPrincipal">
                    <label>Hasta <span class="glyphicon glyphicon-calendar"></span></label>
                </span>
            </div>
        </div>
        <!--Finaliza filtro Fecha-->
        <div class="row m-t-10">
            <div class="col-md-12">
                <a href="#" id="btnFiltrarDashboardPrincipal" class="btn btn-inverse btn-success btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
            </div>
        </div>
    </div>
</div>
<!--Finaliza panel filtros principal-->
