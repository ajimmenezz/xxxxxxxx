<!--
 * Description: Formulario para generar el reporte Impericia PDF Servicio Correctivo
 *
 * @author: Alberto Barcenas
 *
-->
<!-- Comienza Informacion de la Solicitud -->
<div style="page-break-after:always;">
    <!-- Comienza informacion del Servicio -->
    <div class="row">
        <div class="col-md-6">
            <h4 class="f-w-700">Detalles del Servicio</h4>
        </div>
        <div class="col-md-6 text-right">
            <h4 class="f-w-700">Sucursal: <?php echo $solicitud['sucursal']; ?></h4>
        </div>
        <div class="col-md-12 underline m-t-0 m-b-10"></div>
    </div>        
    <div class="row">
        <div class="col-md-6">
            <h6 class="f-w-700">Folio SD</h6>
            <h5><?php echo $solicitud['folio']; ?></h5>
        </div>                                   
    </div>
    <div class="row m-t-10"> 
        <div class="col-md-6 col-xs-12">
            <h6 class="f-w-700">Area y Punto</h6>
            <h5><?php echo $generales['NombreArea'], $generales['Punto']; ?></h5>
        </div>  
        <div class="col-md-6 col-xs-12">
            <h6 class="f-w-700">Equipo</h6>
            <h5><?php echo $generales['Equipo']; ?></h5>
        </div>  
    </div>
    <div class="row m-t-10">
        <div class="col-md-6 col-xs-12">
            <h6 class="f-w-700">Serie</h6>
            <h5><?php echo $generales['Serie']; ?></h5>
        </div>  
        <div class="col-md-6 col-xs-12">
            <h6 class="f-w-700">Número de Terminal</h6>
            <h5><?php echo $generales['Terminal']; ?></h5>
        </div>  
    </div>
    <div class="row">
        <div class="col-md-6">
            <h6 class="f-w-700">Fecha Creación</h6>
            <h5><?php echo $solicitud['fechaServicio']; ?></h5>
        </div>                                            
        <div class="col-md-6">
            <h6 class="f-w-700">Fecha Inicio</h6>
            <h5><?php echo $solicitud['fechaInicio']; ?></h5>
        </div>                                                              
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Comienza Firma -->
    <div class="row m-t-40">
        <div class = "col-md-4 col-md-offset-4 text-center">
            <h5 class = 'f-w-700 text-center'>Firma del Reporte</h5>
            <?php echo '<img style="max-height: 90px" src="' . $correctivosDiagnostico['Firma'] . '" >'; ?>  
            <h6 class='f-w-700 text-center'><?php echo $correctivosDiagnostico['Gerente'] ?></h6>               
            <h6 class='f-w-700 text-center'><?php echo $correctivosDiagnostico['FechaFirma'] ?></h6>               
        </div>
    </div>
    <!-- Termina Firma -->

</div>
<!-- Termina informacion del Servicio -->

<!-- Comienza Informacion de la Impericia -->
<div style="page-break-after:always;">
    <div class = "row">                   
        <div class="col-md-6">
            <h6 class="f-w-700">Tipo de Falla</h6>
            <h5><?php echo $correctivosDiagnostico['NombreTipoFalla']; ?></h5>
        </div>                                     
        <div class="col-md-6">
            <h6 class="f-w-700">Falla</h6>
            <h5><?php echo $correctivosDiagnostico['NombreFalla']; ?></h5>
        </div>                                     
    </div>
    <div class="row m-t-10">
        <div class="col-md-12 col-xs-12">
            <h6 class="f-w-700">Observaciones</h6>
            <h5><?php echo $correctivosDiagnostico['Observaciones']; ?></h5>
        </div>  
    </div>
    <?php
    $arrayEvidencias = explode(",", $correctivosDiagnostico['Evidencias']);
    ?>
    <div class="row">                                  
        <div class="col-md-12">
            <h6 class="f-w-700">Evidencias</h6>
        </div>
    </div>
    <div class="row">
        <?php
        foreach ($arrayEvidencias as $key => $value) {
            ?>
            <div style="display:inline-block; max-width: 200px;" >                                
                <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">                                  
            </div>
        <?php } ?>
    </div>
</div>
<!-- Finaliza Informacion de la Impericia -->
