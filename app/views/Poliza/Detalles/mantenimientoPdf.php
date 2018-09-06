<!--
 * Description: Formulario para generar PDF Servicio Mantenimiento
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
        <div class="col-md-4">
            <h6 class="f-w-700">Folio SD</h6>
            <h5><?php echo $solicitud['folio']; ?></h5>
        </div>                    
        <div class="col-md-4">
            <h6 class="f-w-700">Ticket</h6>
            <h5><?php echo $solicitud['ticket']; ?></h5>
        </div>                    
        <div class="col-md-4">
            <h6 class="f-w-700">Id del Servicio</h6>
            <h5><?php echo $solicitud['servicio']; ?></h5>
        </div>                    
    </div>
    <div class="row">
        <div class="col-md-4">
            <h6 class="f-w-700">Tipo de Servicio</h6>
            <h5><?php echo $solicitud['tipoServicio']; ?></h5>
        </div>                    
        <div class="col-md-4">
            <h6 class="f-w-700">Sucursal</h6>
            <h5><?php echo $solicitud['sucursal']; ?></h5>
        </div>                    
        <div class="col-md-4">
            <h6 class="f-w-700">Atiende</h6>
            <h5><?php echo $solicitud['atiendeServicio']; ?></h5>
        </div>                    
    </div>
    <div class="row">
        <div class="col-md-4">
            <h6 class="f-w-700">Fecha Creación</h6>
            <h5><?php echo $solicitud['fechaServicio']; ?></h5>
        </div>                                            
        <div class="col-md-4">
            <h6 class="f-w-700">Fecha Inicio</h6>
            <h5><?php echo $solicitud['fechaInicio']; ?></h5>
        </div>                                            
        <div class="col-md-4">
            <h6 class="f-w-700">Fecha Cierre</h6>
            <h5><?php echo $solicitud['fechaConclusion']; ?></h5>
        </div>                    
    </div>

    <!-- Comienza Firma -->
    <?php if ($solicitud['firma'] !== 'Sin Información') { ?>       
        <div class="row">
            <div class="col-md-12 underline m-t-15 m-b-10"></div>
            <div class="col-md-4 col-md-offset-4 text-center"> 
                <h5 class='f-w-700 text-center'>Firma Cierre</h5>
                <?php echo '<img style="max-height: 90px" src="' . $solicitud['firma'] . '" >'; ?>  
                <h6 class='f-w-700 text-center'><?php echo $solicitud['nombreFirma'] ?></h6>               
                <h6 class='f-w-700 text-center'><?php echo $solicitud['fechaFirma'] ?></h6>               
            </div>
        </div>
    <?php } elseif (!empty($documentacionFirmada)) {
        ?>
        <div class="row">
            <div class="col-md-12 underline m-t-15 m-b-10"></div>
            <div class="col-md-4 col-md-offset-4 text-center"> 
                <h5 class='f-w-700 text-center'>Firma Avance</h5>
                <?php echo '<img style="max-height: 90px" src="' . $documentacionFirmada[0]['Firma'] . '" >'; ?>  
                <h6 class='f-w-700 text-center'><?php echo $documentacionFirmada[0]['Recibe'] ?></h6>               
                <h6 class='f-w-700 text-center'><?php echo $documentacionFirmada[0]['Fecha'] ?></h6>               
            </div>
        </div>
        <?php
    }
    ?>
    <!-- Termina Firma -->

</div>
<!-- Termina informacion del Servicio -->

