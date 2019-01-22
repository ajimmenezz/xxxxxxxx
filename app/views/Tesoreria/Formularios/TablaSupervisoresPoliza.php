<div class="table-responsive">
    <table id="data-table-facturas-poliza" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th class="never">Id</th>
                <th class="all">Servicio</th>
                <th class="all">Ticket </th>
                <th class="all">Folio</th>
                <th class="all">Número de Vuelta</th>
                <th class="all">Sucursal</th>
                <th class="all">Tecnico</th>
                <th class="all">Fecha</th>
                <th class="all">Estatus Vuelta</th>
                <th class="all">Estatus Servicio</th>
                <th class="all">Autorizado por</th>
                <th class="all">Monto</th>
                <th class="all">Viatico</th>
                <th class="all">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($vueltas)) {
                foreach ($vueltas as $key => $value) {
                    echo '<tr>';
                    echo '<td>' . $value['Id'] . '</td>';
                    echo '<td>' . $value['IdServicio'] . '</td>';
                    echo '<td>' . $value['Ticket'] . '</td>';
                    echo '<td>' . $value['Folio'] . '</td>';
                    echo '<td>' . $value['Vuelta'] . '</td>';
                    echo '<td>' . $value['Sucursal'] . '</td>';
                    echo '<td>' . $value['NombreAtiende'] . '</td>';
                    echo '<td>' . $value['Fecha'] . '</td>';
                    echo '<td>' . $value['Estatus'] . '</td>';
                    echo '<td><font color="red">' . $value['EstatusServicio'] . '</font></td>';
                    echo '<td>' . $value['SupervisorAutorizado'] . '</td>';
                    echo '<td>' . $value['Monto'] . '</td>';
                    echo '<td>' . $value['Viatico'] . '</td>';
                    echo '<td>' . $value['Total'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>                                       
        </tbody>
    </table>
</div>

