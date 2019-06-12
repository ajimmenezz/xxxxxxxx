<div id="contentDashboardGapsiFilters" class="page-with-two-sidebar">
    
    <!-- Empieza contenido #sidebar-right -->
    <div id="sidebar-right" class="sidebar sidebar-right fixed">
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
                            if($gastosCompras[0][0] === "MN")
                                echo '<input type="radio" name="optionsRadiosMoneda" value="MN" checked/>Peso';
                            else
                               echo '<input type="radio" name="optionsRadiosMoneda" value="MN"/>Peso'; 
                            ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label style="color: #A8ACB1">
                            <?php 
                            if($gastosCompras[0][0] === "USD")
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
                                if(count($proyectos) == 1){
                                    echo '<option value="' . $proyectos[0]['IdProyecto'] . '" selected="selected">' . $proyectos[0]['Proyecto'] . '</option>';
                                }else{
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
                                if(count($servicios) == 1){
                                    echo '<option value="' . $servicios[0]['TipoServicio'] . '" selected="selected">' . $servicios[0]['TipoServicio'] . '</option>';
                                }else{
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
                                if(count($sucursales) == 1){
                                    echo '<option value="' . $sucursales[0]['idSucursal'] . '" selected="selected">' . $sucursales[0]['Sucursal'] . '</option>';
                                }else{
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
                                if(count($categorias) == 1){
                                    echo '<option value="' . $categorias[0]['Categoria'] . '" selected="selected">' . $categorias[0]['Categoria'] . '</option>';
                                }else{
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
                                if(count($subcategorias) == 1){
                                    echo '<option value="' . $subcategorias[0]['SubCategoria'] . '" selected="selected">' . $subcategorias[0]['SubCategoria'] . '</option>';
                                }else{
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
    
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <h1 class="page-header">Dashboard Gapsi</h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 text-right" onclick="location.reload()">
                <label id="btnReturnDashboardGapsi" class="btn btn-warning">
                    <i class="fa fa-2x fa-home"></i>
                </label>  
            </div>
        </div>
        <div id="panelDashboardGapsiFilters" class="panel panel-inverse">

            <!--Empieza titulo pagina-->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" data-click="right-sidebar-toggled" class="f-s-14">
                        <i class="fa fa-th"></i>
                    </a>
                </div>
                <h4 class="panel-title">Gastos</h4>
            </div>
            <!--Finaliza titulo pagina-->

            <!--Empieza Panel -->
            <div class="panel-body">
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
                        <div id="chart_proyecto" style="width: 100%; height: 400px;  max-height:400px"></div>
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
                                        <?php
                                        if ($proyectos[0][0] === 'MN') {
                                            echo '<th class="all">Gasto MN</th>';
                                        } else {
                                            echo '<th class="all">Gasto USD</th>';
                                        }
                                        ?>
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
                                        echo '<td>$ ' . number_format($proyecto['Gasto'], 2) . '</td>';
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
                                        <?php
                                        if ($servicios[0][0] === 'MN') {
                                            echo '<th class="all">Gasto MN</th>';
                                        } else {
                                            echo '<th class="all">Gasto USD</th>';
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($servicios as $servicio) {
                                        echo "<tr>";
                                        echo '<td>' . $servicio['TipoServicio'] . '</td>';
                                        if ($servicio['TipoServicio'] != '') {
                                            echo '<td>' . $servicio['TipoServicio'] . '</td>';
                                        } else {
                                            echo '<td style="color: red">SIN DATOS</td>';
                                        }
                                        echo '<td>$ ' . number_format($servicio['Gasto'], 2) . '</td>';
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
                                        <?php
                                        if ($sucursales[0][0] === 'MN') {
                                            echo '<th class="all">Gasto MN</th>';
                                        } else {
                                            echo '<th class="all">Gasto USD</th>';
                                        }
                                        ?>
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
                                        echo '<td>$ ' . number_format($sucursal['Gasto'], 2) . '</td>';
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
                                        <?php
                                        if ($categorias[0][0] === 'MN') {
                                            echo '<th class="all">Gasto MN</th>';
                                        } else {
                                            echo '<th class="all">Gasto USD</th>';
                                        }
                                        ?>
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
                                        echo '<td>$ ' . number_format($categoria['Gasto'], 2) . '</td>';
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
                                        <?php
                                        if ($subcategorias[0][0] === 'MN') {
                                            echo '<th class="all">Gasto MN</th>';
                                        } else {
                                            echo '<th class="all">Gasto USD</th>';
                                        }
                                        ?>
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
                                        echo '<td>$ ' . number_format($subcategoria['Gasto'], 2) . '</td>';
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
                                        echo '<td>$ ' . number_format($valor['Gasto'], 2) . '</td>';
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
</div>