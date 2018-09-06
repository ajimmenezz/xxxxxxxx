<div class="row">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Detalles del servicio.</legend>
        </fieldset>  
    </div>
</div>
<div class="row">
    <!--        <div class="col-md-12">
                <pre>
    <?php // var_dump($datos); ?>
                </pre>
            </div>-->
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Id del Servicio</h5>
        <pre><?php echo $datos['Id']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Tipo</h5>
        <pre><?php echo $datos['Tipo']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Estatus</h5>
        <pre><?php echo $datos['Estatus']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Fecha de Creación</h5>
        <pre><?php echo $datos['FechaCreacion']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Fecha de Inicio</h5>
        <pre><?php echo $datos['FechaInicio']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Atiende</h5>
        <pre><?php echo $datos['Atiende']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h5 class="f-w-700">Servicio</h5>
        <pre><?php echo $datos['Servicio']; ?></pre>
    </div>
</div>
<?php
if (!in_array($datos['Sucursal'], ['', NULL])) {
    ?>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <h5 class="f-w-700">Sucursal</h5>
            <pre><?php echo $datos['Sucursal']; ?></pre>
        </div>    
        <?php
    }

    if (!in_array($datos['FechaConclusion'], ['', NULL])) {
        ?>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <h5 class="f-w-700">Fecha de Conclusión</h5>
            <span class="pre"><?php echo $datos['FechaConclusion']; ?></span>
        </div>
    </div>
    <?php
} else {
    ?>
    </div>
    <?php
}

