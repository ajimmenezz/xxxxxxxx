<div class="panel-body">
    <!-- begin timeline -->
    <ul id="listaHistorial" class="timeline">
        <?php
        if (!empty($bitacoraReporteFalso)) {
            foreach ($bitacoraReporteFalso as $key => $item) {
                $arrayArchivos = explode(",", $item['Evidencias']);
                $arrayFecha = explode(' ', $item['Fecha']);
                ?>
                <li>

                    <!-- begin timeline-time -->
                    <div class="timeline-time">
                        <span class="date"><?php echo $arrayFecha[0] ?></span>
                        <span class="time"><?php echo $arrayFecha[1] ?></span>
                    </div>
                    <!-- end timeline-time -->

                    <!-- begin timeline-icon -->
                    <div class="timeline-icon">
                        <a href="javascript:;"><i class="fa fa-check"></i></a>
                    </div>
                    <!-- end timeline-icon -->

                    <!-- begin timeline-body -->
                    <div class="timeline-body">
                        <div class="timeline-header">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <?php (empty($item['Foto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $item['Foto']; ?>
                                    <span class="userimage"><img src="<?php echo $foto ?>" alt="" /></span>
                                    <span class="username"><?php echo $item['Usuario'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-content">
                            <p>
                                <?php echo $item['Observaciones'] ?>
                            </p>
                        </div>
                        <div class="row">
                            <?php
                            foreach ($arrayArchivos as $key => $value) {
                                if ($value != '') {
                                    echo '<div class="thumbnail-pic m-5 p-5">';
                                    $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                                    switch ($ext) {
                                        case 'png': case 'jpeg': case 'jpg': case 'gif':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'xls': case 'xlsx':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'doc': case 'docx':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        case 'pdf':
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                        default :
                                            echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                            break;
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- end timeline-body -->
                </li>
                <?php
            }
            ?>
            <li>
                <!-- begin timeline-icon -->
                <div class="timeline-icon">
                    <a href="javascript:;" style="background:#707478"><i class="fa fa-spinner"></i></a>
                </div>
                <!-- end timeline-icon -->
                <!-- begin timeline-body -->
                <div class="timeline-body">
                    Fin de la Bitácora...
                </div>
                <!-- begin timeline-body -->
            </li>
            <?php
        }
        ?>
    </ul>
    <!-- end timeline -->
</div>