<div style="page-break-after:always;">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3>Información General del Servicio Uber</h3>
            <div class="underline"></div>
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Número de Solicitud</h5>
                <h4><?php echo $solicitud['solicitud']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Solicitante</h5>
                <h4><?php echo $solicitud['solicitante']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Fecha de Solicitud</h5>
                <h4><?php echo $solicitud['fechaSolicitud']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Estatus de Solicitud</h5>
                <h4><?php echo $solicitud['estatusSolicitud']; ?></h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h5 class="f-w-700">Descripción de la Solicitud</h5>
                <h4><?php echo $solicitud['descripcionSolicitud']; ?></h4>            
            </div>       
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Número de Ticket</h5>
                <h4><?php echo $solicitud['ticket']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Tipo de Servicio</h5>
                <h4><?php echo $solicitud['tipoServicio']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Fecha de Servicio</h5>
                <h4><?php echo $solicitud['fechaServicio']; ?></h4>
            </div>                    
            <div class="col-md-3 col-xs-12">
                <h5 class="f-w-700">Estatus de Servicio</h5>
                <h4><?php echo $solicitud['estatusServicio']; ?></h4>
            </div>                          
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h5 class="f-w-700">Descripción del Servicio</h5>
                <h4><?php echo $solicitud['descripcionServicio']; ?></h4>        
            </div>                   
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-6 col-xs-12">
                <h5 class="f-w-700">Tiempo de la Solicitud</h5>
                <h4><?php echo $solicitud['tiempoSolicitud']; ?> hrs</h4>
            </div>                   
            <div class="col-md-6 col-xs-12">
                <h5 class="f-w-700">Tiempo del Servicio</h5>
                <h4><?php echo $solicitud['tiempoServicio']; ?> hrs</h4>
            </div>                   
        </div>    
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3>Documentación del servicio.</h3>        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Ticket de Referencia</h5>
                <h4><?php echo $generales['ticket']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Número de Personas</h5>
                <h4><?php echo $generales['personas']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Fecha del Servicio</h5>
                <h4><?php echo $generales['fecha']; ?></h4>
            </div>                         
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h5 class="f-w-700">Dirección de Origen</h5>
                <h4><?php echo $generales['origen']; ?></h4>
            </div>  
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h5 class="f-w-700">Dirección Destino</h5>
                <h4><?php echo $generales['destino']; ?></h4>
            </div>   
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <div class="divTablas">
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h5 class="f-w-700">Motivo / Proyecto</h5>
                <h4><?php echo $generales['motivo']; ?></h4>
            </div> 
        </div>
    </div>
</div>

<!-- Comienza informacion de la conversacion (Notas) -->
<?php
if (count($notasPdf) > 0) {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12">                            
                <fieldset>
                    <legend class="pull-left width-full f-s-17">Conversación del Servicio.</legend>
                </fieldset>  
            </div>
        </div>

        <?php
        foreach ($notasPdf as $key => $value) {
            $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
            ?>
            <div class="row m-t-25">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="f-w-600 pull-left"><?php echo $value['Nombre']; ?></p>            
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="f-w-600 pull-right"><?php echo $fecha; ?></p>            
                </div>
            </div>
            <?php
            if ($value['Nota'] != '') {
                ?>
                <pre><?php echo $value['Nota']; ?></pre>
                <?php
            }
            if ($value['Archivos'] != '') {
                $archivos = explode(",", $value['Archivos']);
                foreach ($archivos as $k => $v) {
                    ?>
                    <div style="display:inline-block; max-width: 180px; max-height: 250px; font-size:10px;" >
                        <a href="<?php echo $v; ?>" target="_blank" >
                            <img class="img-thumbnail img-responsive" src="<?php echo $v; ?>" />
                        </a>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}
?>
<!-- Termina informacion de la conversacion (Notas) -->