<!-- Empezando #contenido -->
<div id="content" class="content">
    <div id="divListaProyectos">
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <h1 class="page-header">Proyectos <small>Especiales</small></h1>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                <label id="btn-proyecto-nuevo" class="btn btn-success">
                    <i class="fa fa-file"></i> Nuevo Proyecto
                </label>  
            </div>
        </div>                 
        <div id="panel-table-proyectos" class="panel panel-inverse">            
            <div class="panel-heading"> 
                <h4 class="panel-title">Proyectos Sin Iniciar</h4>
            </div>            
            <div class="panel-body">                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="errorTablePlaneacion"></div>
                    </div>
                </div>
                <div class="row">                           
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-proyectos-sin-iniciar" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>                                
                                        <th class="never">Id</th>
                                        <th class="all">Ticket</th>
                                        <th class="all">Proyecto</th>
                                        <th class="all">Complejo</th>
                                        <th class="all">Estado</th>
                                        <th class="all">Fecha Inicio</th>
                                        <th class="all">Fecha Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($datos['ProyectosSinAtender'] as $key => $value) {
                                        echo '<tr>';
                                        echo '<td>' . $value['Id'] . '</td>';
                                        echo '<td>' . (($value['Ticket'] !== '0' ) ? $value['Ticket'] : '') . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Complejo'] . '</td>';
                                        echo '<td>' . $value['Estado'] . '</td>';
                                        echo '<td>' . (($value['FechaInicio'] !== '0000-00-00' ) ? $value['FechaInicio'] : '') . '</td>';
                                        echo '<td>' . (($value['FechaTermino'] !== '0000-00-00' ) ? $value['FechaTermino'] : '') . '</td>';
                                        echo '</tr>';
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
    
    <div id="divNuevoProyecto" style="display:none;"></div>
    <div id="divDetallesProyecto" style="display:none;"></div>
    <div id="divNuevaUbicacion" style="display:none;"></div>
    <div id="divDetallesTarea" style="display:none;"></div>

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
