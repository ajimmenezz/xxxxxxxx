<div id="contentDashboardGapsiFilters" class="content">
    <div class="row">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <h1 class="page-header">Dashboard Gapsi</h1>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 text-right">
            <label id="btnReturnDashboardGapsi" class="btn btn-success">
                <i class="fa fa fa-reply"></i> Regresar
            </label>  
        </div>
    </div>
    <div id="panelDashboardGapsiFilters" class="panel panel-inverse">
       
        <div class="panel-heading">
            <h4 class="panel-title">Gastos</h4><?php //echo '<pre>'; var_dump($proyectos); echo '</pre>';?>
        </div>
        
        <div class="panel-body">
            <div class="row">
<!--comienzo del div menu                -->
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
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
                                                echo '<td>'.$valor['TipoTrans'].'</td>';
                                                echo '<td>'.number_format($valor['Gasto'], 2).'</td>';
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
                                            <th class="all">Tipo</th>
                                            <th class="all">Filtro</th>
                                            <th class="all"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Tipo Proyecto</td>
                                            <td>Tipo Proyecto</td>
                                            <td></td>
                                        </tr>
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
                                        echo '<option value="'.$proyecto['IdProyecto'].'">'.$proyecto['Proyecto'].'</option>';
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
                                        echo '<option value="'.$servicio['IdServicio'].'">'.$servicio['TipoServicio'].'</option>';
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
                                        echo '<option value="'.$sucursal['IdSucursal'].'">'.$sucursal['Sucursal'].'</option>';
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
                                        echo '<option value="'.$categoria['Categoria'].'">'.$categoria['Categoria'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="hideSubCategoria">
                                <label>SubCategoria</label>
                                <select id="selectSubCategoria" class="form-control efectoDescuento" name="SelectSubCategoria" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($subcategorias as $subcategoria) {
                                        echo '<option value="'.$subcategoria['SubCategoria'].'">'.$subcategoria['SubCategoria'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group"  id="hideConcepto">
                                <label>Concepto</label>
                                <select id="selectConcepto" class="form-control efectoDescuento" name="SelectConcepto" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    foreach ($concepto as $valor) {
                                        echo '<option value="'.$valor['Concepto'].'">'.$valor['Concepto'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
<!--                            <div class="form-group">
                                <label>Moneda</label>
                                <select id="selectMoneda" class="form-control efectoDescuento" name="SelectMoneda" style="width: 100%">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>-->
                        </div>
                    </div>
                </div>
<!--fin del div menu                -->
<!--comienzo de div graficos-->
                <div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
<!--tarjeta de la informacion de proyectos                    -->
                    <div id="cardProyectos" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                                    <div class="form-group">
                                        <h4 class="m-t-10">Proyectos</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="row">
                                        <div id="chart_proyecto" style="width: 100%; height: 20%;"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
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
                                                        echo '<td>'.$proyecto['IdProyecto'].'</td>';
                                                        echo '<td>'.$proyecto['Proyecto'].'</td>';
                                                        echo '<td>'.number_format($proyecto['Gasto'], 2).'</td>';
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
<!--tarjeta de la informacion de servicios-->
                    <div id="cardServicios" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <h4 class="m-t-10">Servicios</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
                                <div id="chart_servicios" style="width: 100%; height: 100%;"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                                                    echo '<td>'.$servicio['IdServicio'].'</td>';
                                                    echo '<td>'.$servicio['TipoServicio'].'</td>';
                                                    echo '<td>'.number_format($servicio['Gasto'], 2).'</td>';
                                                echo "</tr>";
                                            }
                                            ?>
                                         </tbody>
                                     </table>
                                 </div>
                            </div>
                        </div>
                    </div>
<!--tarjeta de la informacion de sucursal-->
                    <div id="cardSucursal" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                                    <div class="form-group">
                                        <h4 class="m-t-10">Sucursal</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
                                    <div id="chart_sucursal" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                                                        echo '<td>'.$sucursal['IdSucursal'].'</td>';
                                                        echo '<td>'.$sucursal['Sucursal'].'</td>';
                                                        echo '<td>'.number_format($sucursal['Gasto'], 2).'</td>';
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
<!--tarjeta de la informacion de categoria-->
                    <div id="cardCategoria" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                                    <div class="form-group">
                                        <h4 class="m-t-10">Categoria</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
                                    <div id="chart_categoria" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                                                        echo '<td>'.$categoria['Categoria'].'</td>';
                                                        echo '<td>'.number_format($categoria['Gasto'], 2).'</td>';
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
<!--tarjeta de la informacion de subcategoria-->
                    <div id="cardSubCategoria" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                                    <div class="form-group">
                                        <h4 class="m-t-10">SubCategoria</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
                                    <div id="chart_subCategoria" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                                                        echo '<td>'.$subcategoria['SubCategoria'].'</td>';
                                                        echo '<td>'.number_format($subcategoria['Gasto'], 2).'</td>';
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
<!--tarjeta de la informacion de concepto-->
                    <div id="cardConcepto" class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                                    <div class="form-group">
                                        <h4 class="m-t-10">Concepto</h4>
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>    
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                        
                                    <div id="chart_concepto" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
                                                        echo '<td>'.$valor['Concepto'].'</td>';
                                                        echo '<td>'.number_format($valor['Gasto'], 2).'</td>';
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
<!--fin de div graficos                -->
            </div>
        </div>
        
    </div>   
</div>

<!--<div class="theme-panel">
    <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn bg-green"><i class="fa fa-filter text-white"></i></a>
    <div class="theme-panel-content" style="width:auto; height:500px; overflow:auto;">
        <h5 class="m-t-0">Filtros</h5>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
tabla de filtros agregados                            
                <div  id="tableFiltros" class="table-responsive">
                    <table id="data-tipo-gastos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Filtro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tipo Proyecto</td>
                            </tr>
                            <tr>
                                <td>Servicios<button type="button" class="close" @click="close()"><span aria-hidden="true">&times;</span></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
                <div class="form-group">
                    <label>Proyectos</label>
                    <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($proyectos as $proyecto) {
                            echo '<option value="'.$proyecto['IdProyecto'].'">'.$proyecto['Proyecto'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Servicios</label>
                    <select id="selectServicio" class="form-control efectoDescuento" name="SelectServicio" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($servicios as $servicio) {
                            echo '<option value="'.$servicio['IdServicio'].'">'.$servicio['TipoServicio'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sucursal</label>
                    <select id="selectSucursal" class="form-control efectoDescuento" name="SelectSucursal" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($sucursales as $sucursal) {
                            echo '<option value="'.$sucursal['IdSucursal'].'">'.$sucursal['Sucursal'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <select id="selectCategoria" class="form-control efectoDescuento" name="SelectCategoria" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($categorias as $categoria) {
                            echo '<option value="1">'.$categoria['Categoria'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>SubCategoria</label>
                    <select id="selectSubCategoria" class="form-control efectoDescuento" name="SelectSubCategoria" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($subcategorias as $subcategoria) {
                            echo '<option value="1">'.$subcategoria['SubCategoria'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Concepto</label>
                    <select id="selectConcepto" class="form-control efectoDescuento" name="SelectConcepto" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($concepto as $valor) {
                            echo '<option value="1">'.$valor['Concepto'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Moneda</label>
                    <select id="selectMoneda" class="form-control efectoDescuento" name="SelectMoneda" style="width: 100%">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>-->