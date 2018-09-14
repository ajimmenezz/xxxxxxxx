<div class="divTablas">
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-6">
                <h4 class="f-w-700">Información General del Servicio</h4>
            </div>
            <div class="col-md-6 text-right">
                <?php (empty($solicitud['sucursal'])) ? $sucursal = '' : $sucursal = 'Sucursal: ' . $solicitud['sucursal']; ?>
                <h4 class="f-w-700"> <?php echo $sucursal; ?></h4>
            </div>
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>  
        <div class="row m-t-10">
            <?php if (!empty($solicitud['folio'])) { ?>
                <div class="col-md-3 col-xs-3">
                    <h6 class="f-w-700">Folio</h6>
                    <h5><?php echo $solicitud['folio']; ?></h5>            
                </div>       
            <?php } ?>  
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Número de Ticket</h6>
                <h5><?php echo $solicitud['ticket']; ?></h5>
            </div>   
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Estatus de Ticket</h6>
                <h5><?php echo $solicitud['estatusSolicitud']; ?></h5>
            </div>
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Núm. Servicio</h6>
                <h5><?php echo $solicitud['servicio']; ?></h5>
            </div>   
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">                        
                <div class="underline"></div>                    
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Tipo de Servicio</h6>
                <h5><?php echo $solicitud['tipoServicio']; ?></h5>
            </div>                    
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Sucursal</h6>
                <h5><?php echo $solicitud['sucursal'] ?></h5>
            </div>                    
            <div class="col-md-3 col-xs-3">
                <h6 class="f-w-700">Personal que Atiende</h6>
                <h5><?php echo $solicitud['atiendeServicio']; ?></h5>
            </div>     
        </div>
        <div class="row m-t-0">
            <div class="col-md-12 col-xs-12">
                <h4>Documentación del servicio.</h4>        
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">                        
                <div class="underline"></div>                    
            </div>
        </div>
        <div class="row m-t-10">               
            <div class="col-md-3 col-xs-12">
                <h6 class="f-w-700">Estatus de Servicio</h6>
                <h5><?php echo $solicitud['estatusServicio']; ?></h5>
            </div>                          
            <div class="col-md-3 col-xs-12">
                <h6 class="f-w-700">Fecha Creación</h6>
                <h5><?php echo $solicitud['fechaServicio']; ?></h5>
            </div>                          
            <div class="col-md-3 col-xs-12">
                <h6 class="f-w-700">Fecha Inicio</h6>
                <h5><?php echo $solicitud['fechaInicio']; ?></h5>
            </div>                          
            <div class="col-md-3 col-xs-12">
                <h6 class="f-w-700">Fecha Conclusión</h6>
                <h5><?php echo $solicitud['fechaConclusion']; ?></h5>
            </div>                          
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Descripción del Servicio</h6>
                <h5><?php echo $solicitud['descripcionServicio']; ?></h5>        
            </div>                   
        </div>
        <?php if ($solicitud['firma'] !== 'Sin Información') { ?>
            <div class="row">
                <div class="col-md-4 col-md-offset-4 text-center"> 
                    <h5 class='f-w-700 text-center'>Firma Cierre</h5>
                    <?php echo '<img style="max-height: 120px" src="/storage/Archivos/imagenesFirmas/Firma_' . $solicitud['ticket'] . '_' . $solicitud['servicio'] . '.png" >'; ?>  
                    <h6 class='f-w-700 text-center'><?php echo $solicitud['nombreFirma'] ?></h6>               
                    <h6 class='f-w-700 text-center'><?php echo $solicitud['fechaFirma'] ?></h6>               
                </div>
            </div>
            <?php
        }
        if (in_array($tipoServicio, ['6', '7', 6, 7])) {
            ?>
        </div>
        <div style="page-break-after:always;">
            <div class="row m-t-10">
                <div class="col-md-12 col-xs-12">
                    <h6 class="f-w-700">Resolución del Servicio</h6>
                    <h5><?php echo $generales['descripcion']; ?></h5>
                </div>  
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Resolución del Servicio</h6>
                <h5><?php echo $generales['descripcion']; ?></h5>
            </div>  
        </div>
    </div>
    <?php
}
?>

