<div id="listaPoliza" class="content">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <h1 class="page-header">SLA's</h1>
        </div>
        <div class="col-md-6 col-xs-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acciones <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a id="reporteExcel" href="javascript:;"><i class="fa fa-file-excel-o"></i> Excel</a></li>                
                </ul>
            </div>
        </div>
    </div>
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
                <div class="col-md-5 col-xs-5">
                    <div class="form-group">
                        <label>Desde *</label>
                        <div class='input-group date' id='desdeSLA'>
                            <input type='text' id="txtDesdeSLA" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                
                </div>                                                        
                <div class="col-md-5 col-xs-5">
                    <div class="form-group">
                        <label>Hasta</label>
                        <div class='input-group date' id='hastaSLA'>
                            <input type='text' id="txtHastaSLA" class="form-control" value="" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                
                </div>
                <div class="col-md-2 col-xs-2 m-t-20 text-center">
                    <button type="button" id="btnBuscarSLA" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-md-12">
                    <div id="errorFiltroSLA"></div>
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
                            <th class="all">Zona</th>
                            <th class="all">Solicitud asignada a</th>
                            <th class="all">Técnico</th>
                            <th class="all">Creación Ticket</th>
                            <th class="all">Intervalo de Creación de Folio y Ticket</th>
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
                                echo '<td>' . $value['Zona'] . '</td>';
                                echo '<td>' . $value['AtiendeSolicitud'] . '</td>';
                                echo '<td>' . $value['Tecnico'] . '</td>';
                                echo '<td>' . $value['FechaCreacionServicio'] . '</td>';
                                echo '<td>' . $value['IntervaloSolicitudServicioCreacion'] . '</td>';
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

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button type="button" id="btnAceptar" class="btn btn-primary"><i class="fa fa-check"></i> Aceptar</button>
            </div>
            <div id="errorModal"></div>
        </div>
    </div>
</div>