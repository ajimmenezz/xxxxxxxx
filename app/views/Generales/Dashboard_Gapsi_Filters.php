<div id="contentDashboardGapsiFilters">

    <div id="content" class="content">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-9">
                <h1 class="page-header">Dashboard Gapsi</h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-3 text-right">
                <label id="btnReturnDashboardGapsi" class="btn btn-warning" onclick="location.reload()">
                    <i class="fa fa-2x fa-home"></i>
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
                <!--Empieza contenido proyecto-->                        
                <div id="proyecto" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <?php
                            if ($proyectos[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">Proyectos en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">Proyectos en Dolares</h4>';
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoProyecto"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                <div id="servicio" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6">
                            <?php
                            if ($servicios[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">Servicios en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">Servicios en Dolares</h4>';
                            }
                            ?>
                        </div> 
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoServicio"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                                        <th class="all">Servicio</th>
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
                <div id="sucursal" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6">
                            <?php
                            if ($sucursales[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">Sucursales en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">Sucursales en Dolares</h4>';
                            }
                            ?>
                        </div> 
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoSucursal"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                <div id="categoria" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6">
                            <?php
                            if ($categorias[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">Categoria en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">Categoria en Dolares</h4>';
                            }
                            ?>
                        </div> 
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoCategoria"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                                        echo '<td>' . $categoria['Categoria'] . '</td>';
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
                <div id="subcategoria" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6">
                            <?php
                            if ($subcategorias[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">SubCategoria en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">SubCategoria en Dolares</h4>';
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoSubCategoria"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                                        echo '<td>' . $subcategoria['SubCategoria'] . '</td>';
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
                <div id="concepto" class="row"> 

                    <!--Empieza titulo-->
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-6">
                            <?php
                            if ($subcategorias[0][0] === 'MN') {
                                echo '<h4 class="m-t-10">Concepto en Pesos</h4>';
                            } else {
                                echo '<h4 class="m-t-10">Concepto en Dolares</h4>';
                            }
                            ?>
                        </div> 
                        <div class="col-md-6">
                            <h4 class="m-t-10" style="text-align: right;">Gasto total: <label id="gastoConcepto"></label></h4>
                        </div>
                        <div class="col-md-12">
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
                                            <?php
                                            if ($subcategorias[0][0] === 'MN') {
                                                echo '<th class="all">Gasto MN</th>';
                                            } else {
                                                echo '<th class="all">Gasto</th>';
                                            }
                                            ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($concepto as $valor) {
                                        echo "<tr>";
                                        echo '<td>' . $valor['Concepto'] . '</td>';
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
