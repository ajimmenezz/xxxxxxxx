<div id="listaPoliza" class="content">
    <h1 class="page-header">SLA's</h1>

    <div id="panelSLA" class="panel panel-inverse">

        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">SLA's</h4>
        </div>

        <div class="panel-body">

            <div class="row"> 
                <div class="col-md-12">
                    <div class="errorListaPoliza"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">  
                    <h3 class="m-t-10">Lista Folios</h3>
                </div>

                <div class="col-xs-12 col-md-3 text-right hidden">
                    <a href="javascript:;" class="btn btn-info m-r-5 " id="btnMostrarServicios"><i class="fa fa-refresh"></i> Mostrar todos los tickets</a>
                </div>

                <div class="col-xs-12 col-md-3 hidden">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputBuscarFolio" placeholder="Folio"/>
                            <span class="input-group-addon">
                                <a href="javascript:;" class="btn btn-default btn-xs" id="btnBuscarFolio"><i class="fa fa-search"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="underline m-b-15 m-t-15"></div>
                </div>
            </div> 

            <div class="table-responsive">
                <table id="data-table-SLA" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Folio</th>
                            <th class="all">Sucursal</th>
                            <th class="all">Técnico</th>
                            <th class="all">Creación Folio</th>
                            <th class="all">Inicio Folio</th>
                            <th class="all">Tiempo Transcurrido</th>
                            <th class="all">Tiempo Limite</th>
                            <th class="all">Prioridad</th>
                            <th class="all">Local/Foraneo</th>
                            <th class="all">SLA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos['folios'])) {
                            foreach ($datos['folios'] as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . $value['Folio'] . '</td>';
                                echo '<td>' . $value['Sucursal'] . '</td>';
                                echo '<td>' . $value['Tecnico'] . '</td>';
                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                echo '<td>' . $value['FechaInicio'] . '</td>';
                                echo '<td>' . $value['TiempoTranscurrido'] . '</td>';
                                echo '<td>' . $value['TiempoPrioridad'] . '</td>';
                                echo '<td>' . $value['Prioridad'] . '</td>';
                                echo '<td>' . $value['LocalForaneo'] . '</td>';
                                echo '<td>' . $value['SLA'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
