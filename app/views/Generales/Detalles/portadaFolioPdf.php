<div class="divTablas">
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Información General del Folio</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-2 col-xs-2">
                <h6 class="f-w-700">Folio</h6>
                <h5><?php echo $solicitud['Folio']; ?></h5>            
            </div>       
            <div class="col-md-2 col-xs-2">
                <h6 class="f-w-700">Vuelta</h6>
                <h5><?php echo $numeroVueltas['Vueltas']; ?></h5>
            </div>   
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Solicita</h6>
                <h5><?php echo $solicitud['Solicitante']; ?></h5>
            </div>   
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Fecha de Vuelta</h6>
                <h5><?php echo $datosFacturacionOutsourcig['Fecha']; ?></h5>
            </div>   
        </div>

        <div class="row">
            <div class="col-md-6 text-center"> 
                <h5 class='f-w-700 text-center'>Firma Gerente</h5>
                <?php echo '<img style="max-height: 90px" src="' . $datosFacturacionOutsourcig['FirmaGerente'] . '" >'; ?>  
                <h6 class='f-w-700 text-center'><?php echo $datosFacturacionOutsourcig['Gerente'] ?></h6>               
                <h6 class='f-w-700 text-center'><?php echo $datosFacturacionOutsourcig['Fecha'] ?></h6>               
            </div>
            <div class="col-md-6 text-center"> 
                <h5 class='f-w-700 text-center'>Firma Técnico</h5>
                <?php echo '<img style="max-height: 90px" src="' . $datosFacturacionOutsourcig['FirmaUsuario'] . '" >'; ?>  
                <h6 class='f-w-700 text-center'><?php echo $datosFacturacionOutsourcig['NombreTecnico'] ?></h6>               
                <h6 class='f-w-700 text-center'><?php echo $datosFacturacionOutsourcig['Fecha'] ?></h6>               
            </div>
        </div>

        <?php if ($detallesSD !== '') { ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h6 class="f-w-700">Descripción</h6>
                    <h5><?php echo $detallesSD; ?></h5>        
                </div>                   
            </div>
        <?php } ?>

        <div class="row m-t-0">
            <div class="col-md-12 col-xs-12">
                <h4>Servicios ligados al Folio.</h4>        
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">                        
                <div class="underline"></div>                    
            </div>
        </div>
        <br>
        <?php
        echo $tablaServicios;
        ?>
    </div>

</div>