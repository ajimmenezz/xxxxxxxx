<div id="divFormularioSolicitud" class="content">    
    <h1 class="page-header">Proyectos Especiales V2</h1>
    <div id="panelTablaProyectos" class="panel panel-inverse">        
        <div class="panel-heading">    
            <h4 class="panel-title">Proyectos Especiales V2</h4>
        </div>
        <div class="panel-body">
            <?php
//            echo "<pre>";
//            var_dump($datos['Sucursales']);
//            echo "</pre>";
            ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Lista de Proyectos</h4>
                    <div class="underline m-b-10"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="table-proyectos-especiales" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Folio SD</th>
                                    <th>Sucursal</th>
                                    <th>Técnico Asignado</th>
                                    <th>Estatus</th>
                                    <th>Tipo de Proyecto</th>
                                    <th>Categoría</th>
                                    <th>Actividad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['proyectos']) && !empty($datos['proyectos'])) {
                                    foreach ($datos['proyectos'] as $key => $value) {
                                        echo ''
                                        . '<tr>'
                                        . ' <td>' . $value['Ticket'] . '</td>'
                                        . ' <td>' . $value['Folio'] . '</td>'
                                        . ' <td>' . $value['Sucursal'] . '</td>'
                                        . ' <td>' . $value['Ingeniero'] . '</td>'
                                        . ' <td>' . $value['Estatus'] . '</td>'
                                        . ' <td>' . $value['Tipo'] . '</td>'
                                        . ' <td>' . $value['Categoria'] . '</td>'
                                        . ' <td>' . $value['Actividad'] . '</td>'
                                        . '</tr>';
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

<!--Empezando seccion para la captura del inventario por sala-->
<div id="capturePageInventario" class="content" style="display:none"></div>
<!--Finalizando seccion para la captura del inventario por sala-->