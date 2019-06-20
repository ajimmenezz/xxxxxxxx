<div class="row">
    <div class="col-md-12">
        <h4>Materiales</h4>
    </div>
    <div class="col-md-12">
        <div class="underline"></div>
    </div>
</div>
<ul class="nav nav-pills">
    <li class="active"><a href="#MaterialesUtilizados" data-toggle="tab">Materiales Utilizados</a></li>
    <li><a href="#MaterialesDisponibles" data-toggle="tab">Materiales disponibles</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="MaterialesUtilizados">
        <div class="table-responsive">
            <table id="data-table-productos-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="none">Id</th>
                        <th class="all">Clave</th>
                        <th class="all">Producto</th>
                        <th class="all">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($materiales['lista']) && count($materiales['lista']) > 0) {
                        foreach ($materiales['lista'] as $key => $value) {
                            echo '
                            <tr>
                                <td>' . $value['Id'] . '</td>
                                <td>' . $value['Clave'] . '</td>
                                <td>' . $value['Producto'] . '</td>                                                    
                                <td>' . $value['Cantidad'] . '</td>                                                    
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="MaterialesDisponibles">
        <div class="table-responsive">
            <table id="data-table-sae-products" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                <thead>
                    <tr>
                        <th class="all">Clave</th>
                        <th class="all">Producto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($productosSAE) && count($productosSAE) > 0) {
                        foreach ($productosSAE as $key => $value) {
                            if (!in_array($value['Clave'], $materiales['claves'])) {
                                echo '
                                <tr>
                                    <td>' . $value['Clave'] . '</td>
                                    <td>' . $value['Nombre'] . '</td>                                                    
                                </tr>';
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>