<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table id="pendingServicesTable" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="never">Servicio</th>
                    <th class="all">Ticket AdIST</th>
                    <th class="all">Ticket SD</th>
                    <th class="all">Tipo Servicio</th>
                    <th class="all">Sucursal</th>
                    <th class="all">Zona</th>
                    <th class="all">Atiende</th>
                    <th class="all">Estatus</th>
                    <th class="all">Registro Calendario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($pendingServices) && count($pendingServices) > 0) {
                    foreach ($pendingServices as $k => $v) {
                        $calendarLabel = 'Sin Registro';
                        if ($v['CalendarId'] != "") {
                            $calendarLabel = '<a target="_blank" href="' . $v['CalendarLink'] . '">Registrado</a>';
                        }
                        echo '
                        <tr>
                            <td>' . $v['Id'] . '</td>
                            <td>' . $v['Ticket'] . '</td>
                            <td>' . $v['Folio'] . '</td>
                            <td>' . $v['TipoServicio'] . '</td>
                            <td>' . $v['Sucursal'] . '</td>
                            <td>' . $v['Zona'] . '</td>
                            <td>' . $v['Atiende'] . '</td>
                            <td>' . $v['Estatus'] . '</td>
                            <td>' . $calendarLabel . '</td>
                        </tr>
                        ';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>