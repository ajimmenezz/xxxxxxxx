<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Reporte de Inventarios</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
<div id="areasViewPanel" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Inventario por Áreas</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3><?php echo "Aqui van las áreas"; ?></h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4 class="m-t-25 pull-right"><a role="button" id="btnCountView" style="display: none">Vista Conteo</a><a role="button" id="btnBranchListView">Vista Lista Sucursales</a></h4>
            </div>
        </div>
        <div class="row">
            <div class="underline col-md-12 col-sm-12 col-xs-12"></div>
        </div>
        <div id="countView">
            <div class="row m-t-15">
                <div class="col-md-6 col-sm-6 col-xs-12 table-responsive">
                    <table id="pointsAreaTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Área de Atención</th>
                                <th class="all">Total Puntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($pointsArea) && count($pointsArea) > 0) {
                                foreach ($pointsArea as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Id'] . '</td>
                                        <td>' . $v['Nombre'] . '</td>
                                        <td>' . $v['Total'] . '</td>                                        
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 table-responsive">
                    <table id="devicesAreaTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Área de Atención</th>
                                <th class="all">Total Equipos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($devicesArea) && count($devicesArea) > 0) {
                                foreach ($devicesArea as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Id'] . '</td>
                                        <td>' . $v['Nombre'] . '</td>
                                        <td>' . $v['Total'] . '</td>                                        
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row m-t-30">
                <div class="col-md-4 col-sm-4 col-xs-12 table-responsive">
                    <table id="devicesLineTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Línea</th>
                                <th class="all">Total Equipos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($devicesLine) && count($devicesLine) > 0) {
                                foreach ($devicesLine as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Id2'] . '</td>
                                        <td>' . $v['Nombre'] . '</td>
                                        <td>' . $v['Total'] . '</td>                                        
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 table-responsive">
                    <table id="devicesSublineTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Sublínea</th>
                                <th class="all">Total Equipos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($devicesSubline) && count($devicesSubline) > 0) {
                                foreach ($devicesSubline as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Id2'] . '</td>
                                        <td>' . $v['Nombre'] . '</td>
                                        <td>' . $v['Total'] . '</td>                                        
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 table-responsive">
                    <table id="devicesModelTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Id</th>
                                <th class="all">Modelo</th>
                                <th class="all">Total Equipos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($devicesModel) && count($devicesModel) > 0) {
                                foreach ($devicesModel as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Id2'] . '</td>
                                        <td>' . $v['Nombre'] . '</td>
                                        <td>' . $v['Total'] . '</td>                                        
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="branchListView" style="display:none">
            <div class="row m-t-20">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <table id="branchListTable" class="table table-details table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="never">Servicio</th>
                                <th class="never">Ticket SD</th>
                                <th class="never">Ticket AD</th>
                                <th class="all">Sucursal</th>
                                <th class="all">Región</th>
                                <th class="all">Atiende</th>
                                <th class="all">Estatus Censo</th>
                                <th class="all">Último Censo Concluido</th>
                                <th class="all">Total Equipos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($branchList) && count($branchList) > 0) {
                                foreach ($branchList as $k => $v) {
                                    echo '
                                    <tr>
                                        <td>' . $v['Servicio'] . '</td>
                                        <td>' . $v['SD'] . '</td>
                                        <td>' . $v['Ticket'] . '</td>
                                        <td>' . $v['Sucursal'] . '</td>
                                        <td>' . $v['Region'] . '</td>
                                        <td>' . $v['Usuario'] . '</td>
                                        <td>' . $v['Estatus'] . '</td>
                                        <td>' . $v['UltimaActualizacion'] . '</td>
                                        <td>' . $v['Total'] . '</td>
                                    </tr>
                                ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>