<!-- Comienza informacion del Antes y Despues -->
<?php
if ($generalesMantenimiento['antesDespues'] !== 'Sin Información') {
    foreach ($generalesMantenimiento['antesDespues'] as $key => $datos) {
        $arrayAntes = explode(",", $datos['EvidenciasAntes']);
        $arrayDespues = explode(",", $datos['EvidenciasDespues']);
        ?>
        <div style="page-break-after:always;">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h1 class="f-w-700">Información Antes y Después</h1>
                </div>                   
                <div class="col-md-12 underline m-t-0 m-b-10"></div>
            </div>
            <div class="row pre">                                  
                <div class="col-md-12">                        
                    <h3><?php echo $datos['Area'] . ' ' . $datos['Punto']; ?></h3>
                </div>                                                                                                                               
            </div>
            <div class="row">                                  
                <div class="col-md-6">
                    <h6 class="f-w-700">Observaciones del Antes</h6>
                    <h5><?php echo $datos['ObservacionesAntes']; ?></h5>
                </div>  
                <div class="col-md-6">
                    <h6 class="f-w-700">Observaciones del Después</h6>
                    <h5><?php echo $datos['ObservacionesDespues']; ?></h5>
                </div>                                                                                                                               
            </div>
            <div class="row">                                  
                <div class="col-md-12">
                    <h6 class="f-w-700">Evidencias del Antes y Después</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    foreach ($arrayAntes as $key => $value) {
                        ?>
                        <div style="display:inline-block; max-width: 200px;" >                                
                            <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">  
                            <h6 class="f-w-700">Antes</h6>
                        </div>
                        <?php
                    }
                    foreach ($arrayDespues as $key => $value) {
                        ?>
                        <div style="display:inline-block; max-width: 200px;" >                                
                            <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">  
                            <h6 class="f-w-700">Después</h6>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div> 
        <?php
    }
}
?>
<!-- Termina informacion del Antes y Despues -->

<!-- Comienza informacion Problemas por Equipo -->
<?php
if ($generalesMantenimiento['problemasEquipo'] !== 'Sin Información') {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h1 class="f-w-700">Información de Problemas por Equipo</h1>
            </div>                   
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>
        <?php
        foreach ($generalesMantenimiento['problemasEquipo'] as $key => $datos) {
            $arrayEvidencias = explode(",", $datos['Evidencias']);
            ?>
            <div class="row pre">                                  
                <div class="col-md-12">                        
                    <h3><?php echo $datos['Area'] . ' ' . $datos['Punto']; ?></h3>
                    <h4><?php echo $datos['Equipo']; ?></h4>
                </div>                                                                                                                               
            </div>
            <div class="row">                                  
                <div class="col-md-12">
                    <h6 class="f-w-700">Observaciones</h6>
                    <h5><?php echo $datos['Observaciones']; ?></h5>
                </div>  
            </div>
            <div class="row">                                  
                <div class="col-md-12">
                    <h6 class="f-w-700">Evidencias</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    foreach ($arrayEvidencias as $key => $value) {
                        ?>
                        <div style="display:inline-block; max-width: 200px;" >                                
                            <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">                                  
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<!-- Termina informacion Problemas por Equipo -->

<!-- Comienza informacion Equipo Faltante -->
<?php
if ($generalesMantenimiento['equiposFaltante'] !== 'Sin Información') {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h1 class="f-w-700">Equipo Faltante</h1>
            </div>                   
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>
        <br>
        <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
            <thead>
                <tr>
                    <th><h4 class="f-w-700">Área</h4></th>
                    <th><h4 class="f-w-700">Punto</h4></th>
                    <th><h4 class="f-w-700">Tipo</h4></th>
                    <th><h4 class="f-w-700">Equipo</h4></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($generalesMantenimiento['equiposFaltante'] as $key => $value) {
                    echo '<tr>';
                    echo '<td><h6 class="f-w-700">' . $value['Area'] . '</h6></td>';
                    echo '<td><h6 class="f-w-700">' . $value['Punto'] . '</h6></td>';
                    echo '<td><h6 class="f-w-700">' . $value['NombreItem'] . '</h6></td>';
                    echo '<td><h6 class="f-w-700">' . $value['Equipo'] . '</h6></td>';
                    echo '</tr>';
                }
                ?>                                        
            </tbody>
        </table>
    </div>
<?php } ?>
<!-- Termina informacion Equipos Faltante -->

<!-- Comienza informacion Problemas Adicionales -->
<?php
if ($generalesMantenimiento['problemasAdicionales'] !== 'Sin Información') {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h1 class="f-w-700">Otros Problemas</h1>
            </div>                   
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>
        <?php
        foreach ($generalesMantenimiento['problemasAdicionales'] as $key => $datos) {
            $arrayEvidencias = explode(",", $datos['Evidencias']);
            ?>
            <div class="row pre">                                  
                <div class="col-md-12">                        
                    <h3><?php echo $datos['Area'] . ' ' . (($datos['Punto'] === '0') ? $punto = '' : $punto = $datos['Punto']); ?></h3>
                </div>                                                                                                                               
            </div>
            <div class="row">                                  
                <div class="col-md-12">
                    <h6 class="f-w-700">Descripción</h6>
                    <h5><?php echo $datos['Descripcion']; ?></h5>
                </div>  
            </div>
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
        <?php } ?>
    </div>
<?php } ?>
<!-- Termina informacion Problemas Adicionales -->