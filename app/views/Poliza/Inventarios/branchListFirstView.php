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
<div id="branchListPanel" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Inventarios por Sucursal</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3>Inventarios por Sucursal</h3>
            </div>
            <div class="underline col-md-12 col-sm-12 col-xs-12"></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                <table id="branchInventoriesTable" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
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
                            <th class="all">Total Censos</th>
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
                                        <td>' . $v['TotalCensos'] . '</td>
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