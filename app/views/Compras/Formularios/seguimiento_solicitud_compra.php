<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h1 class="page-header">Solicitud de Compra</h1>
    </div>
    <div class="col-md-6 col-xs-6 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>
    </div>
</div>
<div id="panelFormularioSolicitudCompra" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading"></div>
    <!--Finalizando cabecera del panel-->
    <!--Empezando cuerpo del panel-->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12">
                        <h3 class="m-t-10">Detalles de solicitud de compra</h3>
                    </div>
                    <?php
                    if (in_array($solicitud['IdEstatus'], [7, '7'])) {
                        ?>
                        <div class="col-md-12">
                            <div class="alert alert-success fade in p-5">
                                <strong>
                                    <?php echo $solicitud['DescEstatus']; ?>
                                </strong>
                            </div>
                        </div>
                    <?php } ?>
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
                    <label class="f-w-600 f-s-13">Cliente:</label>
                    <label class="form-control"><?php echo $solicitud['Cliente']; ?></label>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Proyecto:</label>
                    <label class="form-control"><?php echo $solicitud['Proyecto']; ?></label>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="f-w-600 f-s-13">Sucursal:</label>
                    <label class="form-control"><?php echo $solicitud['Sucursal']; ?></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="active"><a href="#nav-pills-productos-solicitados" data-toggle="tab">Productos solicitados</a></li>
                    <li><a href="#nav-pills-observaciones" data-toggle="tab">Observaciones de Solicitud</a></li>
                    <li><a href="#nav-pills-archivos-previos" data-toggle="tab">Archivos Adjuntos</a></li>
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
                    <div class="tab-pane fade" id="nav-pills-archivos-previos">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="f-w-600 f-s-13">Archivos Adjuntos:</label>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
                                            <?php
                                            $archivos = explode(",", $solicitud['Archivos']);
                                            foreach ($archivos as $key => $value) {
                                                echo '<div class="thumbnail-pic m-5 p-5">';
                                                $extencion = pathinfo($value, PATHINFO_EXTENSION);
                                                switch ($extencion) {
                                                    case 'png':
                                                    case 'jpeg':
                                                    case 'jpg':
                                                    case 'gif':
                                                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                        break;
                                                    case 'xls':
                                                    case 'xlsx':
                                                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                        break;
                                                    case 'doc':
                                                    case 'docx':
                                                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                        break;
                                                    case 'pdf':
                                                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                        break;
                                                    default:
                                                        echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                        break;
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-pills-observaciones">
                        <div class="form-group">
                            <label>Observaciones de Solicitud *</label>
                            <label class="form-control"><?php echo $solicitud['Descripcion']; ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="errorFormulario" class="col-md-12 text-center">
            </div>
        </div>
        <?php

        if ($solicitud['IdEstatus'] == 9 && $autorizar === true) {
            ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a id="btnAutorizarSolicitud" class="btn btn-success p-l-25 p-r-25 p-t-10 p-b-10 f-w-600 f-s-15 m-r-10">Autorizar Compra</a>
                    <a id="btnRechazarSolicitud" class="btn btn-danger p-l-25 p-r-25 p-t-10 p-b-10 f-w-600 f-s-15 m-l-10">Rechazar Solicitud</a>
                </div>
            </div>
        <?php
    }
    ?>
    </div>
    <!--Finalizando cuerpo del panel-->
</div>