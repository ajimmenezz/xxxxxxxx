<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Servicio:</div>
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Id'] != '' ? $datos[0]['Id'] : 'S/Inf'); ?></div>
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Ticket:</div>
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Ticket'] != '' ? $datos[0]['Ticket'] : 'S/Inf'); ?></div>
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Folio:</div>
    <div class="col-md-2 col-sm-2 col-xs-3 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Folio'] != '' ? $datos[0]['Folio'] : 'S/Inf'); ?></div>    
</div>
<div class="row m-t-10">
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Tipo de Servicio:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Tipo'] != '' ? $datos[0]['Tipo'] : 'S/Inf'); ?></div>
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Estaus:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Estatus'] != '' ? $datos[0]['Estatus'] : 'S/Inf'); ?></div>    
</div>
<div class="row m-t-10">
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Sucursal:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Sucursal'] != '' ? $datos[0]['Sucursal'] : 'S/Inf'); ?></div>
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Atiende:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Atiende'] != '' ? $datos[0]['Atiende'] : 'S/Inf'); ?></div>    
</div>
<div class="row m-t-10">
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Fecha Creación:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['FechaCreacion'] != '' ? $datos[0]['FechaCreacion'] : 'S/Inf'); ?></div>
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Fecha Inicio:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['FechaInicio'] != '' ? $datos[0]['FechaInicio'] : 'S/Inf'); ?></div>    
</div>
<div class="row m-t-10">
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Fecha Conclusión:</div>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['FechaConclusion'] != '' ? $datos[0]['FechaConclusion'] : 'S/Inf'); ?></div>    
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Fecha Tentativa:</div>
    <?php
    $tentativa = ($datos[0]['FechaTentativa'] != '' ? $datos[0]['FechaTentativa'] : 'S/Inf');
    if (in_array($datos[0]['IdEstatus'], [1, 2, 3, 10, '1', '2', '3', '10'])) {
        $tentativa = '
                <div class="form-group">
                    <div class="input-group date" id="tentativa">
                        <input type="text" id="txtTentativa" class="form-control" value="' . $datos[0]['FechaTentativa'] . '"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>';
    }
    ?>
    <div class="col-md-4 col-sm-4 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo $tentativa; ?></div>    
</div>
<div class="row m-t-10">
    <div class="col-md-2 col-sm-2 col-xs-4 f-w-700 bg-grey-darker text-white text-right p-5 p-r-10 f-s-14">Descripción:</div>
    <div class="col-md-10 col-sm-10 col-xs-8 f-w-600 bg-grey text-inverse text-center p-5 f-s-14"><?php echo ($datos[0]['Descripcion'] != '' ? $datos[0]['Descripcion'] : 'S/Inf'); ?></div>    
</div>






<?php
//echo "<pre>", var_dump($datos), "</pre>";
?>