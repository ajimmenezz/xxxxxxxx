<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Editar Solicitud de Compra</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
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
                        if (isset($clientes) && count($clientes) > 0) {
                            foreach ($clientes as $key => $value) {
                                $selected = '';
                                if ($value['ID'] == $solicitud['IdCliente']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Proyecto:*</label>
                    <select id="listProyectos" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($proyectos) && count($proyectos) > 0) {
                            foreach ($proyectos as $key => $value) {
                                $selected = '';
                                if ($value['ID'] == $solicitud['IdProyecto']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Sucursal:*</label>
                    <select id="listSucursales" class="form-control" style="width: 100%" data-parsley-required="true">
                        <option value="">Selecciona . . .</option>
                        <?php
                        if (isset($sucursales) && count($sucursales) > 0) {
                            foreach ($sucursales as $key => $value) {
                                $selected = '';
                                if ($value['ID'] == $solicitud['IdSucursal']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $value['ID'] . '" ' . $selected . '>' . $value['Nombre'] . '</option>';
                            }
                        }
                        ?>
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
                                    <?php
                                    if (isset($productosSolicitados) && count($productosSolicitados) > 0) {
                                        foreach ($productosSolicitados as $key => $value) {
                                            echo '
                                                <tr>
                                                    <td>' . $value['ClaveSAE'] . '</td>
                                                    <td>' . $value['DescripcionSAE'] . '</td>                                                    
                                                    <td>' . $value['Cantidad'] . '</td>                                                    
                                                    </tr>';
                                        }
                                    }
                                    ?>
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
                                    if (isset($productosDisponibles) && count($productosDisponibles) > 0) {
                                        foreach ($productosDisponibles as $key => $value) {
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
                            <textarea id="txtObservacionesSolicitud" class="form-control" placeholder="Ingresa las observaciones de la Solicitud de Compra" rows="6"><?php echo $solicitud['Descripcion']; ?></textarea>
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