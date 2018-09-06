<ul id="listaHistorial" class="timeline">
    <?php
    if (count($datos) > 0) {
        if ($datos !== '') {
            foreach ($datos as $key => $item) {
                $arrayArchivos = $item['archivos'];
                $arrayFecha = explode(' ', $item['fecha']);
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
                        <?php (($item['IdTipo'] === '1')) ? $icono = 'fa fa-check' : $icono = 'fa fa-ban'; ?>
                        <?php (($item['IdTipo'] === '1')) ? $colorIcono = '' : $colorIcono = 'background:#ff5b57'; ?>
                        <a href="javascript:;" style="<?php echo $colorIcono ?>"><i class="<?php echo $icono ?>"></i></a>
                    </div>
                    <!-- end timeline-icon -->

                    <!-- begin timeline-body -->
                    <div class="timeline-body">
                        <div class="timeline-header">
                            <div class="row">
                                <div class="col-md-9 col-xs-9">
                                    <?php (empty($item['foto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $item['foto']; ?>
                                    <span class="userimage"><img src="<?php echo $foto ?>" alt="" /></span>
                                    <span class="username"><?php echo $item['usuario'] ?></span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    <?php (($item['IdTipo'] === '1')) ? $colorTituloAvance = 'color:#337ab7' : $colorTituloAvance = 'color:#ff5b57'; ?>
                                    <span class="pull-right text-muted"><h4 style="<?php echo $colorTituloAvance ?>"><?php echo $item['TipoAvance'] ?></h4></span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-content">
                            <p>
                                <?php echo $item['descripcion'] ?>
                            </p>
                        </div>



                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 m-t-10">                                                          
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
                        <?php
                        $contadorTabla = count($item['items']);
                        if ($contadorTabla > 0) {
                            ?>
                            <div class="timeline-footer">
                                <br>
                                <div class="row m-r-10">
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

                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
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
                                            foreach ($item['items'] as $key => $value) {
                                                echo '<tr>';
                                                echo '<td>' . $value['Tipo'] . '</td>';
                                                echo '<td>' . $value['Equipo'] . '</td>';
                                                echo '<td>' . $value['Serie'] . '</td>';
                                                echo '<td>' . $value['Cantidad'] . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <!-- end timeline-body -->
                </li>
                <?php
            }
        } else {
            ?>
            <div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><pre>Al parecer esta solicitud no tiene historial.</pre></div></div>
            <?php
        }
    } else {
        ?>

        <li>
            <!--begin timeline-icon--> 
            <div class="timeline-icon">
                <a href="javascript:;" style="background:#707478"><i class="fa fa-spinner"></i></a>
            </div>
            <!--         end timeline-icon 
                     begin timeline-body -->
            <div class="timeline-body">
                Sin historial que mostrar...
            </div>
            <!--begin timeline-body--> 
        </li>

        <!-- end timeline -->
        <!--Finalizando la seccion Notas-->
        <?php
    }
    ?>
</ul>
