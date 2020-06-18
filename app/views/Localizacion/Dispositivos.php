<!-- Empezando #contenido -->
<div id="divDispositivos" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Dispositivos Global GPS</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel nuevo proyecto-->
    <div id="panelDispositivos" class="panel panel-inverse borde-sombra">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Lista de dispositivos</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                    <table id="table-dispositivos" class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>IMEI</th>
                                <th>Usuario Asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($datos['dispositivos']) && count($datos['dispositivos']) > 0) {
                                foreach ($datos['dispositivos'] as $key => $value) {
                                    echo ''
                                    . '<tr>'
                                    . ' <td>' . $value['IMEI'] . '</td>'
                                    . ' <td>' . $value['Usuario'] . '</td>'
                                    . '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Finalizando cuerpo del panel-->
        </div>
        <!-- Finalizando panel nuevo proyecto -->   

    </div>
    <!-- Finalizando #contenido -->

</div>

<div id="divDetallesDispositivo" class="content" style="display: none"></div>