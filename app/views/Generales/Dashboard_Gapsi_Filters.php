<div id="contentDashboardGapsiFilters" class="content">
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Dashboard Gapsi</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right">
            <label id="btnReturnDashboardGapsi" class="btn btn-success">
                <i class="fa fa fa-home"></i> Regresar
            </label>  
        </div>
    </div>
    <div id="panelDashboardGapsiFilters" class="panel panel-inverse">

        <!--Empieza titulo pagina-->
        <div class="panel-heading">
            <h4 class="panel-title">Gastos</h4>
        </div>
        <!--Finaliza titulo pagina-->

        <!--Empieza Panel -->
        <div class="panel-body">            
            <!--Empieza seccion filtros-->
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 hidden">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">                            
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
                                            if ($valor['0'] == 'MN') {
                                                echo '<td>MN$ ' . number_format($valor['Gasto'], 2) . '</td>';
                                            } else {
                                                if ($valor['0'] == 'USD') {
                                                    echo '<td>US$ ' . number_format($valor['Gasto'], 2) . '</td>';
                                                } else {
                                                    echo '<td>$ ' . number_format($valor['Gasto'], 2) . '</td>';
                                                }
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                            <!--tabla de filtros agregados                            -->
                            <div  id="tableFiltros" class="table-responsive">
                                <table id="data-tipo-filtros" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="never">id</th>
                                            <th class="never"></th>
                                            <th class="all">Filtrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--selector de filtros                    -->
                    <div id="selectFiltros" class="row">
                        <div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
                            <div class="form-group" id="hideProyecto">
                                <label>Proyectos</label>
                                <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($proyectos as $proyecto) {
                                        echo '<option value="' . $proyecto['IdProyecto'] . '">' . $proyecto['Proyecto'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="hideServicio">
                                <label>Servicios</label>
                                <select id="selectServicio" class="form-control efectoDescuento" name="SelectServicio" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($servicios as $servicio) {
                                        echo '<option value="' . $servicio['TipoServicio'] . '">' . $servicio['TipoServicio'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="hideSucursal">
                                <label>Sucursal</label>
                                <select id="selectSucursal" class="form-control efectoDescuento" name="SelectSucursal" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($sucursales as $sucursal) {
                                        echo '<option value="' . $sucursal['idSucursal'] . '">' . $sucursal['Sucursal'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="hideCategoria">
                                <label>Categoria</label>
                                <select id="selectCategoria" class="form-control efectoDescuento" name="SelectCategoria" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($categorias as $categoria) {
                                        echo '<option value="' . $categoria['Categoria'] . '">' . $categoria['Categoria'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Moneda</label>
                                <select id="selectMoneda" class="form-control efectoDescuento" name="SelectMoneda" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <option value="MN">Pesos</option>
                                    <option value="USD">Dolar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--Termina seccion filtros-->

            <!--Empieza contenido proyecto-->                        
            <div id="Proyectos" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Proyectos</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->

                <!--Empieza grafica-->
                <div class="col-md-6 col-sm-12">
                    <div id="chart_proyecto" style="width: 100%; max-height:400px" ></div>
                </div>
                <!--Finaliza grafica-->

                <!--Empieza tabla-->                        
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table id="data-tipo-proyecto" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idProyecto</th>
                                    <th class="all">Proyecto</th>
                                    <th class="all">Gasto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($proyectos as $proyecto) {
                                    echo "<tr>";
                                    echo '<td>' . $proyecto['IdProyecto'] . '</td>';
                                    if ($proyecto['Proyecto'] != '') {
                                        echo '<td>' . $proyecto['Proyecto'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
                                    if ($proyecto['0'] == 'MN') {
                                        echo '<td>MN$ ' . number_format($proyecto['Gasto'], 2) . '</td>';
                                    } else {
                                        if ($proyecto['0'] == 'USD') {
                                            echo '<td>US$ ' . number_format($proyecto['Gasto'], 2) . '</td>';
                                        } else {
                                            echo '<td>$ ' . number_format($proyecto['Gasto'], 2) . '</td>';
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido proyecto-->  

            <!--Empieza contenido Servicios-->                        
            <div id="Servicios" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Servicios</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->
                <!--Empieza grafica-->
                <div class="col-md-6">
                    <div id="chart_servicios" style="width: 100%; height: 400px;  max-height:400px"></div>
                </div>
                <!--Finaliza grafica-->
                <!--Empieza tabla-->                        
                <div class="col-md-6"> 
                    <div class="table-responsive">
                        <table id="data-tipo-servicio" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idSerivicio</th>
                                    <th class="all">Serivicio</th>
                                    <th class="all">Gastos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($servicios as $servicio) {
                                    echo "<tr>";
                                    echo '<td>' . $servicio['IdServicio'] . '</td>';
                                    if ($servicio['TipoServicio'] != '') {
                                        echo '<td>' . $servicio['TipoServicio'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
                                    if ($servicio['0'] == 'MN') {
                                        echo '<td>MN$ ' . number_format($servicio['Gasto'], 2) . '</td>';
                                    } else {
                                        if ($servicio['0'] == 'USD') {
                                            echo '<td>US$ ' . number_format($servicio['Gasto'], 2) . '</td>';
                                        } else {
                                            echo '<td>$ ' . number_format($servicio['Gasto'], 2) . '</td>';
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>                    
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido Servicios-->   

            <!--Empieza contenido Sucursales-->                        
            <div id="Sucursales" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Sucursales</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->
                <!--Empieza grafica-->
                <div class="col-md-6">
                    <div id="chart_sucursal" style="width: 100%; height: 400px;  max-height:400px"></div>
                </div>
                <!--Finaliza grafica-->
                <!--Empieza tabla-->                        
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table id="data-tipo-sucursal" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idSucursal</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Gastos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($sucursales as $sucursal) {
                                    echo "<tr>";
                                    echo '<td>' . $sucursal['idSucursal'] . '</td>';
                                    if ($sucursal['Sucursal'] != '') {
                                        echo '<td>' . $sucursal['Sucursal'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
                                    if ($sucursal['0'] == 'MN') {
                                        echo '<td>MN$ ' . number_format($sucursal['Gasto'], 2) . '</td>';
                                    } else {
                                        if ($sucursal['0'] == 'USD') {
                                            echo '<td>US$ ' . number_format($sucursal['Gasto'], 2) . '</td>';
                                        } else {
                                            echo '<td>$ ' . number_format($sucursal['Gasto'], 2) . '</td>';
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>                    
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido Sucursales-->   

            <!--Empieza contenido Categoria-->                        
            <div id="Categoria" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Categoria</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->
                <!--Empieza grafica-->
                <div class="col-md-6">
                    <div id="chart_categoria" style="width: 100%; height: 400px;  max-height:400px"></div>
                </div>
                <!--Finaliza grafica-->
                <!--Empieza tabla-->                        
                <div class="col-md-6"> 
                    <div class="table-responsive">
                        <table id="data-tipo-categoria" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idCategoria</th>
                                    <th class="all">Categoria</th>
                                    <th class="all">Gastos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($categorias as $categoria) {
                                    echo "<tr>";
                                    echo '<td>1</td>';
                                    if ($categoria['Categoria'] != '') {
                                        echo '<td>' . $categoria['Categoria'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
                                    if ($categoria['0'] == 'MN') {
                                        echo '<td>MN$ ' . number_format($categoria['Gasto'], 2) . '</td>';
                                    } else {
                                        if ($categoria['0'] == 'USD') {
                                            echo '<td>US$ ' . number_format($categoria['Gasto'], 2) . '</td>';
                                        } else {
                                            echo '<td>$ ' . number_format($categoria['Gasto'], 2) . '</td>';
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido Categoria--> 

            <!--Empieza contenido SubCategoria-->                        
            <div id="SubCategoria" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">SubCategoria</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->
                <!--Empieza grafica-->
                <div class="col-md-6">
                    <div id="chart_subCategoria" style="width: 100%; height: 400px;  max-height:400px"></div>
                </div>
                <!--Finaliza grafica-->
                <!--Empieza tabla-->                        
                <div class="col-md-6"> 
                    <div class="table-responsive">
                        <table id="data-tipo-subCategoria" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idSubCategoria</th>
                                    <th class="all">SubCategoria</th>
                                    <th class="all">Gastos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($subcategorias as $subcategoria) {
                                    echo "<tr>";
                                    echo '<td>1</td>';
                                    if ($subcategoria['SubCategoria'] != '') {
                                        echo '<td>' . $subcategoria['SubCategoria'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
                                    if ($subcategoria['0'] == 'MN') {
                                        echo '<td>MN$ ' . number_format($subcategoria['Gasto'], 2) . '</td>';
                                    } else {
                                        if ($subcategoria['0'] == 'USD') {
                                            echo '<td>US$ ' . number_format($subcategoria['Gasto'], 2) . '</td>';
                                        } else {
                                            echo '<td>$ ' . number_format($subcategoria['Gasto'], 2) . '</td>';
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido SubCategoria-->  

            <!--Empieza contenido Concepto-->                        
            <div id="Concepto" class="row"> 

                <!--Empieza titulo-->
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="m-t-10">Concepto</h4>
                        <div class="underline m-b-15 m-t-15"></div>
                    </div> 
                </div>
                <!--Finaliza titulo-->
                <!--Empieza grafica-->
                <div class="col-md-6">
                    <div id="chart_concepto" style="width: 100%; height: 400px;  max-height:400px"></div>
                </div>
                <!--Finaliza grafica-->
                <!--Empieza tabla-->                        
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table id="data-tipo-concepto" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">idConcepto</th>
                                    <th class="all">Concepto</th>
                                    <th class="all">Gastos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($concepto as $valor) {
                                    echo "<tr>";
                                    echo '<td>1</td>';
                                    if ($valor['Concepto'] != '') {
                                        echo '<td>' . $valor['Concepto'] . '</td>';
                                    } else {
                                        echo '<td style="color: red">SIN DATOS</td>';
                                    }
//                                                        if($valor['0'] == 'MN'){
//                                                            echo '<td>MN$ '.number_format($valor['Gasto'], 2).'</td>';
//                                                        }else{
//                                                            if($valor['0'] == 'USD'){
//                                                                echo '<td>US$ '.number_format($valor['Gasto'], 2).'</td>';
//                                                            }else{
                                    echo '<td>$ ' . number_format($valor['Gasto'], 2) . '</td>';
//                                                            }
//                                                        }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>                    
                </div>
                <!--Finaliza tabla-->
            </div>
            <!--Finaliza contenido Concepto-->                                    
        </div>
        <!--Finaliza Panel -->
    </div>   
</div>

<br><br><br><br>
<div class="theme-panel">
    <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn bg-green"><i class="fa fa-filter text-white"></i></a>
    <div class="theme-panel-content">
        <h5 class="m-t-0">Filtros de fechas</h5>
        <div class="divider"></div>
        <div class="row m-t-10">
            <div class="col-md-12 control-label f-w-700">Desde</div>
            <div class="col-md-12">
                <div class="form-group">
                    <div class='input-group date' id='desde' values="">
                        <input id='fechaComienzo' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
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
                        <input id='fechaFin' type='text' class="form-control" value="<?php echo date("Y/d/m"); ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>                
            </div>
        </div>
        <!--        <div class="row">
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
                </div>-->
        <div class="row m-t-10">
            <div class="col-md-12">
                <a href="#" id="btnFiltrarDashboard" class="btn btn-inverse btn-block btn-sm"><i class="fa fa-refresh m-r-3"></i> Filtrar información</a>
            </div>
        </div>
    </div>
</div>