<?php
if (!empty($generales['archivos']['htmlArchivos'])) {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12">                            
                <fieldset>
                    <legend class="pull-left width-full f-s-17">Archivos del servicio.</legend>
                </fieldset>  
            </div>
        </div>
        <?php
        echo $generales['archivos']['htmlArchivos'];
        ?>
    </div>
    <?php
}

if (count($avanceServicio) > 0) {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12">                            
                <fieldset>
                    <legend class="pull-left width-full f-s-17">Historial.</legend>
                </fieldset>  
            </div>
        </div>

        <?php
        foreach ($avanceServicio as $key => $value) {
            $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
            ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="f-w-600 pull-left"><?php echo $value['TipoAvance']; ?></p>            
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="f-w-600 pull-left"><?php echo $value['Usuario']; ?></p>            
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="f-w-600 pull-right"><?php echo $fecha; ?></p>            
                </div>
            </div>
            <?php
            if ($value['Descripcion'] != '') {
                ?>
                <pre><?php echo $value['Descripcion']; ?></pre>
                <?php
            }
            if ($value['Archivos'] != '') {
                $archivos = explode(",", $value['Archivos']);
                foreach ($archivos as $k => $v) {
                    ?>
                    <div style="display:inline-block; width: 140px; height: 140px; font-size:10px;" >
                        <a href="<?php echo $v; ?>" target="_blank" >
                            <img class="img-thumbnail img-responsive" style="width:100% !important; height: 100% !important;" src="<?php echo $v; ?>" />
                        </a>
                    </div>
                    <?php
                }
            }
            $contadorTabla = $value[0]['tablaEquipos'];
            if (count($contadorTabla) > 0) {
                ?>
                <div class="timeline-footer">
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="m-t-10">Lista de Equipos o Materiales</h4>
                        </div>
                    </div>

                    <!--Empezando Separador-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                    </div>
                    <!--Finalizando Separador-->

                    <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" height="3px">
                        <thead>
                            <tr>
                                <th class="never">Tipo Item</th>
                                <th class="all">Descripción</th>
                                <th class="all">Serie</th>
                                <th class="all">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($contadorTabla as $key => $valor) {
                                switch ($valor['IdItem']) {
                                    case '1':
                                        $tipoItem = 'Equipo';
                                        break;
                                    case '2':
                                        $tipoItem = 'Material';
                                        break;
                                    case '3':
                                        $tipoItem = 'Refacción';
                                }
                                echo '<tr>';
                                echo '<td>' . $tipoItem . '</td>';
                                echo '<td>' . $valor['EquipoMaterial'] . '</td>';
                                echo '<td>' . $valor['Serie'] . '</td>';
                                echo '<td>' . $valor['Cantidad'] . '</td>';
                                echo '</tr>';
                            }
                            ?>                                        
                        </tbody>
                    </table>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php
}
if ($sumaTipoDiagnostico !== FALSE) {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12">                            
                <fieldset>
                    <legend class="pull-left width-full f-s-17">Tipos de Fallas.</legend>
                </fieldset>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" height="3px">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Tipo Falla</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($sumaTipoDiagnostico as $key => $valor) {
                            (empty($valor['TipoDiagnostico'])) ? $tipoDiagnostico = 'Sin Tipo de Falla' : $tipoDiagnostico = $valor['TipoDiagnostico'];
                            echo '<tr>';
                            echo '<td>' . $valor['Tipo'] . '</td>';
                            echo '<td>' . $valor['EquipoMaterial'] . '</td>';
                            echo '<td>' . $valor['Cantidad'] . '</td>';
                            echo '<td>' . $tipoDiagnostico . '</td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        ?>
    </div>
    <?php
}
?>
</div>