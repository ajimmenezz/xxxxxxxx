<div class="divTablas">
    <div style="page-break-after:always;">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4>Información General del Servicio</h4>
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-10">
            <?php if (!empty($solicitud['folio'])) { ?>
                <div class="col-md-4 col-xs-4">
                    <h6 class="f-w-700">Folio</h6>
                    <h5><?php echo $solicitud['folio']; ?></h5>            
                </div>       
            <?php } ?>  
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
        <div class="row m-t-10">
            <div class="col-md-12 col-xs-12">
                <h6 class="f-w-700">Resolución del Servicio</h6>
                <h5><?php echo $generales['descripcion']; ?></h5>
            </div>  
        </div>
    </div>
    
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h2>Lista de actividades</h2>
                <div class="underline"></div><br/>
            </div>
        </div>
        <div class="row" style="page-break-after:always;">
            <div class="table-responsive col-md-12">
                <table class="table table-bordered no-wrap" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Actividad</th>
                            <th class="all">Atiende</th>
                            <th class="all">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($infoActividad as $k => $v) {
                            if(!empty($v['NombreAtiende'])){
                                echo ""
                                    . '<tr>'
                                        . ' <td>'.$v['Actividad'] . '</td>'
                                        . ' <td>' . $v['NombreAtiende'] . '</td>'
                                        . ' <td class="text-center">' . $v['Estatus'] . '</td>'
                                    . '</tr>';
                            }else{
                                echo '';
                            }                            
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h2>Seguimiento actividades</h2>
                <div class="underline"></div>
            </div>
        </div>
                <?php
                  foreach($infoActividad as $k => $val){
                      if(!empty($val['NombreAtiende'])){
                          echo '<div class="row" style="page-break-after: always; width: 100%">'
                                . '<div class="col-md-12 col-xs-12"><br/>'
                                . '<strong>Actividad Padre:&nbsp&nbsp</strong>'.$val['ActividadPadre'].'<br/>'
                                . '<div class="row">'
                                    . '<div class="col-md-4">'
                                            . '<h6 class="f-w-700">Actividad:&nbsp</h6>'
                                            . '<h5>'.$val['Actividad'].'</h5>'
                                    . '</div>'
                                    . '<div class="col-md-4">'
                                            . '<h6 class="f-w-700">Atiende:</h6>'
                                            . '<h5>'.$val['NombreAtiende'].'</h5>'
                                    . '</div>'
                                    . '<div class="col-md-4" align="right"><h5>'.$val['Estatus'].'&nbsp&nbsp&nbsp'.$val['Fecha'].'</h5></div>'
                                . '</div><br/>'
                                  . '<div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <h3>Historial de avances</h3>
                                            <div class="underline"></div><br/>
                                        </div>
                                    </div>';
                                
                                foreach ($vistaAvance[$val['Id']] as $key => $value) {
                                                $imgAvance = '';
                                                if ($value['Archivos'] !== '' && $value['Archivos'] !== NULL) {
                                                    $imgAvance = '';
                                                    $archivos = explode(",", $value['Archivos']);
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
                                                        $imgAvance .= ''
                                                                        . '<br/><div class="col-md-3 col-md-offset-2" style="margin: 10px; padding: 10px;">'
                                                                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-' . $value['Id'] . '" data-title="' . $pathInfo['basename'] . '">'
                                                                        . '     <img src="' . $scr . '" style="height: 115px !important; max-width: 130px; display: inline-block;" alt="' . $pathInfo['basename'] . '"  />'
                                                                        . ' </a>'
                                                                        . '</div>';
                                                    }
                                                    
                                                }else{
                                                    echo $imgAvance .= '';
                                                }
                                                
                                                if(!empty($vistaProductos[$value['Id']])){
                                                        $productosAvance = '<div class="table-responsive">
                                                                            <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="all">Tipo Producto</th>
                                                                                        <th class="all">Producto</th>
                                                                                        <th class="all">Cantidad</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>';
                                                        foreach ($vistaProductos[$value['Id']] as $clave => $valor) {
                                                            $productosAvance .= '<tr>
                                                                            <td>' . $valor['TipoProducto'] . '</td>
                                                                            <td>' . $valor['Producto'] . ' ' . $valor['Serie'] . ' </td>
                                                                            <td>' . $valor['Cantidad'] . '</td>
                                                                            </tr>';
                                                        }
                                                            $productosAvance .= '</tbody>
                                                                            </table>
                                                                        </div>';
                                                }else{
                                                    echo $productosAvance = '';
                                                }
                                                
                                                
                                                echo '<div class="row">'
                                                        . '<div class="col-md-4"> <h6 class="f-w-700">Nombre: </h6><h5>'.$value['NombreUsuario']. '</h5></div>'
                                                        . '<div class="col-md-6"> <h6 class="f-w-700">Fecha: </h6><h5>'.$value['Fecha']. '</h5></div>'
                                                        . '<div class="col-md-12"> <h6 class="f-w-700">Observaciones: </h6><h5>'.$value['Observaciones']. '</h5></div>';
                                                            echo $imgAvance;
                                                            
                                                    echo  '</div>'
                                                        . '<br/>'.$productosAvance;
                                    
                                }
                         echo '</div></div>';
                      }else{
                          echo '';
                      }
                     
                }
                  
                ?>
        <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2>Resumen de Productos y Materiales</h2>
                    <div class="underline"></div><br/>
                </div>
        </div>
        <div class="row">
            <?php  
                $productosServicio = '<div class="table-responsive col-md-12">
                                    <table class="table table-bordered no-wrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="all">Tipo Producto</th>
                                                <th class="all">Producto</th>
                                                <th class="all">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                foreach ($tablaProductosServicio as $k => $v) {
                    $productosServicio .= '<tr>
                                    <td>' . $v['TipoProducto'] . '</td>
                                    <td>' . $v['Producto'] . ' ' . $v['Serie'] . ' </td>
                                    <td>' . $v['Cantidad'] . '</td>
                                    </tr>';
                }
                    $productosServicio .= '</tbody>
                                    </table>
                                </div>';
                    echo $productosServicio;
            ?>
        </div>
        
</div>