<!-- Empezando #contenido -->
<div id="divFormularioSolicitarCompra" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Solicitar Compra</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel seguimiento compras -->
    <div id="panelFormularioSolicitarCompra" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading"></div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando cuerpo del panel-->
        <div class="panel-body">
            <div class="row">
                <!--Empezando error-->
                <div class="col-md-12">
                    <div class="errorListaCompras"></div>
                </div>
                <!--Finalizando Error-->
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-6">
                            <h3 class="m-t-10">Formulario de solicitud de compra</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-warning fade in p-5">
                                <strong>
                                    Todos los campos marcados con * son obligatorios.
                                </strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="underline m-b-15"></div>
                        </div>
                        <!--Finalizando Separador-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Cliente:*</label>
                        <select id="listClientes" class="form-control" style="width: 100%" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                            <?php
                            if (isset($datos['Clientes']) && count($datos['Clientes']) > 0) {
                                foreach ($datos['Clientes'] as $key => $value) {
                                    echo '<option value="' . $value['ID'] . '">' . $value['Nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Proyecto:*</label>
                        <select id="listProyectos" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="f-w-600 f-s-13">Sucursal:*</label>
                        <select id="listSucursales" class="form-control" style="width: 100%" disabled="" data-parsley-required="true">
                            <option value="">Selecciona . . .</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#nav-pills-productos-solicitados" data-toggle="tab">Productos solicitados</a></li>
                        <li><a href="#nav-pills-productos-disponibles" data-toggle="tab">Productos disponibles</a></li>
                        <li><a href="#nav-pills-observaciones" data-toggle="tab">Observaciones de Solicitud</a></li>
                        <li><a href="#nav-pills-archivos-previos" data-toggle="tab">Adjuntar Archivos</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="nav-pills-productos-solicitados">
                            <div class="table-responsive">
                                <table id="data-table-productos-solicitados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Clave</th>
                                            <th class="all">Producto</th>
                                            <th class="all">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-pills-productos-disponibles">
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
                                        if (isset($datos['Productos']) && count($datos['Productos']) > 0) {
                                            foreach ($datos['Productos'] as $key => $value) {
                                                echo '
                                                <tr>
                                                    <td>' . $value['Clave'] . '</td>
                                                    <td>' . $value['Nombre'] . '</td>                                                    
                                                </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-pills-archivos-previos">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="f-w-600 f-s-13">Adjuntar Archivos *;</label>
                                        <input id="archivosSolicitud" name="archivosSolicitud[]" type="file" multiple="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-pills-observaciones">
                            <div class="form-group">
                                <label>Observaciones de Solicitud *</label>
                                <textarea id="txtObservacionesSolicitud" class="form-control" placeholder="Ingresa las observaciones de la Solicitud de Compra" rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="errorFormulario" class="col-md-12 text-center">                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a id="brnSolicitarCompra" class="btn btn-success p-l-25 p-r-25 p-t-10 p-b-10 f-w-600 f-s-15">Solicitar Compra</a>
                </div>
            </div>
        </div>
        <!--Finalizando cuerpo del panel-->
    </div>
    <!-- Finalizando panel seguimiento compras -->
</div>
<!-- Finalizando #contenido -->

<!--Empezando seccion para el seguimiento de un servicio sin clasificar-->
<div id="seccionSeguimientoServicio" class="content hidden"></div>
<!-- Finalizando seccion para el seguimiento de un servicio sin clasificar -->

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