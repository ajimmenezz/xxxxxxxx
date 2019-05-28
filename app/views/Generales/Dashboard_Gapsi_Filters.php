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
            <h4 class="panel-title">Gastos</h4><?php //echo '<pre>'; var_dump($sucursales); echo '</pre>';?>
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
                                            <th class="all">Gastos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Compra</td>
                                            <td>315000</td>
                                        </tr>
                                        <tr>
                                            <td>Gastos</td>
                                            <td>160000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
<!--tabla de filtros agregados                            -->
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
                    </div>
<!--selector de filtros                    -->
                    <div id="selectFiltros" class="row">
                        <div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
                            <div class="form-group">
                                <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                                    <option value="">Proyecto</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                                    <option value="">Servicio</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                                    <option value="">Sucursal</option>
                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="selectProyecto" class="form-control efectoDescuento" name="SelectProyecto" style="width: 100%">
                                    <option value="">Categoria</option>
                                    
                                </select>
                            </div>
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
                                    <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
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
                                                        echo '<td>'.$proyecto['Gasto'].'</td>';
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
                                <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
                                     <table id="data-tipo-serivicio" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
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
                                                    echo '<td>'.$servicio['Gasto'].'</td>';
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
                                    <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
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
                                                        echo '<td>'.$sucursal['Gasto'].'</td>';
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
                                    <div id="chart_D" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
                                         <table id="data-tipo-categoria" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                             <thead>
                                                 <tr>
                                                     <th class="all">Categoria</th>
                                                     <th class="all">Gastos</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td>Compra</td>
                                                     <td>315000</td>
                                                 </tr>
                                                 <tr>
                                                     <td>Gastos</td>
                                                     <td>160000</td>
                                                 </tr>
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
                                    <div id="chart_E" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
                                         <table id="data-tipo-SubCategoria" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                             <thead>
                                                 <tr>
                                                     <th class="all">SubCategoria</th>
                                                     <th class="all">Gastos</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td>Compra</td>
                                                     <td>315000</td>
                                                 </tr>
                                                 <tr>
                                                     <td>Gastos</td>
                                                     <td>160000</td>
                                                 </tr>
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
                                    <div id="chart_F" style="width: 100%; height: 20%;"></div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="table-responsive" style="width:auto; height:300px; overflow:auto;">
                                         <table id="data-tipo-concepto" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                             <thead>
                                                 <tr>
                                                     <th class="all">Concepto</th>
                                                     <th class="all">Gastos</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td>Compra</td>
                                                     <td>315000</td>
                                                 </tr>
                                                 <tr>
                                                     <td>Gastos</td>
                                                     <td>160000</td>
                                                 </tr>
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