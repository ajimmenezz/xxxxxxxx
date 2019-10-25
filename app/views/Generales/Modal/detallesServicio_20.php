<div class="row">
    <div class="col-md-12">                            
        <fieldset>
            <legend class="pull-left width-full f-s-17">Detalles del servicio.</legend>
        </fieldset>  
    </div>
</div>
<div class="row">
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
            <pre><?php echo $datos['FechaConclusion']; ?></pre>
        </div>
    </div>
    <?php
} else {
    ?>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Área y Punto</h5>
        <pre><?php echo $datosCorrectivo['Area'] . ' - ' . $datosCorrectivo['Punto']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Equipo</h5>
        <pre><?php echo $datosCorrectivo['Modelo']; ?></pre>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Serie</h5>
        <pre><?php echo $datosCorrectivo['Serie']; ?></pre>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <h5 class="f-w-700">Terminal</h5>
        <pre><?php echo $datosCorrectivo['Terminal']; ?></pre>
    </div>
</div>
<?php
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
if (!in_array($datos['FirmaGerente'], ['', NULL])) {
    ?>
    <div class="row">
        <div class="col-md-offset-2 col-md-2 col-sm-offset-4 col-sm-6 col-xs-offset-12 col-xs-12">
            <h5 class="f-w-700 text-center">Firma de Gerente</h5>
            <img style="max-height: 100px;" src="<?php echo $datos['FirmaGerente']; ?>" alt="Firma de Gerente" />
            <h6 class="f-w-700 text-center"><?php echo $datos['NombreGerente']; ?></h6>            
            <h6 class="f-w-700 text-center"><?php echo $datos['FechaFirma']; ?></h6>            
        </div>
        <div class="col-md-3 col-sm-offset-4 col-sm-6 col-xs-offset-12 col-xs-12">
            <h5 class="f-w-700 text-center">Firma de Técnico</h5>
            <img style="max-height: 100px;" src="<?php echo $datos['FirmaTecnico']; ?>" alt="Firma de Tecnico" />
            <h6 class="f-w-700 text-center"><?php echo $datos['NombreTecnico']; ?></h6>            
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
            <li class="active"><a href="#nav-pills-diagnostico-equipo" data-toggle="tab" aria-expanded="true">Diagnóstico del Equipo</a></li>
            <li class=""><a href="#nav-pills-problemas-servicio" data-toggle="tab" aria-expanded="false">Problemas del Servicio</a></li>
            <li class=""><a href="#nav-pills-solucion" data-toggle="tab" aria-expanded="false">Solución</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="nav-pills-diagnostico-equipo">
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Diagnótico del Equipo.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                $contador = 0;
                if (count($diagnosticoEquipo) > 0) {
                    foreach ($diagnosticoEquipo as $k => $v) {
                        $contador++;
                        ?>

                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h5 class="f-w-700">Tipo Diagnostico</h5>
                                <pre><?php echo $v['TipoDiagnostico']; ?></pre>
                            </div>
                        </div>

                        <?php
                        if ($v['IdTipoDiagnostico'] !== '1') {
                            ?> 
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Observaciones</h5>
                                    <pre><?php echo $v['Observaciones']; ?></pre>
                                </div>
                            </div>

                            <?php
                        }

                        if ($v['IdTipoDiagnostico'] === '3') {
                            ?> 

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Tipo de Falla</h5>
                                    <pre><?php echo $v['TipoFalla']; ?></pre>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Falla</h5>
                                    <pre><?php echo $v['Falla']; ?></pre>
                                </div>
                            </div>

                            <?php
                        }
                        ?> 
                        <?php
                        if ($v['IdTipoDiagnostico'] === '4') {
                            ?>

                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <h5 class="f-w-700">Componente</h5>
                                    <pre><?php echo $v['Componente']; ?></pre>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <h5 class="f-w-700">Tipo de Falla</h5>
                                    <pre><?php echo $v['TipoFalla']; ?></pre>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <h5 class="f-w-700">Falla</h5>
                                    <pre><?php echo $v['Falla']; ?></pre>
                                </div>
                            </div>

                            <?php
                        }
                        ?> 
                        <div class="row">
                            <?php
                            if (!in_array($v['Evidencias'], ['', NULL])) {
                                $archivos = explode(",", $v['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($archivos as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div class="col-md-4">  '
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5"'
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:100px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12">
                                    <h5 class="f-w-700">Evidencias</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
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
                        if ($v['IdTipoDiagnostico'] === '2') {
                            ?> 
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
                                    <h5 class="f-w-700 text-center">Firma</h5>
                                    <img style="max-height: 120px;" src="<?php echo $v['Firma']; ?>" alt="Firma" />
                                    <h6 class="f-w-700 text-center"><?php echo $v['Gerente']; ?></h6>            
                                    <h6 class="f-w-700 text-center"><?php echo $v['FechaFirma']; ?></h6>            
                                </div>
                            </div>
                            <?php
                        }

                        if ($v['IdTipoDiagnostico'] === '1') {
                            ?>
                            <div class="row m-t-30">
                                <div class="col-md-12">                            
                                    <fieldset>
                                        <legend class="pull-left width-full f-s-17">Bitácora Observaciones del Diagnotico.</legend>
                                    </fieldset>  
                                </div>
                            </div>
                            <?php
                                foreach ($v['BitacoraObservaciones'] as $key => $value) {
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
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">                        
                                                <div class="p-5" style="background: #e5e9ed; opacity: .9; border: 1px solid #ccd0d4; border-radius: 3px;">
                                                    <p class="f-w-500 f-s-15 m-8"><?php echo $value['Descripcion']; ?></p>
                                                </div>                        
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($value['Evidencias']) && $value['Evidencias'] != '') {
                                    ?>               
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">                                                          
                                                <?php
                                                $evidencias = explode(",", $value['Evidencias']);
                                                foreach ($evidencias as $key => $valueEvidencias) {
                                                    echo '<div class="thumbnail-pic m-5 p-5">';
                                                        $ext = strtolower(pathinfo($valueEvidencias, PATHINFO_EXTENSION));
                                                        switch ($ext) {
                                                            case 'png': case 'jpeg': case 'jpg': case 'gif':
                                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $valueEvidencias . '"><img src="' . $valueEvidencias . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                break;
                                                            case 'xls': case 'xlsx':
                                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $valueEvidencias . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                break;
                                                            case 'doc': case 'docx':
                                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $valueEvidencias . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                break;
                                                            case 'pdf':
                                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $valueEvidencias . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                break;
                                                            default :
                                                                echo '<a class="imagenesSolicitud" target="_blank" href="' . $valueEvidencias . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                break;
                                                        }
                                                    echo '</div>';
                                                }
                                                ?>                                
                                            </div>
                                        </div>
                                    <?php
                                    }
                                 }
                        }
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de Diagnóstico del Equipo en este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tab-pane fade" id="nav-pills-problemas-servicio">
                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Problemas del Servicio.</legend>
                        </fieldset>  
                    </div>
                </div>
                <?php
                $contador = 0;
                if (count($problemasServicio) > 0) {
                    switch ($tipoProblema) {
                        case '1':
                            ?>
                            <div class="table-responsive">
                                <table  class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Solicitante</th>
                                            <th class="all">Fecha de Solicitud</th>
                                            <th class="all">Refacciones</th>
                                            <th class="all">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($problemasServicio['solicitudesRefaccionServicio'])) {
                                            foreach ($problemasServicio['solicitudesRefaccionServicio'] as $key => $value) {
                                                $RefaccionCantidad = str_replace(",", "<br>", $value['RefaccionCantidad']);
                                                echo '<tr>';
                                                echo '<td>' . $value['Solicitante'] . '</td>';
                                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                                echo '<td>' . $RefaccionCantidad . '</td>';
                                                echo '<td>' . $value['Estatus'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>  
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            break;
                        case '2':
                            ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Solicitante</th>
                                            <th class="all">Fecha de Solicitud</th>
                                            <th class="all">Equipo(s)</th>
                                            <th class="all">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($problemasServicio['solicitudesEquipoServicio'])) {
                                            foreach ($problemasServicio['solicitudesEquipoServicio'] as $key => $value) {
                                                $EquipoCantidad = str_replace(",", "<br>", $value['EquipoCantidad']);
                                                echo '<tr>';
                                                echo '<td>' . $value['Solicitante'] . '</td>';
                                                echo '<td>' . $value['FechaCreacion'] . '</td>';
                                                echo '<td>' . $EquipoCantidad . '</td>';
                                                echo '<td>' . $value['Estatus'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>  
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            break;
                        case '3':
                            if ($problemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '1' && $problemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">                            
                                        <fieldset>
                                            <legend class="pull-left width-full f-s-17">Se deja Equipo de Respaldo:</legend>
                                        </fieldset>  
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Equipo Retirado</h5>
                                        <pre><?php
                                            if ($problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'] === 'Sin Información') {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'];
                                            } else {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreEquipoRetira'];
                                            }
                                            ?>
                                        </pre>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Serie Equipo Retirado</h5>
                                        <pre><?php
                                            if ($problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'] === 'Sin Información') {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'];
                                            } else {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['SerieRetira'];
                                            }
                                            ?>
                                        </pre>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Equipo Respaldo</h5>
                                        <pre><?php
                                            if ($problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'] === 'Sin Información') {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'];
                                            } else {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreEquipoRespaldo'];
                                            }
                                            ?>
                                        </pre>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Serie Equipo Respaldo</h5>
                                        <pre><?php
                                            if ($problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'] === 'Sin Información') {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'];
                                            } else {
                                                echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['SerieRespaldo'];
                                            }
                                            ?>
                                        </pre>
                                    </div>
                                </div>

                                <!-- Comienza Firma -->
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
                                        <h5 class="f-w-700 text-center">Firma</h5>
                                        <?php
                                        if ($problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'] === 'Sin Información') {
                                            echo '<pre>';
                                            echo $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'];
                                            echo '</pre>';
                                        } else {
                                            echo '<img style="max-height: 120px;" src="' . $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['Firma'] . '" alt="Firma" />
                                            <h6 class="f-w-700 text-center">' . $problemasServicio['informacionGarantiaRespaldo']['equiposGarantiaRespaldo'][0]['NombreFirma'] . '</h6>            
                                            <h6 class="f-w-700 text-center">' . $problemasServicio['garantiaRespaldo'][0]['Fecha'] . '</h6>';
                                        }
                                        ?>           
                                    </div>
                                </div>
                                <!-- Termina Firma -->
                                <?php
                            }
                            if ($problemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $problemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <h4 class="f-w-700">Autorización sin Respaldo:</h4>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Autoriza</h5>
                                        <pre><?php echo $problemasServicio['garantiaRespaldo'][0]['Autoriza']; ?></pre>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php
                                    $arrayEvidencias = explode(",", $problemasServicio['garantiaRespaldo'][0]['Evidencia']);
                                    $htmlArchivos = '';
                                    foreach ($arrayEvidencias as $key => $value) {
                                        $htmlArchivos .= ''
                                                . '<div class="col-md-4">  '
                                                . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                                . ' <a class="m-l-5 m-r-5"'
                                                . '     href="' . $value . '" '
                                                . '     data-lightbox="evidencias' . $contador . '" '
                                                . '     data-title="' . basename($value) . '">'
                                                . '     <img src="' . $value . '" '
                                                . '         style="max-height:100px !important;" '
                                                . '         alt="' . basename($value) . '">     '
                                                . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                                . ' </a>'
                                                . '</div>'
                                                . '</div>';
                                    }
                                    ?>            
                                    <div class="col-md-12">
                                        <h5 class="f-w-700">Evidencias</h5>
                                        <h4><?php echo $htmlArchivos; ?></h4>
                                    </div>            
                                </div>

                                <?php
                            }
                            if ($problemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $problemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '1') {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <h4 class="f-w-700">Solicitud de Equipo de Respaldo:</h4>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Nombre de la persona asignada</h5>
                                        <pre><?php echo $problemasServicio['informacionGarantiaRespaldo']['solicitudEquipoRespaldo'][0]['Atiende']; ?></pre>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Fecha de asignación</h5>
                                        <pre><?php echo $problemasServicio['informacionGarantiaRespaldo']['solicitudEquipoRespaldo'][0]['FechaCreacion']; ?></pre>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Equipo</h5>
                                        <pre><?php echo $datosCorrectivo['Modelo']; ?></pre>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h5 class="f-w-700">Serie</h5>
                                        <pre><?php echo $datosCorrectivo['Serie']; ?></pre>
                                    </div>
                                </div>
                                <?php
                            }
                            if ($verificarEnvioEntrega !== FALSE) {
                                if ($verificarEnvioEntrega[0]['Tipo'] === 'Entrega') {
                                    ?>
                                    <div class = "row">
                                        <div class="col-md-12">                            
                                            <fieldset>
                                                <legend class="pull-left width-full f-s-17"><?php echo $envioEntrega['tituloEntregaEnvio']; ?></legend>
                                            </fieldset>  
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="f-w-700">Equipo Entregado</h6>
                                            <h5><?php echo $datosCorrectivo['Modelo']; ?></h5>
                                        </div>                                            
                                        <div class="col-md-6">
                                            <h6 class="f-w-700">Serie Equipo Entregado</h6>
                                            <h5><?php echo $datosCorrectivo['Serie']; ?></h5>
                                        </div>                                                              
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-12 col-xs-12">
                                            <h5 class='f-w-700 text-center'>Firma Entrega</h5>
                                            <?php echo '<img style="max-height: 90px" src="' . $envioEntrega['entregaEquipo'][0]['Firma'] . '"'; ?>  
                                            <h6 class='f-w-700 text-center'><?php echo $envioEntrega['entregaEquipo'][0]['Recibe'] ?></h6>               
                                            <h6 class='f-w-700 text-center'><?php echo $envioEntrega['entregaEquipo'][0]['Fecha'] ?></h6>               
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            if ($tipoProblema !== FALSE) {
                                if ($tipoProblema === '3') {
                                    if ($verificarEnvioEntrega !== FALSE) {
                                        if ($verificarEnvioEntrega[0]['Tipo'] === 'Envio') {
                                            ?>
                                            <div style="page-break-after:always;">
                                                <div class = "row">
                                                    <div class="col-md-12">                            
                                                        <fieldset>
                                                            <legend class="pull-left width-full f-s-17"><?php echo $envioEntrega['tituloEntregaEnvio']; ?></legend>
                                                        </fieldset>  
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <h5 class="f-w-700">Forma de Envio</h5>
                                                        <pre><?php echo $envioEntrega['envioEquipo'][0]['TipoEnvio']; ?></pre>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <h5 class="f-w-700">Guia</h5>
                                                        <pre><?php echo $envioEntrega['envioEquipo'][0]['PaqueteriaConsolidado']; ?></pre>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <h5 class="f-w-700">Comentarios de Envio</h5>
                                                        <pre><?php echo $envioEntrega['envioEquipo'][0]['ComentariosEnvio']; ?></pre>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <?php
                                                    $arrayEvidenciasEnvio = explode(",", $envioEntrega['envioEquipo'][0]['EvidenciasEnvio']);

                                                    $htmlArchivos = '';
                                                    foreach ($arrayEvidenciasEnvio as $key => $value) {
                                                        $htmlArchivos .= ''
                                                                . '<div class="col-md-4">  '
                                                                . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                                                . ' <a class="m-l-5 m-r-5"'
                                                                . '     href="' . $value . '" '
                                                                . '     data-lightbox="evidencias' . $contador . '" '
                                                                . '     data-title="' . basename($value) . '">'
                                                                . '     <img src="' . $value . '" '
                                                                . '         style="max-height:100px !important;" '
                                                                . '         alt="' . basename($value) . '">     '
                                                                . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                                                . ' </a>'
                                                                . '</div>'
                                                                . '</div>';
                                                    }
                                                    ?>            
                                                    <div class="col-md-12">
                                                        <h5 class="f-w-700">Evidencias</h5>
                                                        <h4><?php echo $htmlArchivos; ?></h4>
                                                    </div>            
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <h5 class="f-w-700">Fecha y Hora de Entrega</h5>
                                                        <?php (empty($envioEntrega['envioEquipo'][0]['FechaCapturaRecepcion'])) ? $FechaCapturaRecepcion = 'Sin información' : $FechaCapturaRecepcion = $envioEntrega['envioEquipo'][0]['FechaCapturaRecepcion']; ?>
                                                        <pre><?php echo $FechaCapturaRecepcion; ?></pre>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <h5 class="f-w-700">Persona quien recibe</h5>
                                                        <?php (empty($envioEntrega['envioEquipo'][0]['NombreRecibe'])) ? $NombreRecibe = 'Sin información' : $NombreRecibe = $envioEntrega['envioEquipo'][0]['NombreRecibe']; ?>
                                                        <pre><?php echo $NombreRecibe; ?></pre>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <h5 class="f-w-700">Comentarios de Entrega</h5>
                                                        <?php (empty($envioEntrega['envioEquipo'][0]['ComentariosEntrega'])) ? $ComentariosEntrega = 'Sin información' : $ComentariosEntrega = $envioEntrega['envioEquipo'][0]['ComentariosEntrega']; ?>
                                                        <pre><?php echo $ComentariosEntrega; ?></pre>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <?php
                                                    $arrayEvidenciasEntrega = explode(",", $envioEntrega['envioEquipo'][0]['EvidenciasEntrega']);
                                                    $htmlArchivos = '';
                                                    foreach ($arrayEvidenciasEntrega as $key => $value) {
                                                        $htmlArchivos .= ''
                                                                . '<div class="col-md-4">  '
                                                                . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                                                . ' <a class="m-l-5 m-r-5"'
                                                                . '     href="' . $value . '" '
                                                                . '     data-lightbox="evidencias' . $contador . '" '
                                                                . '     data-title="' . basename($value) . '">'
                                                                . '     <img src="' . $value . '" '
                                                                . '         style="max-height:100px !important;" '
                                                                . '         alt="' . basename($value) . '">     '
                                                                . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                                                . ' </a>'
                                                                . '</div>'
                                                                . '</div>';
                                                    }
                                                    ?>            
                                                    <div class="col-md-12">
                                                        <h5 class="f-w-700">Evidencias de Entrega</h5>
                                                        <?php
                                                        if (empty($envioEntrega['envioEquipo'][0]['ComentariosEntrega'])) {
                                                            ?>
                                                            <pre>Sin información</pre>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <h4><?php echo $htmlArchivos; ?></h4>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>            
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            }
                            break;
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de problemas este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tab-pane fade" id="nav-pills-solucion">  

                <div class="row">
                    <div class="col-md-12">                            
                        <fieldset>
                            <legend class="pull-left width-full f-s-17">Solución - <?php echo $correctivoSoluciones['returnArraySolicion']['tituloSolucion']; ?></legend>
                        </fieldset>  
                    </div>
                </div>

                <?php
                if ($correctivoSoluciones) {
                    switch ($correctivoSoluciones['correctivoSoluciones'][0]['IdTipoSolucion']) {
                        case '1':
                            ?>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h5 class="f-w-700">Solución</h5>
                                    <pre><?php echo $correctivoSoluciones['returnArraySolicion']['correctivosSolucionSinEquipo'][0]['Solucion']; ?></pre>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Observaciones</h5>
                                    <pre><?php echo $correctivoSoluciones['correctivoSoluciones'][0]['Observaciones']; ?></pre>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                $arrayEvidencias = explode(",", $correctivoSoluciones['correctivoSoluciones'][0]['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($arrayEvidencias as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div class="col-md-4">  '
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5"'
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:100px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12">
                                    <h5 class="f-w-700">Evidencias</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                            </div>
                            <?php
                            break;
                        case '2':
                            ?>

                            <div class="table-responsive">
                                <table  class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="all">Refacción</th>
                                            <th class="all">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($correctivoSoluciones['returnArraySolicion']['correctivosSolucionRefaccion'] as $key => $value) {
                                            echo '<tr>';
                                            echo '<td>' . $value['Refaccion'] . '</td>';
                                            echo '<td>' . $value['Cantidad'] . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>  
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Observaciones</h5>
                                    <pre><?php echo $correctivoSoluciones['correctivoSoluciones'][0]['Observaciones']; ?></pre>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                $arrayEvidencias = explode(",", $correctivoSoluciones['correctivoSoluciones'][0]['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($arrayEvidencias as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div class="col-md-4">  '
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5"'
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:100px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12">
                                    <h5 class="f-w-700">Evidencias</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                            </div>

                            <?php
                            break;
                        case '3':
                            ?>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Equipo</h5>
                                    <pre><?php echo $correctivoSoluciones['returnArraySolicion']['correctivosSolucionCambio'][0]['Equipo']; ?></pre>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h5 class="f-w-700">Serie</h5>
                                    <pre><?php echo $correctivoSoluciones['returnArraySolicion']['correctivosSolucionCambio'][0]['Serie']; ?></pre>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5 class="f-w-700">Observaciones</h5>
                                    <pre><?php echo $correctivoSoluciones['correctivoSoluciones'][0]['Observaciones']; ?></pre>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                $arrayEvidencias = explode(",", $correctivoSoluciones['correctivoSoluciones'][0]['Evidencias']);
                                $htmlArchivos = '';
                                foreach ($arrayEvidencias as $key => $value) {
                                    $htmlArchivos .= ''
                                            . '<div class="col-md-4">  '
                                            . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                            . ' <a class="m-l-5 m-r-5"'
                                            . '     href="' . $value . '" '
                                            . '     data-lightbox="evidencias' . $contador . '" '
                                            . '     data-title="' . basename($value) . '">'
                                            . '     <img src="' . $value . '" '
                                            . '         style="max-height:100px !important;" '
                                            . '         alt="' . basename($value) . '">     '
                                            . '     <p class="f-s-13 m-t-2 m-l-10">' . basename($value) . '</p>'
                                            . ' </a>'
                                            . '</div>'
                                            . '</div>';
                                }
                                ?>            
                                <div class="col-md-12">
                                    <h5 class="f-w-700">Evidencias</h5>
                                    <h4><?php echo $htmlArchivos; ?></h4>
                                </div>            
                            </div>
                            <?php
                            break;
                    }
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <pre>No hay registros de solución este servicio.</pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
