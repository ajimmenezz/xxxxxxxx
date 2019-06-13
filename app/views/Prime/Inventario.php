<div id="seccionSucursales" class="content">
    <h1 class="page-header">Inventario Prime</h1>
    <div id="panelSucursales" class="panel panel-inverse">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div id="listaInstalaciones">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Sucursales Prime</h4>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="underline m-b-10"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="errorMessage"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table-sucursales" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="none">Id</th>
                                        <th class="all">Clave</th>
                                        <th class="all">Sucursal</th>
                                        <th class="all">Region</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($datos['Sucursales']) && !empty($datos['Sucursales'])) {
                                        foreach ($datos['Sucursales'] as $key => $value) {
                                            echo ""
                                                . "<tr>"
                                                . " <td>" . $value['Id'] . "</td>"
                                                . " <td>" . $value['Clave'] . "</td>"
                                                . " <td>" . $value['Nombre'] . "</td>"
                                                . " <td>" . $value['Region'] . "</td>"
                                                . "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="formularioDepositar" style="display:none"></div>
        </div>
    </div>
</div>

<div id="seccionInventario" class="content" style="display:none"></div>

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
        </div>
    </div>
</div>