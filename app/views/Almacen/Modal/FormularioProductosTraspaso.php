<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row m-t-35"> 
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-grup">
                <h4 class="m-t-10">Productos de Póliza TI y Salas 4D</h4>
            </div>
        </div>                                    
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
    </div>
    <div class="table-responsive m-t-25">    
        <table id="data-table-inventario" class="table table-hover table-striped table-bordered no-wrap m-t-25" style="cursor:pointer" width="100%">
            <thead>
                <tr>                
                    <th class="all">Producto</th>
                    <th class="all">Tipo</th>                
                    <th class="all">Serie</th>
                    <th class="all">Estatus</th>
                    <th class="all">Traspasar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($poliza) && !empty($poliza)) {
                    foreach ($poliza as $key => $value) {
                        echo '<tr data-id="' . $value['Id'] . '">'
                        . '<td>' . $value['Producto'] . '</td>'
                        . '<td>' . $value['Tipo'] . '</td>'
                        . '<td>' . $value['Serie'] . '</td>'
                        . '<td>' . $value['Estatus'] . '</td>'
                        . '<td class="text-center">'
                        . ' <input type="checkbox" class="producto-inventario" id="producto-' . $value['Id'] . '" data-id="' . $value['Id'] . '" />'
                        . '</td>'
                        . '</tr>';
                    }
                }

                if (isset($salas) && !empty($salas)) {
                    foreach ($salas as $key => $value) {
                        echo '<tr data-id="' . $value['Id'] . '">'
                        . '<td>' . $value['Producto'] . '</td>'
                        . '<td>' . $value['Tipo'] . '</td>'
                        . '<td>' . $value['Serie'] . '</td>'
                        . '<td>' . $value['Estatus'] . '</td>'
                        . '<td class="text-center">'
                        . ' <input type="checkbox" class="producto-inventario" id="producto-' . $value['Id'] . '" data-id="' . $value['Id'] . '" />'
                        . '</td>'
                        . '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="row m-t-35"> 
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-grup">
                <h4 class="m-t-10">Otros Productos (SAE)</h4>
            </div>
        </div>                                    
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 underline"></div>
    </div>
    <div class="table-responsive m-t-25">    
        <table id="data-table-inventario-otros" class="table table-hover table-striped table-bordered no-wrap m-t-25" style="cursor:pointer" width="100%">
            <thead>
                <tr>                
                    <th class="all">Producto</th>                    
                    <th class="all">Cantidad</th>
                    <th class="all">Estatus</th>
                    <th class="all">Traspasar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($otros) && !empty($otros)) {
                    foreach ($otros as $key => $value) {
                        echo '<tr>'
                        . '<td>' . $value['Producto'] . '</td>'
                        . '<td>' . $value['Cantidad'] . '</td>'
                        . '<td>' . $value['Estatus'] . '</td>'
                        . '<td class="text-center">'
                        . ' <input type="hidden" value="' . $value['Cantidad'] . '" id="cantidad-producto-hidden-' . $value['Id'] . '" />'
                        . ' <input type="number" class="cantidad-producto-otros" data-id="' . $value['Id'] . '" min="0" max="' . $value['Cantidad'] . '" />'
                        . '</td>'
                        . '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>