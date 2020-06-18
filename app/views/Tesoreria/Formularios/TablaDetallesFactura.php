<div class="table-responsive">
    <table id="data-table-detalles-factura" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th class="all">Servicio</th>
                <th class="all">Ticket </th>
                <th class="all">Folio</th>
                <th class="all">Número de Vuelta</th>
                <th class="all">Sucursal</th>
                <th class="all">Tecnico</th>
                <th class="all">Monto</th>
                <th class="all">Viatico</th>
                <th class="all">Fecha</th>
                <th class="all">Archivo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($detallesFactura)) {
                foreach ($detallesFactura as $key => $value) {
                    echo '<tr>';
                    echo '<td>' . $value['IdServicio'] . '</td>';
                    echo '<td>' . $value['Ticket'] . '</td>';
                    echo '<td>' . $value['Folio'] . '</td>';
                    echo '<td>' . $value['Vuelta'] . '</td>';
                    echo '<td>' . $value['Sucursal'] . '</td>';
                    echo '<td>' . $value['Tecnico'] . '</td>';
                    echo '<td>' . $value['Monto'] . '</td>';
                    echo '<td>' . $value['Viatico'] . '</td>';
                    echo '<td>' . $value['Fecha'] . '</td>';
                    echo '<td><a href="' . $value['Archivo'] . '" target="_blank" class="btn btn-danger btn-xs "><i class="fa fa-file-pdf-o"></i> PDF</a></td>';
                    echo '</tr>';
                }
            }
            ?>                                        
        </tbody>
    </table>
</div>
