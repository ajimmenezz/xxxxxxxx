<div id="contentDashboardGapsi" class="content">
    <h1 class="page-header">Dashboard Gapsi</h1>
    <div id="panelDashboardGapsi" class="panel panel-inverse">
       
        <div class="panel-heading">
            <h4 class="panel-title">Gastos</h4>
        </div>
        
        <div class="panel-body">
            <div class="row">
<!--grafica principal dashboard                -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">                        
                    <div class="row">
                        <div id="graphDashboard" style="width: 100%; height: 100%;"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="table-responsive">
<!--tabla de los tipos de proyectos                            -->
                            <table id="data-table-tipo-proyectos" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">idTipoProyecto</th>
                                        <th class="all">Tipo Proyecto</th>
                                        <th class="all">Proyectos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Tipo</td>
                                        <td>10</td>
                                    </tr><tr>
                                        <td>1</td>
                                        <td>Tipo</td>
                                        <td>10</td>
                                    </tr><tr>
                                        <td>1</td>
                                        <td>Tipo</td>
                                        <td>10</td>
                                    </tr><tr>
                                        <td>1</td>
                                        <td>Tipo</td>
                                        <td>10</td>
                                    </tr>
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
                                <tr>
                                    <td>1</td>
                                    <td>Proyecto X</td>
                                    <td>10</td>
                                    <td>20/03/19</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>   
</div>

<div id="dashboardGapsiFilters" class="content hidden"></div>