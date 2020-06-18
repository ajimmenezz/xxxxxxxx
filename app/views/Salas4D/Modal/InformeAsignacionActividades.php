<!--Empezando la seccion Asignacion de Actividades-->
<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h3>Historial Avances</h3>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 m-t-10 text-right">
        <label id="btnRegresar" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>
<div class="row m-t-10">
    <div class="col-md-12">
        <div id="errorInforme"></div>
    </div>                                    
</div>
<div class="row m-t-20">
    <div class="col-md-12 col-xs-12">
                <ul id="ulListaInforme" class="media-list media-list-with-divider media-messaging">
                            <?php
                            foreach ($actividad as $key => $value) {
                                
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
                                                        . '<div class="evidencia">'
                                                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-' . $value['Id'] . '" data-title="' . $pathInfo['basename'] . '">'
                                                        . '     <img src="' . $scr . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                                        . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                                        . ' </a>'
                                                        . '</div>';
                                    }

                                }else{
                                    echo $imgAvance .= '';
                                }
                                
                                
                                
                                echo "<li class='media media-sm'>"
                                        . "<div class='media-body'>"
                                            . "<h5 class='media-heading'>".$value['NombreUsuario'] . "</h5>"
                                            . "<h6 class='f-w-600'>".$value['Fecha'] . "</h6>"
                                            ."<p class='f-w-600'>".$value['Observaciones'] . "</p>";
                                                echo $imgAvance;                                      
                                           echo "</div>
                                        </li>";
                                echo $productohtml[$value['Id']];
                            }
                            ?>
                </ul>
        </div>
</div>
<!-- end col-6 -->