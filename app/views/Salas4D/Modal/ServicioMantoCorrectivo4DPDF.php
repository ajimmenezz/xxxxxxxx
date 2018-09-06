<div class="divTablas">
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Información General del Servicio Correctivo</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Número de Ticket</h6>
                <h5><?php echo $solicitud['ticket']; ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">                        
                <div class="underline"></div>                    
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Tipo de Servicio</h6>
                <h5><?php echo $solicitud['tipoServicio']; ?></h5>
            </div>                    
            <div class="col-md-4 col-xs-4">
                <h6 class="f-w-700">Sucursal</h6>
                <h5><?php echo $solicitud['sucursal'] ?></h5>
            </div>                    
            <div class="col-md-4 col-xs-4">
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
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center"> 
                <h5 class='f-w-700 text-center'>Firma Cierre</h5>
                <?php echo '<img style="max-height: 120px" src="/storage/Archivos/imagenesFirmas/Firma_' . $solicitud['ticket'] . '_' . $solicitud['servicio'] . '.png" >'; ?>  
                <h6 class='f-w-700 text-center'><?php echo $solicitud['nombreFirma'] ?></h6>               
                <h6 class='f-w-700 text-center'><?php echo $solicitud['fechaFirma'] ?></h6>               
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Datos del Correctivo</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-3 col-xs-4">
                <h6 class="f-w-700">Sucursal</h6>
                <?php 
                    foreach ($sucursal4D as $valor) {
                        $idSucursal = $serviciosTicket[0]['IdSucursal'];
                        if($valor['Id'] == $idSucursal){ ?>
                            <h5><?php echo $valor['Nombre']; ?></h5>
                <?php }  } ?>
            </div> 
            <div class="col-md-3 col-xs-4">
                <h6 class="f-w-700">Tipo de Falla</h6>
                <?php 
                    foreach ($catTiposFalla as $valorFalla) {
                     if($correctivosGenerales['tipoFalla'] == $valorFalla['Id']){?>
                        <h5><?php echo $valorFalla['Nombre']; ?></h5>
                <?php } } ?>
            </div>
            <div class="col-md-6 col-xs-12">
                <?php if($correctivosGenerales['tipoFalla'] == 1){
                        $tituloDanado = "Elemento dañado";
                      }else{ 
                        $tituloDanado = 'Subelemento dañado';
                      } ?>
                <h6 class="f-w-700"><?php echo $tituloDanado; ?></h6>
                <?php foreach ($nombreProductoDanado as $valorDanado) {
                    if($correctivosGenerales['elementoRadio'] == $valorDanado['Id'] && $correctivosGenerales['tipoFalla'] == 1){ ?>
                        <h5><?php echo $valorDanado['Elemento']; ?> - <?php echo $valorDanado['Serie']; ?></h5>
                <?php }else if($correctivosGenerales['tipoFalla'] == 2 && $correctivosGenerales['elementoRadio'] == $valorDanado['Id']){ ?>
                        <h5><?php echo $valorDanado['Subelemento'] ?> - <?php echo $valorDanado['Serie']; ?> </h5>
                <?php } }?>
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Información de la Solución</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Tipo de Solución</h6>
                <?php 
                    foreach ($tipoSolucion as $valorTipoSolcuion) {
                        if($valorTipoSolcuion['id'] == $solucionServicio[0]['IdTipoSolucion']){
                            $solucion = $valorTipoSolcuion['text'];
                        }
                    }
               ?>
               <h5><?php echo $solucion; ?></h5>
            </div>
        </div>
        <div class="row m-t-10">
        <?php if($solucionServicio[0]['IdTipoSolucion'] == 1 && $correctivosGenerales['tipoFalla'] == 2){ ?>       
            <div class="col-md-12 col-xs-12"></div>  
         <?php }else if ($solucionServicio[0]['IdTipoSolucion'] == 3 && $correctivosGenerales['tipoFalla'] == 1 || $correctivosGenerales['tipoFalla'] == 2){ ?>
            <div class="col-md-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered no-wrap" width="100%">
                     <thead>
                         <tr>
                             <th class="all">Subelemento dañado</th>
                             <th class="all">Subelemento utilizado</th>
                         </tr>
                     </thead>
                     <tbody>
                         <!--<h6 class="f-w-700"> <?php echo $tituloUtil; ?> </h6>-->
                         <?php 
                             foreach ($subDanados as $v) {
                                 $masSubElementosDanado = $v['SubelementoDanado'];
                                    $masSubElementosUtil = $v['SubelementoInventario'];?>
                                <tr>
                                    <td><?php echo $masSubElementosDanado ?></td>
                                    <td><?php echo $masSubElementosUtil ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
         <?php }else if ($solucionServicio[0]['IdTipoSolucion'] == 2 && $correctivosGenerales['tipoFalla'] == 1){?>
             <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Elemento utilizado</h6>
                <h5><?php echo $elementoUtil[0]['Nombre']; ?></h5>
            </div> 
         <?php } ?>      
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Adicionales</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Notas</h6>
                <h5><?php echo $solucionServicio[0]['Observaciones'] ?></h5>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Archivos Adjuntos</h6>
                <?php 
                    $imgAvance = '';
                    $evidencia = $solucionServicio[0]['Archivos'];
                    if ($evidencia !== '' && $evidencia !== NULL) {
                        $archivos = explode(",", $evidencia);
                        foreach ($archivos as $k => $v) {
                            $pathInfo = pathinfo($v);
                            if (array_key_exists("extension", $pathInfo)) {
                                switch (strtolower($pathInfo['extension'])) {
                                    case 'doc': case 'docx':
                                        $scr = '/assets/img/Iconos/word_icon.png';
                                        break;
                                    case 'xls': case 'xlsx':
                                        $scr = '/assets/img/Iconos/excel_icon.png';
                                        break;
                                    case 'pdf':
                                        $scr = '/assets/img/Iconos/pdf_icon.png';
                                        break;
                                    case 'jpg': case 'jpeg': case 'bmp': case 'gif': case 'png':
                                        $scr = $v;
                                        break;
                                    default :
                                        $scr = '/assets/img/Iconos/file_icon.png';
                                        break;
                                }
                            } else {
                                $scr = '/assets/img/Iconos/file_icon.png';
                            }
                            echo '<br/><div class="col-md-3 col-md-offset-2" style="margin: 10px; padding: 10px;">'
                                    . '<img src="' . $scr . '" style="height: 140px !important; max-width: 170px; display: inline-block;" alt="' . $pathInfo['basename'] . '"  />'
                                  . '</div>';
                        }
                    }else{
                        echo 'Sin archivos adjuntos';
                    }
                ?>
                
            </div>
        </div>
</div>