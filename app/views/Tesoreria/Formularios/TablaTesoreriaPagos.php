<?php
if (!empty($facturasTesoreriaPago)) {
    $fechaInicial = $facturasTesoreriaPago[0]['fechaInicial'];
    $fechaFinal = $facturasTesoreriaPago[0]['fechaFinal'];
} else {
    $fechaInicial = $facturasTesoreriaPagoJueves[0]['fechaInicial'];
    $fechaFinal = $facturasTesoreriaPagoJueves[0]['fechaFinal'];
}
?>
<div class="row m-t-20">
    <div class="col-md-7">
        <h3 class="m-t-10">Facturas Pendientes de Pago</h3>
    </div>
    <div class="col-md-5">
        <div class="alert alert-info">
            <i class=""fa fa-info-circle fa-fw></i>
            <div id="divFecha" data-fecha-inicial='<?php echo $fechaInicial; ?>' data-fecha-final='<?php echo $fechaFinal; ?>'>
                Del día <strong><?php echo $fechaInicial; ?></strong> al día <strong><?php echo $fechaFinal; ?></strong>.</div>
        </div>
    </div>
</div>
<!--Empezando Separador-->
<div class="row">
    <div class="col-md-12">
        <div class="underline m-b-15 m-t-15"></div>
    </div>
</div>

<div class="row m-b-15">
    <div class="col-md-12  text-right">
        <div class="btn-group">
            <button id="btnSemanaAnterior" class="btn btn-white"><i class="fa fa-chevron-left"></i> Semana anterior</button>
            <button id="btnSemanaActual" class="btn btn-white active">Esta semana</button>
            <button id="btnSemanaSeguiente" class="btn btn-white">Semana siguiente <i class="fa fa-chevron-right"></i></button>
        </div>
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
                <th class="all">Estatus</th>
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
                    echo '<td>' . $value['Estatus'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>                                       
        </tbody>
    </table>
</div>

