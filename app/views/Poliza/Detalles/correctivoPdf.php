<!--
 * Description: Formulario para generar el Documento PDF de Conclusion Servicio Correctivo
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
            <h6 class="f-w-700">Atiende</h6>
            <h5><?php echo $solicitud['atiendeServicio']; ?></h5>
        </div>                   
        <div class="col-md-4">
            <h6 class="f-w-700">Núm. Servicio</h6>
            <h5><?php echo $solicitud['servicio']; ?></h5>
        </div>                   
    </div>

    <div class="row m-t-10"> 
        <div class="col-md-4 col-xs-4">
            <h6 class="f-w-700">Tipo de Servicio</h6>
            <h5><?php echo $solicitud['tipoServicio']; ?></h5>
        </div> 
        <?php if ($correctivosDiagnostico !== 'Sin Información') { ?>  
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Area y Punto</h6>
                <h5><?php echo $generales['NombreArea'], $generales['Punto']; ?></h5>
            </div>  
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Equipo</h6>
                <h5><?php echo $generales['Equipo']; ?></h5>
            </div>  
        </div>
        <div class="row m-t-10">
            <div class="col-md-4 col-xs-12">
                <h6 class="f-w-700">Serie</h6>
                <h5><?php echo $generales['Serie']; ?></h5>
            </div>  
            <div class="col-md-4 col-xs-12">
                <h6 class="f-w-700">Número de Terminal</h6>
                <h5><?php echo $generales['Terminal']; ?></h5>
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
                <h6 class="f-w-700">Fecha Conclusión</h6>
                <h5><?php echo $solicitud['fechaConclusion']; ?></h5>
            </div>    
        </div>
    <?php } else { ?>
    </div>
<?php } ?>

<br>
<br>
<br>
<br>
<br>

<!-- Comienza Firmas -->
<?php if ($solicitud['firma'] !== 'Sin Información') { ?>       
    <div class="row">
        <div class="col-md-6 text-center"> 
            <h5 class='f-w-700 text-center'>Firma Gerente Cierre</h5>
            <?php echo '<img style="max-height: 90px" src="' . $solicitud['firma'] . '" >'; ?>  
            <h6 class='f-w-700 text-center'><?php echo $solicitud['nombreFirma'] ?></h6>               
            <h6 class='f-w-700 text-center'><?php echo $solicitud['fechaFirma'] ?></h6>               
        </div>
        <div class="col-md-6 text-center"> 
            <h5 class='f-w-700 text-center'>Firma Técnico Cierre</h5>
            <?php echo '<img style="max-height: 90px" src="' . $solicitud['firmaTecnico'] . '" >'; ?>  
            <h6 class='f-w-700 text-center'><?php echo $solicitud['atiendeServicio'] ?></h6>               
            <h6 class='f-w-700 text-center'><?php echo $solicitud['fechaFirma'] ?></h6>               
        </div>
    </div>
<?php } elseif (!empty($correctivosDiagnostico['Firma'])) {
    ?>       
    <div class="row">
        <div class="col-md-12 text-center"> 
            <h5 class='f-w-700 text-center'>Firma</h5>
            <?php echo '<img style="max-height: 90px" src="' . $correctivosDiagnostico['Firma'] . '" >'; ?>  
            <h6 class='f-w-700 text-center'><?php echo $correctivosDiagnostico['Gerente'] ?></h6>               
            <h6 class='f-w-700 text-center'><?php echo $correctivosDiagnostico['FechaFirma'] ?></h6>               
        </div>
    </div>
<?php } ?>
<!-- Termina Firmas -->

</div>
<!-- Termina informacion del Servicio -->

