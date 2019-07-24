<div id="divMisSolicitudesCompra" class="content">
    <h1 class="page-header">Mis solicitudes de compra</h1>
    <div id="panelMisSolicitudesCompra" class="panel panel-inverse">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="m-t-10">Lista de solicitudes de compra</h3>
                </div>
                <div class="col-md-12">
                    <div class="underline m-b-15"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="data-table-mis-solitudes-compra" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <th class="all">Cliente</th>
                                    <th class="all">Proyecto</th>
                                    <th class="all">Sucursal</th>
                                    <th class="all">Fecha de Solicitud</th>
                                    <th class="all">Estatus</th>
                                    <th class="all">Decripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($datos['Solicitudes']) && count($datos['Solicitudes']) > 0) {
                                    foreach ($datos['Solicitudes'] as $key => $value) {
                                        echo '
                                        <tr>
                                            <td>' . $value['Id'] . '</td>
                                            <td>' . $value['Cliente'] . '</td>
                                            <td>' . $value['Proyecto'] . '</td>
                                            <td>' . $value['Sucursal'] . '</td>
                                            <td>' . $value['Fecha'] . '</td>
                                            <td>' . $value['Estatus'] . '</td>
                                            <td>' . $value['Descripcion'] . '</td>
                                        </tr>';
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

<div id="divFormularioSolicitarCompra" style="display: none" class="content"></div>

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
                <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
            </div>
            <div id="errorModal"></div>
        </div>
    </div>
</div>