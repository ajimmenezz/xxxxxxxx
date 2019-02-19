<div id="panelValidacionExistencia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Seguimiento de Solicitud de Producto</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Seguimiento de Solicitud de Producto</h4>
            </div>
        </div>
        <div class="row">
            <div class="underline m-b-10"></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="lista-equipos-enviados-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Refacción</th>
                                <th class="all">Serie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($refaccionEquipoUtilizadoAlmacen)) {
                                foreach ($refaccionEquipoUtilizadoAlmacen as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Producto'] . '</td>';
                                    echo '<td>' . $value['Serie'] . '</td>';
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