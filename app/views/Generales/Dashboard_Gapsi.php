<!-- Empieza contenido #sidebar-right -->
<div id="sidebar-right" class="sidebar sidebar-right hidden">
    <!-- Empieza sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!--Empieza seccion filtros-->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 hidden">                       
                    <div  id="tableGastos" class="table-responsive">
                        <table id="data-tipo-gastos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Tipo</th>
                                    <th class="all">Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($gastosCompras as $valor) {
                                    echo "<tr>";
                                    echo '<td>' . $valor['TipoTrans'] . '</td>';
                                    echo '<td>$ ' . number_format($valor['Gasto'], 2) . '</td>';
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Empieza Seccion de filtros agregados -->
                <div class="col-md-12">
                    <div  id="seccionFiltros" class="table-responsive hidden">
                        <table id="data-seccion-filtros" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Filtrado por</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finaliza Seccion de filtros agregados -->
            </div>
            <!--Empieza selector modena-->
            <div class="col-md-12">
                <div class="radio">
                    <label style="color: #A8ACB1">
                        <?php
                        if ($gastosCompras[0][0] === "MN")
                            echo '<input type="radio" name="optionsRadiosMoneda" value="MN" checked/>Peso';
                        else
                            echo '<input type="radio" name="optionsRadiosMoneda" value="MN"/>Peso';
                        ?>
                    </label>
                </div>
                <div class="radio">
                    <label style="color: #A8ACB1">
                        <?php
                        if ($gastosCompras[0][0] === "USD")
                            echo '<input type="radio" name="optionsRadiosMoneda" value="USD" checked/>Dollar';
                        else
                            echo '<input type="radio" name="optionsRadiosMoneda" value="USD" />Dollar';
                        ?>
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
                            <input id='fechaComienzo' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
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
                            <input id='fechaFinal' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
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
                    <div class="form-group" id="hideProyecto">
                        <label style="color: #A8ACB1">Proyectos</label>
                        <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                            <option value="">Seleccionar...</option>
                            <?php
                            if (count($proyectos) == 1) {
                                echo '<option value="' . $proyectos[0]['IdProyecto'] . '" selected="selected">' . $proyectos[0]['Proyecto'] . '</option>';
                            } else {
                                foreach ($proyectos as $proyecto) {
                                    echo '<option value="' . $proyecto['IdProyecto'] . '">' . $proyecto['Proyecto'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="hideServicio">
                        <label style="color: #A8ACB1">Servicios</label>
                        <select id="selectServicio" class="form-control efectoDescuento" name="SelectServicio" style="width: 100%">
                            <option value="">Seleccionar...</option>
                            <?php
                            if (count($servicios) == 1) {
                                echo '<option value="' . $servicios[0]['TipoServicio'] . '" selected="selected">' . $servicios[0]['TipoServicio'] . '</option>';
                            } else {
                                foreach ($servicios as $servicio) {
                                    echo '<option value="' . $servicio['TipoServicio'] . '">' . $servicio['TipoServicio'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="hideSucursal">
                        <label style="color: #A8ACB1">Sucursal</label>
                        <select id="selectSucursal" class="form-control efectoDescuento" name="SelectSucursal" style="width: 100%">
                            <option value="">Seleccionar...</option>
                            <?php
                            if (count($sucursales) == 1) {
                                echo '<option value="' . $sucursales[0]['idSucursal'] . '" selected="selected">' . $sucursales[0]['Sucursal'] . '</option>';
                            } else {
                                foreach ($sucursales as $sucursal) {
                                    echo '<option value="' . $sucursal['idSucursal'] . '">' . $sucursal['Sucursal'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="hideCategoria">
                        <label style="color: #A8ACB1">Categoria</label>
                        <select id="selectCategoria" class="form-control efectoDescuento" name="SelectCategoria" style="width: 100%">
                            <option value="">Seleccionar...</option>
                            <?php
                            if (count($categorias) == 1) {
                                echo '<option value="' . $categorias[0]['Categoria'] . '" selected="selected">' . $categorias[0]['Categoria'] . '</option>';
                            } else {
                                foreach ($categorias as $categoria) {
                                    echo '<option value="' . $categoria['Categoria'] . '">' . $categoria['Categoria'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="hideSubCategoria">
                        <label style="color: #A8ACB1">SubCategoria</label>
                        <select id="selectSubCategoria" class="form-control efectoDescuento" name="SelectSubCategoria" style="width: 100%">
                            <option value="">Seleccionar...</option>
                            <?php
                            if (count($subcategorias) == 1) {
                                echo '<option value="' . $subcategorias[0]['SubCategoria'] . '" selected="selected">' . $subcategorias[0]['SubCategoria'] . '</option>';
                            } else {
                                foreach ($subcategorias as $subcategoria) {
                                    echo '<option value="' . $subcategoria['SubCategoria'] . '">' . $subcategoria['SubCategoria'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!--Finaliza selector de filtros-->

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
                                    echo '<td>$ ' . number_format($valorProyecto['Gasto'], 2) . '</td>';
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

<!--<div id="filtroFechas" class="theme-panel">
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
</div>-->