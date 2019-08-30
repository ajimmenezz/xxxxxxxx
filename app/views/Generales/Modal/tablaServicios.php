<div class="row">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Servicios de la Solicitud.</legend>
        </fieldset>  
    </div>
</div>

<div class="row">
    <div class="table-responsive">
        <table id="data-table-servicios" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th class="all">Id</th>
                    <th class="all">Ticket</th>
                    <th class="all">Estatus </th>
                    <th class="all">Tipo Servicio</th>
                    <th class="all">Sucursal</th>
                    <th class="all">Fecha</th>
                    <th class="all">Atiende</th>
                    <th class="all">Descripción</th>
                    <th class="never">Detalles del Servicio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $host = $_SERVER['SERVER_NAME'];
                if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                    $link = 'https://siccob.solutions/Detalles/Servicio/';
                } elseif ($host === 'pruebas.siccob.solutions' || $host === 'www.pruebas.siccob.solutions') {
                    $link = 'https://pruebas.siccob.solutions/Detalles/Servicio/';
                } else {
                    $link = 'http://' . $host . '/Detalles/Servicio/';
                }
                if (!empty($listaServicios)) {
                    foreach ($listaServicios as $key => $value) {
                        echo '<tr>';
                        echo '<td>' . $value['Id'] . '</td>';
                        echo '<td>' . $value['Ticket'] . '</td>';
                        echo '<td>' . $value['NombreEstatus'] . '</td>';
                        echo '<td>' . $value['TipoServicio'] . '</td>';
                        echo '<td>' . $value['Sucursal'] . '</td>';
                        echo '<td>' . $value['FechaCreacion'] . '</td>';
                        echo '<td>' . $value['Atiende'] . '</td>';
                        echo '<td>' . $value['Descripcion'] . '</td>';
//                        echo '<td>' . $value['IdSolicitud'] . '</td>';
                        echo '<td><a href="' . $link . $value['Id'] . '" class="btn btn-success m-r-5"><i class="fa fa-eye"></i> Ver Servicio</a></td>';
                        echo '</tr>';
                    }
                }
                ?>                                        
            </tbody>
        </table>
    </div>
</div>