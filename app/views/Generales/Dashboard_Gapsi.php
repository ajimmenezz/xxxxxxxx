<div id="contentDashboardGapsi" class="content">
    <h1 class="page-header">Dashboard Gapsi</h1>
    <div id="panelDashboardGapsi" class="panel panel-inverse">
       
        <div class="panel-heading">
            <h4 class="panel-title">Gastos</h4>
        </div>
        
        <div class="panel-body">
            <div class="row">
<!--grafica principal dashboard                -->
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">                        
                    <div class="row">
                        <div id="graphDashboard" style="width: 100%; height: 400px;  max-height:400px"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="table-responsive">
<!--tabla de los tipos de proyectos                            -->
                            <table id="data-table-tipo-proyectos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th class="all">Tipo Proyecto</th>
                                        <th class="all">Proyectos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['TiposProyectos'] as $valorTipoProyecto) {
                                        echo "<tr>";
                                            echo '<td>'.$valorTipoProyecto['Tipo'].'</td>';
                                            echo '<td>'.$valorTipoProyecto['Tipo'].'</td>';
                                            echo '<td>'.$valorTipoProyecto['Proyectos'].'</td>';
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                    <div class="form-group">
                        <h3 class="m-t-10">Proyectos</h3>
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
                                    <th class="never">idProyecto</th>
                                    <th class="all">Proyecto</th>
                                    <th class="all">Gasto</th>
                                    <th class="all">Fecha Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos['Proyectos'] as $valorProyecto) {
                                    echo "<tr>";
                                        echo '<td>'.$valorProyecto['IdProyecto'].'</td>';
                                        echo '<td>'.$valorProyecto['Descripcion'].'</td>';
                                        echo '<td>'.$valorProyecto['Gasto'].'</td>';
                                        echo '<td>'.$valorProyecto['FCreacion'].'</td>';
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

<div id="dashboardGapsiFilters">
    <?php
                                    var_dump($datos['TiposProyectos']);
    ?>
</div>