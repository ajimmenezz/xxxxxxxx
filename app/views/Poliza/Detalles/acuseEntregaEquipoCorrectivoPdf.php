<!--
 * Description: Formulario para generar el PDF Retiro a Garantia con Respaldo Servicio Correctivo
 *
 * @author: Alberto Barcenas
 *
-->
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
        <div class="col-md-6">
            <h6 class="f-w-700">Atiende</h6>
            <h5><?php echo $solicitud['atiendeServicio']; ?></h5>
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
    <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
        <thead>
            <tr>
                <th><h4 class="f-w-700">Equipo</h4></th>
                <th><h4 class="f-w-700">Serie</h4></th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo '<tr>';
            echo '<td><h6 class="f-w-700">' . $entregaEquipo['Equipo'] . '</h6></td>';
            echo '<td><h6 class="f-w-700">' . $entregaEquipo['Serie'] . '</h6></td>';
            echo '</tr>';
            ?>                                        
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Comienza Firma -->
    <div class="row m-t-40">
        <div class = "col-md-4 col-md-offset-4 text-center">
            <h5 class = 'f-w-700 text-center'>Firma</h5>
            <?php
            echo '<img style="max-height: 120px" src="/storage/Archivos/imagenesFirmas/Correctivo/AcuseEntrega/Firma_' . $solicitud['ticket'] . '_' . $solicitud['servicio'] . '.png" >';
            if ($entregaEquipo['NombreRecibe'] === NULL) {
                ?>  
                <h6 class='f-w-700 text-center'><?php echo $entregaEquipo['NombreFirma'] ?></h6>               
                <?php
            } else {
                ?>  
                <h6 class='f-w-700 text-center'><?php echo $entregaEquipo['NombreRecibe'] ?></h6>               
                <?php
            }
            ?>  
            <h6 class='f-w-700 text-center'><?php echo $entregaEquipo['Fecha'] ?></h6>               
        </div>
    </div>
    <!-- Termina Firma -->

</div>