<!-- Comienza Informacion Diagnostico del Equipo -->
<?php if ($correctivosDiagnostico !== 'Sin Información' || $detallesSD !== 'Sin Información') { ?>
    <div style="page-break-after:always;">

        <?php if ($detallesSD !== 'Sin Información') { ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h1 class="f-w-700">Solicitud Service Desk</h1>
                </div>                   
                <div class="col-md-12 underline m-t-0 m-b-10"></div>
            </div>
            <div class = "row">
                <div class = "col-md-12 col-xs-12">
                    <h5><?php echo $detallesSD; ?></h5>
                </div>                                    
            </div>
            <?php
        }
        if ($correctivosDiagnostico !== 'Sin Información') {
            ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h1 class="f-w-700">Diagnóstico del Equipo - <?php echo $correctivosDiagnostico['NombreTipoDiagnostico']; ?></h1>
                </div>                   
                <div class="col-md-12 underline m-t-0 m-b-10"></div>
            </div>
            <?php
            if ($correctivosDiagnostico['IdTipoDiagnostico'] === '4') {
                ?>
                <div class = "row">
                    <div class = "col-md-6">
                        <h6 class = "f-w-700">Componente</h6>
                        <h5><?php echo $correctivosDiagnostico['Componente']; ?></h5>
                    </div>                                    
                </div>
                <?php
            }
            if ($correctivosDiagnostico['IdTipoDiagnostico'] === '4' || $correctivosDiagnostico['IdTipoDiagnostico'] === '3' || $correctivosDiagnostico['IdTipoDiagnostico'] === '2') {
                ?>
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
                <?php
            }
            if ($correctivosDiagnostico['IdTipoDiagnostico'] === '1') {
                ?>
                <div style="page-break-after:always;">
                    <div class="row">
                        <div class="col-md-12">                            
                            <fieldset>
                                <legend class="pull-left width-full f-s-17">Bitácora Observaciones del Diagnotico.</legend>
                            </fieldset>  
                        </div>
                    </div>

                    <?php
                    foreach ($correctivosDiagnostico['BitacoraObservaciones'] as $key => $value) {
                        $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
                        ?>
                        <div class="row m-t-25">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <p class="f-w-600 pull-left"><?php echo $value['Usuario']; ?></p>            
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <p class="f-w-600 pull-right"><?php echo $fecha; ?></p>            
                            </div>
                        </div>
                        <div class="row m-t-25">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <pre><?php echo $value['Descripcion']; ?></pre>
                            </div>
                        </div>
                        <?php
                        if (!empty($value['Evidencias']) && $value['Evidencias'] != '') {
                            $archivos = explode(",", $value['Evidencias']);
                            foreach ($archivos as $k => $v) {
                                ?>
                                <div style="display:inline-block; max-width: 180px; max-height: 250px;" >
                                    <a href="<?php echo $v; ?>" target="_blank" style="font-size:0px;" >
                                        <img class="img-thumbnail img-responsive" src="<?php echo $v; ?>">
                                    </a>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h6 class="f-w-700">Observaciones</h6>
                        <h5><?php echo $correctivosDiagnostico['Observaciones']; ?></h5>
                    </div>  
                </div>
                <?php
            }
            if ($correctivosDiagnostico['Evidencias'] !== '') {
                $arrayEvidencias = explode(",", $correctivosDiagnostico['Evidencias']['htmlArchivos']);
                ?>
                <div class="row">                                  
                    <div class="col-md-12">
                        <h6 class="f-w-700">Evidencias</h6>
                    </div>
                </div>
                <div class="row">
                    <?php
                    foreach ($arrayEvidencias as $key => $value) {
                        echo $value;
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
<?php } ?>
<!-- Finaliza Informacion Diagnostico del Equipo -->

<!-- Comienza Informacion Problemas del Servicio -->
<?php if ($tipoProblema !== 'Sin Información') { ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h1 class="f-w-700">Problemas del Servicio - <?php echo $tituloProblemasServicio; ?></h1>
            </div>                   
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>
        <?php
        switch ($tipoProblema) {
            case '1':
                ?>
                <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th><h4 class="f-w-700">Solicitante</h4></th>
                            <th><h4 class="f-w-700">Fecha de Solicitud</h4></th>
                            <th><h4 class="f-w-700">Refacciones</h4></th>
                            <th><h4 class="f-w-700">Estatus</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($returnArrayProblemasServicio['solicitudesRefaccionServicio'] as $key => $value) {
                            $RefaccionCantidad = str_replace(",", "<br>", $value['RefaccionCantidad']);
                            echo '<tr>';
                            echo '<td><h6 class="f-w-700">' . $value['Solicitante'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['FechaCreacion'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $RefaccionCantidad . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['Estatus'] . '</h6></td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
                <?php
                break;
            case '2':
                ?>
                <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th><h4 class="f-w-700">Solicitante</h4></th>
                            <th><h4 class="f-w-700">Fecha de Solicitud</h4></th>
                            <th><h4 class="f-w-700">Equipo(s)</h4></th>
                            <th><h4 class="f-w-700">Estatus</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($returnArrayProblemasServicio['solicitudesEquipoServicio'] as $key => $value) {
                            $EquipoCantidad = str_replace(",", "<br>", $value['EquipoCantidad']);
                            echo '<tr>';
                            echo '<td><h6 class="f-w-700">' . $value['Solicitante'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['FechaCreacion'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $EquipoCantidad . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['Estatus'] . '</h6></td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
                <?php
                break;
            case '3':
                if ($returnArrayProblemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '1' && $returnArrayProblemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                    if (!empty($returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'])) {
                        ?>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <h1 class="f-w-700">Se deja Equipo de Respaldo:</h1>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="f-w-700">Equipo Retirado</h6>
                                <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreEquipoRetira']; ?></h5>
                            </div>                                            
                            <div class="col-md-6">
                                <h6 class="f-w-700">Serie Equipo Retirado</h6>
                                <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['SerieRetira']; ?></h5>
                            </div>                                                              
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="f-w-700">Equipo Respaldo</h6>
                                <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreEquipoRespaldo']; ?></h5>
                            </div>                                            
                            <div class="col-md-6">
                                <h6 class="f-w-700">Serie Equipo Respaldo</h6>
                                <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['SerieRespaldo']; ?></h5>
                            </div>                                                              
                        </div>

                        <!-- Comienza Firma -->
                        <div class="row m-t-40">
                            <div class = "col-md-4 col-md-offset-4 text-center">
                                <h5 class = 'f-w-700 text-center'>Firma de Retiro de Equipo</h5>
                                <?php echo '<img style="max-height: 120px" src="/storage/Archivos/imagenesFirmas/Correctivo/RetiroGarantiaRespaldo/Firma_' . $solicitud['ticket'] . '_' . $solicitud['servicio'] . '.png" >';
                                ?>  
                                <h6 class='f-w-700 text-center'><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreFirma'] ?></h6>               
                                <h6 class='f-w-700 text-center'><?php echo $returnArrayProblemasServicio['garantiaRespaldo'][0]['Fecha'] ?></h6>               
                            </div>
                        </div>
                        <!-- Termina Firma -->
                        <?php
                    }
                }
                if ($returnArrayProblemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $returnArrayProblemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h1 class="f-w-700">Autorización sin Respaldo:</h1>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="f-w-700">Autoriza</h6>
                            <h5><?php echo $returnArrayProblemasServicio['garantiaRespaldo'][0]['Autoriza']; ?></h5>
                        </div>                                                                                                        
                    </div>
                    <?php
                    $arrayEvidencias = explode(",", $returnArrayProblemasServicio['garantiaRespaldo'][0]['Evidencia']);
                    foreach ($arrayEvidencias as $key => $datos) {
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
                        <?php
                    }
                }
                if ($returnArrayProblemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $returnArrayProblemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '1') {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h1 class="f-w-700">Solicitud de Equipo de Respaldo:</h1>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="f-w-700">Nombre de la persona asignada</h6>
                            <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['solicitudEquipoRespaldo'][0]['Atiende']; ?></h5>
                        </div>                                                                                                        
                        <div class="col-md-6">
                            <h6 class="f-w-700">Fecha de asignación</h6>
                            <h5><?php echo $returnArrayProblemasServicio['informacionGarantiaRespaldo']['solicitudEquipoRespaldo'][0]['FechaCreacion']; ?></h5>
                        </div>                                                                                                        
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-6 col-xs-6">
                            <h6 class="f-w-700">Equipo</h6>
                            <h5><?php echo $generales['Equipo']; ?></h5>
                        </div>  
                        <div class="col-md-6 col-xs-6">
                            <h6 class="f-w-700">Serie</h6>
                            <h5><?php echo $generales['Serie']; ?></h5>
                        </div>  
                    </div>
                    <?php
                }
                if ($envioEntrega !== 'Sin Información') {
                    if ($envioEntrega['Tipo'] === 'Entrega') {
                        ?>
                        <div class = "row">
                            <div class = "col-md-12 col-xs-12">
                                <h1 class = "f-w-700"><?php echo $returnArrayEnvioEntrega['tituloEntregaEnvio']; ?></h1>
                            </div>                   
                            <div class="col-md-12 underline m-t-0 m-b-10"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="f-w-700">Equipo Entregado</h6>
                                <h5><?php echo $generales['Equipo']; ?></h5>
                            </div>                                            
                            <div class="col-md-6">
                                <h6 class="f-w-700">Serie Equipo Entregado</h6>
                                <h5><?php echo $generales['Serie']; ?></h5>
                            </div>                                                              
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4 text-center"> 
                                <h5 class='f-w-700 text-center'>Firma Entrega</h5>
                                <?php echo '<img style="max-height: 90px" src="/storage/Archivos/imagenesFirmas/Correctivo/AcuseEntrega/Firma_' . $solicitud['ticket'] . '_' . $solicitud['servicio'] . '.png" >'; ?>  
                                <h6 class='f-w-700 text-center'><?php echo $returnArrayEnvioEntrega['entregaEquipo'][0]['Recibe'] ?></h6>               
                                <h6 class='f-w-700 text-center'><?php echo $returnArrayEnvioEntrega['entregaEquipo'][0]['Fecha'] ?></h6>               
                            </div>
                        </div>
                        <?php
                    }
                }
                break;
        }
        ?>
    </div>
<?php } ?>
<!-- Finaliza Informacion Problemas Servicio -->

<!-- Comienza Informacion Entrega del Equipo -->
<?php
if ($tipoProblema !== 'Sin Información') {
    if ($tipoProblema === '3') {
        if ($envioEntrega !== 'Sin Información') {
            if ($envioEntrega['Tipo'] === 'Envio') {
                ?>
                <div style="page-break-after:always;">
                    <div class = "row">
                        <div class = "col-md-12 col-xs-12">
                            <h1 class = "f-w-700"><?php echo $returnArrayEnvioEntrega['tituloEntregaEnvio']; ?></h1>
                        </div>                   
                        <div class="col-md-12 underline m-t-0 m-b-10"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="f-w-700">Forma de Envio</h6>
                            <h5><?php echo $returnArrayEnvioEntrega['envioEquipo'][0]['TipoEnvio']; ?></h5>
                        </div>                                            
                        <div class="col-md-6">
                            <h6 class="f-w-700">Guia</h6>
                            <h5><?php echo $returnArrayEnvioEntrega['envioEquipo'][0]['PaqueteriaConsolidado']; ?></h5>
                        </div>                                                              
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 col-xs-12">
                            <h6 class="f-w-700">Comentarios de Envio</h6>
                            <h5><?php echo $returnArrayEnvioEntrega['envioEquipo'][0]['ComentariosEnvio']; ?></h5>
                        </div>  
                    </div>
                    <?php
                    $arrayEvidenciasEnvio = explode(",", $returnArrayEnvioEntrega['envioEquipo'][0]['EvidenciasEnvio']);
                    ?>
                    <div class="row">                                  
                        <div class="col-md-12">
                            <h6 class="f-w-700">Evidencias de Envio</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        foreach ($arrayEvidenciasEnvio as $key => $value) {
                            ?>
                            <div style="display:inline-block; max-width: 200px;" >                                
                                <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">                                  
                            </div>
                        <?php } ?>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="f-w-700">Fecha y Hora de Entrega</h6>
                            <?php (empty($returnArrayEnvioEntrega['envioEquipo'][0]['FechaCapturaRecepcion'])) ? $FechaCapturaRecepcion = 'Sin información' : $FechaCapturaRecepcion = $returnArrayEnvioEntrega['envioEquipo'][0]['FechaCapturaRecepcion']; ?>
                            <h5><?php echo $FechaCapturaRecepcion; ?></h5>
                        </div>                                            
                        <div class="col-md-6">
                            <h6 class="f-w-700">Persona quien recibe</h6>
                            <?php (empty($returnArrayEnvioEntrega['envioEquipo'][0]['NombreRecibe'])) ? $NombreRecibe = 'Sin información' : $NombreRecibe = $returnArrayEnvioEntrega['envioEquipo'][0]['NombreRecibe']; ?>
                            <h5><?php echo $NombreRecibe; ?></h5>
                        </div>                                                              
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 col-xs-12">
                            <h6 class="f-w-700">Comentarios de Entrega</h6>
                            <?php (empty($returnArrayEnvioEntrega['envioEquipo'][0]['ComentariosEntrega'])) ? $ComentariosEntrega = 'Sin información' : $ComentariosEntrega = $returnArrayEnvioEntrega['envioEquipo'][0]['ComentariosEntrega']; ?>
                            <h5><?php echo $ComentariosEntrega; ?></h5>
                        </div>  
                    </div>
                    <?php
                    $arrayEvidenciasEnvio = explode(",", $returnArrayEnvioEntrega['envioEquipo'][0]['EvidenciasEntrega']);
                    ?>
                    <div class="row">                                  
                        <div class="col-md-12">
                            <h6 class="f-w-700">Evidencias de Entrega</h6>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        if (empty($returnArrayEnvioEntrega['envioEquipo'][0]['ComentariosEntrega'])) {
                            ?>
                            <div class="col-md-12 col-xs-12">
                                <h5>Sin información</h5>
                            </div> 

                            <?php
                        } else {
                            foreach ($arrayEvidenciasEnvio as $key => $value) {
                                ?>
                                <div style="display:inline-block; max-width: 200px;" >                                
                                    <img class="img-thumbnail img-responsive" src="<?php echo $value; ?>">                                  
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }
    }
}
?>
<!-- Finaliza Informacion Entrega del Equipo -->

<!-- Comienza Informacion Solucion -->
<?php if ($correctivoSoluciones !== 'Sin Información') { ?>
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h1 class="f-w-700">Solución - <?php echo $returnArraySolicion['tituloSolucion']; ?></h1>
            </div>                   
            <div class="col-md-12 underline m-t-0 m-b-10"></div>
        </div>
        <?php
        switch ($correctivoSoluciones['IdTipoSolucion']) {
            case '1':
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="f-w-700">Solución</h6>
                        <h5><?php echo $returnArraySolicion['correctivosSolucionSinEquipo'][0]['Solucion']; ?></h5>
                    </div>                                                                                                        
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h6 class="f-w-700">Observaciones</h6>
                        <h5><?php echo $correctivoSoluciones['Observaciones']; ?></h5>
                    </div>  
                </div>
                <?php
                $arrayEvidencias = explode(",", $correctivoSoluciones['Evidencias']['htmlArchivos']);
                ?>
                <div class="row">                                  
                    <div class="col-md-12">
                        <h6 class="f-w-700">Evidencias</h6>
                    </div>
                </div>
                <div class="row">
                    <?php
                    foreach ($arrayEvidencias as $key => $value) {
                        echo $value;
                    }
                    ?>
                </div>
                <?php
                break;
            case '2':
                ?>
                <table class="table-bordered no-wrap" style="cursor:pointer" width="100%">
                    <thead>
                        <tr>
                            <th><h4 class="f-w-700">Refacción</h4></th>
                            <th><h4 class="f-w-700">Cantidad</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($returnArraySolicion['correctivosSolucionRefaccion'] as $key => $value) {
                            echo '<tr>';
                            echo '<td><h6 class="f-w-700">' . $value['Refaccion'] . '</h6></td>';
                            echo '<td><h6 class="f-w-700">' . $value['Cantidad'] . '</h6></td>';
                            echo '</tr>';
                        }
                        ?>                                        
                    </tbody>
                </table>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h6 class="f-w-700">Observaciones</h6>
                        <h5><?php echo $correctivoSoluciones['Observaciones']; ?></h5>
                    </div>  
                </div>
                <?php
                $arrayEvidencias = explode(",", $correctivoSoluciones['Evidencias']['htmlArchivos']);
                ?>
                <div class="row">                                  
                    <div class="col-md-12">
                        <h6 class="f-w-700">Evidencias</h6>
                    </div>
                </div>
                <div class="row">
                    <?php
                    foreach ($arrayEvidencias as $key => $value) {
                        echo $value;
                    }
                    ?>
                </div>
                <?php
                break;
            case '3':
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="f-w-700">Equipo</h6>
                        <h5><?php echo $returnArraySolicion['correctivosSolucionCambio'][0]['Equipo']; ?></h5>
                    </div>                                            
                    <div class="col-md-6">
                        <h6 class="f-w-700">Serie</h6>
                        <h5><?php echo $returnArraySolicion['correctivosSolucionCambio'][0]['Serie']; ?></h5>
                    </div>                                                              
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="f-w-700"><?php echo $generales['Equipo']; ?> (<?php echo $generales['Serie']; ?>) se sustituyó por el equipo <?php echo $returnArraySolicion['correctivosSolucionCambio'][0]['Equipo']; ?> (<?php echo $returnArraySolicion['correctivosSolucionCambio'][0]['Serie']; ?>)</h6>
                    </div>                                                                                                        
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12 col-xs-12">
                        <h6 class="f-w-700">Observaciones</h6>
                        <h5><?php echo $correctivoSoluciones['Observaciones']; ?></h5>
                    </div>  
                </div>
                <?php
                $arrayEvidencias = explode(",", $correctivoSoluciones['Evidencias']['htmlArchivos']);
                ?>
                <div class="row">                                  
                    <div class="col-md-12">
                        <h6 class="f-w-700">Evidencias</h6>
                    </div>
                </div>
                <div class="row">
                    <?php
                    foreach ($arrayEvidencias as $key => $value) {
                        echo $value;
                    }
                    ?>
                </div>
                <?php
                break;
        }
        ?>
    </div>
<?php } ?>
<!-- Finaliza Informacion Solucion -->

<!-- Comienza Notas -->
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
                    <div style="display:inline-block; max-width: 180px; max-height: 250px;" >
                        <a href="<?php echo $v; ?>" target="_blank" style="font-size:0px;" >
                            <img class="img-thumbnail img-responsive" src="<?php echo $v; ?>">
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

if (!empty($avanceServicio)) {
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

            if (!empty($value[0]['tablaEquipos'])) {
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
                            foreach ($value[0]['tablaEquipos'] as $key => $valor) {
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