if (!in_array($datos['Evidencias'], ['', NULL])) {
    $archivos = explode(",", $datos['Evidencias']);
    $htmlArchivos = '';
    foreach ($archivos as $key => $value) {
        $htmlArchivos .= ''
                . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                . ' <a class="m-l-5 m-r-5" '
                . '     href="' . $value . '" '
                . '     data-lightbox="adjunto-solicitud-' . $datos['Id'] . '" '
                . '     data-title="' . basename($value) . '">'
                . '     <img src="' . $value . '" '
                . '         style="max-height:200px !important;" '
                . '         alt="' . basename($value) . '">     '
                . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                . ' </a>'
                . '</div>';
    }
    ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h5 class="f-w-700">Archivos</h5>
            <h4><?php echo $htmlArchivos; ?></h4>
        </div>
    </div>
    <?php
}
if (!in_array($datos['Firma'], ['', NULL])) {
    ?>
    <div class="row">
        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
            <h5 class="f-w-700 text-center">Firma de Cierre</h5>
            <img style="max-height: 120px;" src="<?php echo $datos['Firma']; ?>" alt="Firma de Cierre" />
            <h6 class="f-w-700 text-center"><?php echo $datos['NombreFirma']; ?></h6>            
            <h6 class="f-w-700 text-center"><?php echo $datos['FechaFirma']; ?></h6>            
        </div>
    </div>
    <?php
}
?>
<div class="row m-t-20 m-t-20"></div>
<div class="row m-t-20">
    <div class="col-md-12">
        <ul class="nav nav-pills">
            <li class="active"><a href="#nav-pills-antes-despues" data-toggle="tab" aria-expanded="true">Antes y después</a></li>
            <li class=""><a href="#nav-pills-problemas-equipo" data-toggle="tab" aria-expanded="false">Problemas por equipo</a></li>
            <li class=""><a href="#nav-pills-equipo-faltante" data-toggle="tab" aria-expanded="false">Equipo faltante</a></li>
            <li class=""><a href="#nav-pills-problemas-adicionales" data-toggle="tab" aria-expanded="false">Otros problemas</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="nav-pills-antes-despues">
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Antes y después.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                $contador = 0;
                if (count($ad) > 0) {
                    foreach ($ad as $k => $v) {
                        $contador++;
                        ?>
                        <div class="row m-t-20">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <pre><?php echo $v['Area'] . ' ' . $v['Punto']; ?></pre>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h5 class="f-w-700">Observaciones del Antes</h5>
                                <h6><?php echo $v['ObservacionesAntes']; ?></h6>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h5 class="f-w-700">Observaciones del Después</h5>
                                <h6><?php echo $v['ObservacionesDespues']; ?></h6>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (!in_array($v['EvidenciasAntes'], ['', NULL])) {

                                $archivos = explode(",", $v['EvidenciasAntes']);
                                $htmlArchivos = '';
                                foreach ($archivos as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5" '
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias-antes-' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:75px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Evidencias Antes</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                                <?php
                            } else {
                                ?>
                                <div class="col-md-6 col-sm-6 col-xs-12"></div> 
                                <?php
                            }

                            if (!in_array($v['EvidenciasDespues'], ['', NULL])) {
                                $archivos = explode(",", $v['EvidenciasDespues']);
                                $htmlArchivos = '';
                                foreach ($archivos as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5" '
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias-antes-' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:75px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>';
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Evidencias Después</h5>
                                        <h4><?php echo $htmlArchivos; ?></h4>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col-md-6 col-sm-6 col-xs-12"></div> 
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros del antes y después en este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tab-pane fade" id="nav-pills-problemas-equipo">
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Problemas por equipo.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                $contador = 0;
                if (count($pe) > 0) {
                    ?>
                    <div class="row text-right">
                        <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnGenerarPDFProblemasEquipo"><i class="fa fa-file-pdf-o"></i> Generar PDF</a>
                    </div>
                    <?php
                    foreach ($pe as $k => $v) {
                        $contador++;
                        ?>
                        <div class="row m-t-20">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <pre><?php echo $v['Area'] . ' ' . $v['Punto']; ?></pre>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <h5 class="f-w-700">Modelo de Equipo</h5>
                                <h6><?php echo $v['Modelo']; ?></h6>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h5 class="f-w-700">Serie del Equipo</h5>
                                <h6><?php echo $v['Serie']; ?></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h5 class="f-w-700">Descripcion del problema</h5>
                                <h6><?php echo $v['Observaciones']; ?></h6>                                
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (!in_array($v['Evidencias'], ['', NULL])) {

                                $archivos = explode(",", $v['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($archivos as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5" '
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias-antes-' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:75px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Evidencias del Problema</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                                <?php
                            } else {
                                ?>
                                <div class="col-md-12 col-sm-12 col-xs-12"></div> 
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de problemas por equipo en este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tab-pane fade" id="nav-pills-equipo-faltante">  
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Equipo faltante.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                if (count($ef) > 0) {
                    ?>
                    <div class="row text-right">
                        <a href="javascript:;" class="btn btn-danger m-r-5 " id="btnGenerarPDFEquipoFaltante"><i class="fa fa-file-pdf-o"></i> Generar PDF</a>
                    </div>
                    <br>
                    <table id="data-table-equipo-faltante" class="table table-hover table-striped table-bordered no-wrap table-datatable" style="cursor:pointer; width">
                        <thead>
                            <tr>
                                <th>Área</th>
                                <th>Punto</th>
                                <th>Equipo Faltante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($ef as $key => $value) {
                                echo ""
                                . " <tr>"
                                . "     <td>" . $value['Area'] . "</td>"
                                . "     <td>" . $value['Punto'] . "</td>"
                                . "     <td>" . $value['Modelo'] . "</td>"
                                . " </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de equipos faltante en este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tab-pane fade" id="nav-pills-problemas-adicionales">
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Otros Problemas.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                $contador = 0;
                if (count($pa) > 0) {
                    ?>
                    <div class = "row text-right">
                        <a href = "javascript:;" class = "btn btn-danger m-r-5 " id = "btnGenerarPDFOtrosProblemas"><i class = "fa fa-file-pdf-o"></i> Generar PDF</a>
                    </div>
                    <?php
                    foreach ($pa as $k => $v) {
                        $contador++;
                        ?>
                        <div class="row m-t-20">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <pre><?php echo $v['Area'] . ' ' . $v['Punto']; ?></pre>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h5 class="f-w-700">Descripción del problema</h5>
                                <h6><?php echo $v['Descripcion']; ?></h6>                                
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (!in_array($v['Evidencias'], ['', NULL])) {

                                $archivos = explode(",", $v['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($archivos as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5" '
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias-antes-' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:75px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Evidencias del Problema</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                                <?php
                            } else {
                                ?>
                                <div class="col-md-12 col-sm-12 col-xs-12"></div> 
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de otros problemas en este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
