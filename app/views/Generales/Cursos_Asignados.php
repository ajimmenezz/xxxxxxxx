<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Cursos asignados</h1>
    <!-- end page-header -->

    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" >
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Avence total</h4>
                                    <p>65.33%</p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-red">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p>33.77%</p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-blue">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Total de cursos</h4>
                                    <p>90</p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    En esta sección encontraras una serie de cursos en línea que se te han asignado a tu perfil, los cuales tiene 
                    como finalidad crear un aporte profecional en tu formación. Por lo tanto esta 
                    herramienta web te permite ir registrando el progreso de cada uno de los cursos que vas tomando. <br><br>

                    <b>Nota Es importante mencionar que el área de Capacitación estará al pendiente de progreso que lleves.</b><br>
                </div>

            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursosAsignados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <td class="all">Curso</td>
                                    <td class="all">Avance</td>
                                    <td class="all">Fecha de asignación</td>
                                    <td class="all">Estatus</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($datos['filas'])) {
                                    foreach ($datos['filas'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . $value['Curso'] . '</td>';
                                        echo '<td>' . $value['Avance'] . '</td>';
                                        echo '<td>' . $value['FechaAsignacion'] . '</td>';
                                        echo '<td>' . $value['Estatus'] . '</td>';
                                        echo '<td class="text-center">' . $value['Acciones'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>
</div>
<!-- Finalizando #contenido -->

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div id="error-in-modal"></div>
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnAceptar" class="btn btn-success">Comenzar</button>
            </div>
            <div id="errorModal"></div>
        </div>
    </div>
</div>
