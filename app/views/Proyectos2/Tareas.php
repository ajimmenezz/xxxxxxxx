<!-- Empezando #contenido -->
<div id="content" class="content">
    <div id="divListaTareas">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1 class="page-header">Seguimiento de Tareas</h1>
            </div>           
        </div>                 
        <div id="panel-table-tareas" class="panel panel-inverse">            
            <div class="panel-heading"> 
                <h4 class="panel-title">Tareas de Proyecto</h4>
            </div>            
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorTableTareas"></div>
                    </div>
                </div>
                <div class="row">                           
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-tareas" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>                                
                                        <th class="never">Id</th>
                                        <th class="never">IdUsuarios</th>                                        
                                        <th class="never">IdPredecesora</th>                                        
                                        <th class="all">Proyecto</th>
                                        <th class="all">Sucursal</th>
                                        <th class="all">Tarea</th>
                                        <th class="all">Avance</th>                                        
                                        <th class="all">Predecesora</th>                                        
                                        <th class="all">Inicio</th>                                        
                                        <th class="all">Fin</th>                                        
                                        <th class="all">Lider</th>                                        
                                        <th class="all">Usuarios</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($datos['Tareas'])) {

                                        foreach ($datos['Tareas'] as $key => $value) {
                                            $usuarios = explode(",", $value['Usuarios']);
                                            $_usuarios = '';
                                            foreach ($usuarios as $k => $v) {                                                
                                                $_usuarios .= $v . "<br/>";
                                            }
                                            $_usuarios = substr($_usuarios, 0, -5);


                                            echo '<tr>';
                                            echo '<td>' . $value['Id'] . '</td>';
                                            echo '<td>' . $value['IdUsuarios'] . '</td>';
                                            echo '<td>' . $value['IdPredecesora'] . '</td>';
                                            echo '<td>' . $value['Proyecto'] . '</td>';
                                            echo '<td>' . $value['Sucursal'] . '</td>';
                                            echo '<td>' . $value['Tarea'] . '</td>';
                                            echo '<td class="text-center">' . $value['Avance'] . '%</td>';
                                            echo '<td>' . (($value['Predecesora'] != '') ? $value['Predecesora'] : 'N/A') . '</td>';
                                            echo '<td class="text-center">' . $value['Inicio'] . '</td>';
                                            echo '<td class="text-center">' . $value['Fin'] . '</td>';
                                            echo '<td>' . $value['Lider'] . '</td>';
                                            echo '<td>' . $_usuarios . '</td>';
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
        </div>        
    </div>

    <div id="divFormularioSeguimientoTarea" style="display:none;"></div>

    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <div id="error-in-modal"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarCambiosModal" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Finalizando #contenido -->
