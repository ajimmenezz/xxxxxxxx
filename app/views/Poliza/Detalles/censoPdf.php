<!--
 * Description: Formulario para generar PDF Servicio Censo
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

<!-- Comienza informacion del Censo -->
<?php
if ($equiposCensados['equiposCensados'] !== 'Sin Información') {
    ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12">
                <h3 class="f-w-700">Resumen del Censo</h3>
            </div>                   
        </div>
        <div class="row">
            <div class="col-md-12">                        
                <div class="underline"></div>                    
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h6 class="f-w-700">Total de Campos en POS</h6>
                <table class="table-bordered" width="100%" >
                    <thead>
                        <tr>
                            <th><h4 class="f-w-700">Área</h4></th>
                            <th><h4 class="f-w-700">Total</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($equiposCensados['totalAreas'] as $key => $value) {
                            echo '<tr>';
                            echo '<td><h6 class="f-w-700">' . $value['Area'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['Total'] . '</h6></td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="f-w-700">Total de Equipos</h6>
                <table class="table-bordered" width="100%" >
                    <thead>
                        <tr>
                            <th><h4 class="f-w-700">Linea</h4></th>
                            <th><h4 class="f-w-700">Total</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($equiposCensados['totalLineas'] as $key => $value) {
                            echo '<tr>';
                            echo '<td><h6 class="f-w-700">' . $value['Linea'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['Total'] . '</h6></td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Termina informacion del Censo -->

<!-- Comienza informacion del Censo -->
<?php
if ($equiposCensados['equiposCensados'] !== 'Sin Información') {
    ?>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3 class="f-w-700">Información del Censo</h3>
        </div>                   
    </div>
    <div class="row">
        <div class="col-md-12">                        
            <div class="underline"></div>                    
        </div>
    </div>
    <br>
    <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th><h4 class="f-w-700">Área</h4></th>
                <th><h4 class="f-w-700">Punto</h4></th>
                <th><h4 class="f-w-700">Modelo</h4></th>
                <th><h4 class="f-w-700">Serie</h4></th>
                <th><h4 class="f-w-700">No. Terminal</h4></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($equiposCensados['equiposCensados'] as $key => $value) {
                echo '<tr>';
                echo '<td><h6 class="f-w-700">' . $value['Area'] . '</h6></td>';
                echo '<td><h6 class="f-w-700">' . $value['Punto'] . '</h6></td>';
                echo '<td><h6 class="f-w-700">' . $value['Equipo'] . '</h6></td>';
                echo '<td><h6 class="f-w-700">' . $value['Serie'] . '</h6></td>';
                echo '<td><h6 class="f-w-700">' . $value['Extra'] . '</h6></td>';
                echo '</tr>';
            }
            ?>                                        
        </tbody>
    </table>
<?php } ?>
<!-- Termina informacion del Censo -->

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