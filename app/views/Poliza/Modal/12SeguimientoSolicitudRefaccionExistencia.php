<?php

if (!empty($invetarioAlmacen)) {
    $botonCotizar = 'hidden';
    if ($idEstatus === '41') {
        $botonTeminarSeleccion = 'hidden';
        $botonTeminarSeleccionLaboratorio = '';
    } else {
        $botonTeminarSeleccion = '';
        $botonTeminarSeleccionLaboratorio = 'hidden';
    }
} else {
    if ($idEstatus === '41') {
        $botonCotizar = 'hidden';
    } else {
        $botonCotizar = '';
    }
    $botonTeminarSeleccion = 'hidden';
    $botonTeminarSeleccionLaboratorio = 'hidden';
}

$mensajeCotizacion = 'hidden';

if ($cotizacionAnterior[0]['Total'] <= 0) {
    $botonCotizacion = '';
}else{
    $botonCotizacion = 'hidden';
}
?>
<div id="panelValidacionExistencia" class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">2) Seguimiento de Solicitud de Producto</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Seguimiento de Solicitud de Producto</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-4 <?php echo $botonCotizar; ?>">
                <div class="form-group text-right">
                    <a href="javascript:;" class="btn btn-sm btn-info f-s-13" id="solicitarLaboratorio"><i class="fa fa-wrench"></i> Solicitar a Laboratorio</a>
                    <a href="javascript:;" class="btn btn-sm btn-success f-s-13 <?php echo $botonCotizacion; ?>" id="solicitarCotizacion"><i class="fa fa-usd"></i> Solicitar Cotización</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="lista-solicitud-producto" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Producto</th>
                                <th class="all">Serie</th>
                                <th class="all">Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($invetarioAlmacen)) {
                                foreach ($invetarioAlmacen as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['Producto'] . '</td>';
                                    echo '<td>' . $value['Serie'] . '</td>';
                                    echo '<td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input editor-active" data-id="' . $value['Id'] . '">
                                            </div>
                                        </td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="errorSolicitudProducto"></div>
            </div>
        </div>
        <div class="row <?php echo $botonTeminarSeleccion; ?>">
            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                <a id="btnTerminarSeleccionLocal" class="btn btn-primary m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guargar Producto(s) - Local</a>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                <a id="btnTerminarSeleccionForaneo" class="btn btn-success m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guardar Producto(s) - Foráneo</a>
            </div>
        </div>
        <div class="row <?php echo $botonTeminarSeleccionLaboratorio; ?>">
            <div class="text-center">
                <a id="btnTerminarSeleccionLaboratorio" class="btn btn-primary m-t-10 m-r-10 f-w-600 f-s-13"><i class="fa fa-save"></i> Guargar Producto(s)</a>
            </div>
        </div>
        <div class="alert alert-warning fade in m-b-15 m-t-20 <?php echo $mensajeCotizacion; ?>">                            
            <strong>Ya se ha generado una cotización para el producto solicitado. El estatus de la solicitud es: Pendiente<br>
                Registre el producto en el inventario del almacén de consignación tan pronto como lo reciba</strong>
        </div>
    </div>
</div>