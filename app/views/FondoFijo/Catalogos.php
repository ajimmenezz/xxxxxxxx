<div id="seccionCatalogos" class="content">
    <h1 class="page-header">Catálogos <small> Fondo Fijo</small></h1>
    <div id="panel-catalogos" class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
        <div class="panel-heading p-0">
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse">
                    <li class="prev-button"><a href="javascript:;" data-click="prev-tab" class="text-success"><i class="fa fa-arrow-left"></i></a></li>
                    <li class="active"><a href="#TiposCuenta" data-toggle="tab">Tipos de Cuenta</a></li>
                    <li class=""><a href="#Conceptos" data-toggle="tab">Conceptos</a></li>
                    <li class=""><a href="#Montos" data-toggle="tab">Montos Por Usuario</a></li>
                    <!--<li class=""><a href="#Areas" data-toggle="tab">Áreas por Concepto</a></li>
                    <li class=""><a href="#Ubicaciones" data-toggle="tab">Ubicaciones por Área</a></li>
                    <li class=""><a href="#Accesorios" data-toggle="tab">Accesorios por Sistema</a></li>
                    <li class=""><a href="#Material" data-toggle="tab">Material por Accesorio</a></li>
                    <li class=""><a href="#Kits" data-toggle="tab">Kits de Material</a></li> -->
                    <li class="next-button"><a href="javascript:;" data-click="next-tab" class="text-success"><i class="fa fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
                <div id="errorMessage"></div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="TiposCuenta">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6 col-sm-offset-6 col-sm-6 col-xs-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="txtNuevoTipoCuenta" class="form-control" placeholder="Nuevo Tipo de Cuenta">
                                <span role="button" id="btnAddTipoCuenta" class="input-group-addon bg-aqua"><i class="fa fa-plus text-white"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Lista de Tipos de Cuenta</h4>
                            <div class="underline m-b-10"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="table-tipos-cuenta" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="none">Id</th>
                                            <th class="none">Flag</th>
                                            <th class="all">Tipo de Cuenta</th>
                                            <th class="all" style="width: 25%">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($datos['TiposCuenta']) && !empty($datos['TiposCuenta'])) {
                                            foreach ($datos['TiposCuenta'] as $key => $value) {
                                                echo ""
                                                    . "<tr>"
                                                    . " <td>" . $value['Id'] . "</td>"
                                                    . " <td>" . $value['Flag'] . "</td>"
                                                    . " <td>" . $value['Nombre'] . "</td>"
                                                    . " <td>" . $value['Estatus'] . "</td>"
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
            </div>
            <div class="tab-pane fade" id="Montos">
                <div class="panel-body">
                    <div id="listaUsuariosFondoFijo">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h4>Lista de Usuarios y Fondo Fijo</h4>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="underline m-b-10"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-montos-usuario" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="none">Id</th>
                                                <th class="all">Usuario</th>
                                                <th class="all">Perfil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($datos['Usuarios']) && !empty($datos['Usuarios'])) {
                                                foreach ($datos['Usuarios'] as $key => $value) {
                                                    echo ""
                                                        . "<tr>"
                                                        . " <td>" . $value['Id'] . "</td>"
                                                        . " <td>" . $value['Nombre'] . "</td>"
                                                        . " <td>" . $value['Perfil'] . "</td>"
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
                    <div id="formularioEditarMontos" style="display:none"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="Conceptos">
                <div class="panel-body">
                    <div id="listaConceptos">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h4>Lista de Conceptos</h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                                <button class="btn btn-success pull-right" id="btnAddConcepto"><i class="fa fa-plus text-white"></i></button>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="underline m-b-10"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table-conceptos" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="none">Id</th>
                                                <th class="all">Concepto</th>
                                                <th class="all">Tipos de Cuenta</th>
                                                <th class="all">Tipos de Comprobante</th>
                                                <th class="all">¿Extraordinario?</th>
                                                <th class="all">Monto</th>
                                                <th class="all">Alternativos</th>
                                                <th class="all">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($datos['Conceptos']) && !empty($datos['Conceptos'])) {
                                                foreach ($datos['Conceptos'] as $key => $value) {
                                                    echo ""
                                                        . "<tr>"
                                                        . " <td>" . $value['Id'] . "</td>"
                                                        . " <td>" . $value['Nombre'] . "</td>"
                                                        . " <td>" . $value['Cuentas'] . "</td>"
                                                        . " <td>" . $value['Comprobante'] . "</td>"
                                                        . " <td>" . $value['Extraordinario'] . "</td>"
                                                        . " <td>$" . $value['Monto'] . "</td>"
                                                        . " <td>" . $value['Alternativos'] . "</td>"
                                                        . " <td>" . $value['Estatus'] . "</td>"
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
                    <div id="formularioConceptos" style="display:none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

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