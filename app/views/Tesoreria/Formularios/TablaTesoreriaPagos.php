<div class="row m-t-20">
    <div class="col-md-12">
        <h3 class="m-t-10">Facturas Pendientes de Pago</h3>
    </div>
    <!--Empezando Separador-->
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>
</div>

<div class="row m-b-15 text-right">
    <div class="btn-group">
        <button id="btnSemanaAnterior" class="btn btn-white">Semana anterior</button>
        <button id="btnSemanaActual" class="btn btn-white active">Esta semana</button>
        <button id="btnSemanaSeguiente" class="btn btn-white">Proxima semana</button>
    </div>
</div>

<div class="table-responsive">
    <table id="data-table-facturas-tesoreria" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th class="never">Id</th>
                <th class="all">Técnico</th>
                <th class="all">Autorizado por</th>
                <th class="all">Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($facturasTesoreriaPago)) {
                foreach ($facturasTesoreriaPago as $key => $value) {
                    $montoFactura = (float) $value['MontoFactura'];
                    echo '<tr>';
                    echo '<td>' . $value['Id'] . '</td>';
                    echo '<td>' . $value['Tecnico'] . '</td>';
                    echo '<td>' . $value['Autoriza'] . '</td>';
                    echo '<td>' . $value['Fecha'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>                                       
        </tbody>
    </table>
</div>

