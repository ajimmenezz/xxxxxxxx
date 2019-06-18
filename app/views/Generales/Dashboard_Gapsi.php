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
                <!--Empieza Seccion de filtros agregados -->
                <div class="col-md-12">
                    <div  id="seccionFiltros">
                    </div>
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
                        <input type="radio" name="optionsRadiosMoneda" value="USD" />Dollar
                    </label>
                </div>
            </div>
            <!--Finaliza selector modena-->
            <!--Empieza filtro de fecha-->
            <div class="row">
                <div class="col-md-12">
                    <div class="divider"></div>
                    <h5 class="m-t-0" style="color: #A8ACB1">Filtros de fechas</h5>
                    <div class="form-group">
                        <label style="color: #A8ACB1">Desde</label>
                        <div class='input-group date' id='desde' values="">
                            <input id='fechaComienzo' type='text' class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label style="color: #A8ACB1">Hasta</label>
                        <div class='input-group date' id='hasta'>
                            <input id='fechaFinal' type='text' class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
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
                    <div class="form-group" id="hidecubcategoria">
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
                                    <?php
//                                    if ($datos['TiposProyectos'][0] === 'MN') {
                                        echo '<th class="all">Gasto MN</th>';
//                                    } else {
//                                        echo '<th class="all">Gasto USD</th>';
//                                    }
                                    ?>
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
                                    <?php
//                                    if ($datos['Proyectos'][0] === 'MN') {
                                        echo '<th class="all">Gasto MN</th>';
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
                                foreach ($datos['Proyectos'] as $valorProyecto) {
                                    echo '<tr>';
                                    echo '<td>' . $valorProyecto['Tipo'] . '</td>';
                                    echo '<td>' . $valorProyecto['IdProyecto'] . '</td>';
                                    echo '<td>' . $valorProyecto['Descripcion'] . '</td>';
                                    echo '<td>$ ' . number_format($valorProyecto['Gasto'], 2) . '</td>';
                                    echo '<td>' . $valorProyecto['FCreacion'] . '</td>';
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
        <!--Empieza selector modena-->
        <div class="col-md-12">
            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadiosMonedaPrincipal" value="MN" checked="checked"/>Peso
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadiosMonedaPrincipal" value="USD" />Dollar
                </label>
            </div>
            <div class="divider"></div>
        </div>
        <!--Finaliza selector modena-->
        <h5 class="m-t-0">Filtros de fechas</h5>
        <div class="form-group">
            <label>Desde</label>
            <div class='input-group date' id='desdePrincipal' values="">
                <input id='fechaComienzoPrincipal' type='text' class="form-control"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label>Hasta</label>
            <div class='input-group date' id='hastaPrincipal'>
                <input id='fechaFinalPrincipal' type='text' class="form-control"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12">
                <a href="#" id="btnFiltrarDashboardPrincipal" class="btn btn-inverse btn-success btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
            </div>
        </div>
    </div>
</div>