<?php
setlocale(LC_TIME, 'es_ES.UTF-8');
date_default_timezone_set('America/Mexico_City');
?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <h3>Información General del Ticket Correctivo</h3>
        <h5 class="f-w-600">Documento Creado el <?php echo strftime('%A %e de %B, %G ', strtotime($generales['fecha'])) . strftime("%I:%M %p", strtotime($generales['fecha'])); ?></h5>
        <div class="underline"></div>
    </div>
</div>
<div class="divTablas">
    <div class="row m-t-10">
        <div class="col-md-3 col-xs-12">
            <h5 class="f-w-700">Número de Ticket</h5>
            <h4><?php echo $generales['ticket']; ?></h4>
        </div>                    
        <div class="col-md-3 col-xs-12">
            <h5 class="f-w-700">Folio (SD)</h5>
            <h4><?php echo $generales['folio']; ?></h4>
        </div>                    
        <div class="col-md-3 col-xs-12">
            <h5 class="f-w-700">Tipo de Ticket</h5>
            <h4><?php echo $generales['tipo']; ?></h4>
        </div>                    
        <div class="col-md-3 col-xs-12">
            <h5 class="f-w-700">Estatus del Ticket</h5>
            <h4><?php echo $generales['estatus']; ?></h4>
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
            <h5 class="f-w-700">Sucursal</h5>
            <h4><?php echo $generales['sucursal']; ?></h4>
        </div>                    
        <div class="col-md-6 col-xs-12">
            <h5 class="f-w-700">Ingeniero Asignado</h5>
            <h4><?php echo $generales['ingeniero']; ?></h4>
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
            <h5 class="f-w-700">Observaciones del Levantamiento</h5>
            <h4><?php echo $generales['observaciones']; ?></h4>            
        </div>       
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">                        
        <div class="underline"></div>                    
    </div>
</div>
<?php
foreach ($detalles as $key => $value) {
    ?>
    <div style="page-break-before: always;"></div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3>Documentación del servicio <?php echo $value['Servicio'] ?>.</h3>        
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
                <h5 class="f-w-700">Estatus del Servicio</h5>
                <h4><?php echo $value['Estatus']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Substatus del Servicio</h5>
                <h4><?php echo $value['Substatus']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Fecha del Estatus</h5>
                <h4><?php echo $value['FechaEstatus']; ?></h4>
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
                <h5 class="f-w-700">Observaciones del Estatus</h5>
                <h4><?php echo $value['DescripcionEstatus']; ?></h4>            
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
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Área de Atención</h5>
                <h4><?php echo $value['Area']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Equipo</h5>
                <h4><?php echo $value['Equipo']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Serie</h5>
                <h4><?php echo $value['Serie']; ?></h4>
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
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Tipo de Falla</h5>
                <h4><?php echo $value['TipoFalla']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Clasificación de Falla</h5>
                <h4><?php echo $value['Clasificacion']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Equipo o Componente</h5>
                <h4><?php echo $value['Componente']; ?></h4>
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
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Falla</h5>
                <h4><?php echo $value['Falla']; ?></h4>
            </div>                    
            <div class="col-md-4 col-xs-12">
                <h5 class="f-w-700">Solución</h5>
                <h4><?php echo $value['Solucion']; ?></h4>
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
                <h5 class="f-w-700">Equipo Uilizado</h5>
                <h4><?php
                    $equipo = str_replace("EQUIPO_COMPLETO (", "<br />(", $value['Equipo_Utilizado']);
                    $equipo = str_replace("CONSUMIBLE (", "<br />(", $equipo);
                    $equipo = str_replace("REFACCION (", "<br />(", $equipo);
                    echo $equipo;
                    ?>
                </h4>            
            </div>       
        </div>
    </div>
    <?php
}
?>