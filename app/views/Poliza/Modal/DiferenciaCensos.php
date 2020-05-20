<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="f-s-15 f-w-600 text-center" colspan="5"><?php echo $generales['Sucursal']; ?></th>
                </tr>
                <tr>
                    <th class="f-s-15 f-w-600 text-center">Total de<br />Equipos censados</th>
                    <th class="f-s-15 f-w-600 text-center">Total de equipos que deben existir<br />(basado en el estandar)</th>
                    <th class="f-s-15 f-w-600 text-center">Total <br />Faltantes</th>
                    <th class="f-s-15 f-w-600 text-center">Total <br />Sobrantess</th>
                    <th class="f-s-15 f-w-600 text-center">Diferencia</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="f-s-15 f-w-600 text-center"><?php echo $diferenciasFull['totales']['censados']; ?></td>
                    <td class="f-s-15 f-w-600 text-center"><?php echo $diferenciasFull['totales']['kit']; ?></td>
                    <td class="f-s-15 f-w-600 text-center"><label class="f-s-15 f-w-600 text-danger"><?php echo ($diferenciasFull['totales']['faltantes'] > 0 ? '-' : '') . $diferenciasFull['totales']['faltantes']; ?></label></td>
                    <td class="f-s-15 f-w-600 text-center"><label class="f-s-15 f-w-600 text-success"><?php echo ($diferenciasFull['totales']['sobrantes'] > 0 ? '+' : '') . $diferenciasFull['totales']['sobrantes']; ?></label></td>
                    <td class="f-s-15 f-w-600 text-center"><label class="f-s-15 f-w-600 <?php echo ((int) $diferenciasFull['totales']['sobrantes'] - (int) $diferenciasFull['totales']['faltantes']) < 0 ? 'text-danger' : 'text-sucees'; ?>"><?php echo (int) $diferenciasFull['totales']['sobrantes'] - (int) $diferenciasFull['totales']['faltantes']; ?></label></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <ul class="nav nav-pills">
            <?php
            if (isset($mostrarCenso) && $mostrarCenso) {
                echo '<li class="active"><a href="#detCenso" data-toggle="tab">Censo</a></li>';
                $active = '';
            } else {
                $active = ' class="active"';
            }
            ?>
            <li <?php echo $active; ?>><a href="#difConteos" data-toggle="tab">Faltantes y Sobrantes (Conteos)</a></li>
            <li><a href="#difSeries" data-toggle="tab">Diferencias (Series)</a></li>
            <li><a href="#cambiosSeries" data-toggle="tab">Cambios Serie</a></li>
            <li><a href="#difFaltantes" data-toggle="tab">Faltantes</a></li>
            <li><a href="#difSobrantes" data-toggle="tab">Sobrantes</a></li>
            <li><a href="#Pruebas" data-toggle="tab">Pruebas</a></li>
        </ul>

        <div class="tab-content">
            <?php
            if (isset($mostrarCenso) && $mostrarCenso) {
                $active = '';
            ?>
                <div class="tab-pane fade active in" id="detCenso">
                    <div class="row m-t-15">
                        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Área</th>
                                        <th>Punto</th>
                                        <th>Línea</th>
                                        <th>Sublínea</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($actual) && count($actual) > 0) {
                                        foreach ($actual as $k => $v) {
                                            echo '
                                            <tr>
                                                <td>' . $v['Area'] . '</td>
                                                <td>' . $v['Punto'] . '</td>
                                                <td>' . $v['Linea'] . '</td>
                                                <td>' . $v['Sublinea'] . '</td>
                                                <td>' . $v['Marca'] . '</td>
                                                <td>' . $v['Modelo'] . '</td>
                                                <td>' . $v['Serie'] . '</td>
                                            </tr>
                                            ';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                $active = ' active in ';
            }
            ?>
            <div class="tab-pane fade <?php echo $active; ?>" id="difConteos">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Diferencia de Equipos en Áreas</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="f-s-15 f-w-600 text-center">Área</th>
                                    <th class="f-s-15 f-w-600 text-center">Número de Puntos</th>
                                    <th class="f-s-15 f-w-600 text-center">Equipos que deben existir<br />(según el estandar)</th>
                                    <th class="f-s-15 f-w-600 text-center">Equipos censados</th>
                                    <th class="f-s-15 f-w-600 text-center">Faltantes<br />(según el estandar)</th>
                                    <th class="f-s-15 f-w-600 text-center">Sobrantes<br />(según el estandar)</th>
                                    <th class="f-s-15 f-w-600 text-center">Diferencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasFull['censados']) && isset($diferenciasFull['censados']['areas']) && count($diferenciasFull['censados']['areas']) > 0) {
                                    $totalesAreas = [
                                        'puntos' => 0,
                                        'kit' => 0,
                                        'censados' => 0,
                                        'faltantes' => 0,
                                        'sobrantes' => 0,
                                        'diferencias' => 0
                                    ];
                                    foreach ($diferenciasFull['censados']['areas'] as $k => $v) {
                                        $labelF = '<label role="button" data-name="' . $k . '" class="missing-device f-s-15 text-danger">' . ($v['faltantes'] > 0 ? '-' : '') . $v['faltantes'] . '</label>';
                                        $labelS = '<label role="button" data-name="' . $k . '" class="leftover-device f-s-15 text-success">' . ($v['sobrantes'] > 0 ? '+' : '') . $v['sobrantes'] . '</label>';
                                        $diferencia = (int) $v['sobrantes'] - (int) $v['faltantes'];
                                        $labelD = '<label class="f-s-15 ' . ($diferencia >= 0 ? 'text-success' : 'text-danger') . '">' . $diferencia . '</label>';
                                        // $tooltip = '<p>Cada punto del área debe contener: ' . isset($v['TextoKit']) ? $v['TextoKit'] : $v['TextoKit'] . '</p>';
                                        $tooltip = '';

                                        $totalesAreas['puntos'] += (int) $v['puntos'];
                                        $totalesAreas['kit'] += (int) ($v['puntos'] * $v['kit']);
                                        $totalesAreas['censados'] += (int) $v['censados'];
                                        $totalesAreas['faltantes'] += (int) $v['faltantes'];
                                        $totalesAreas['sobrantes'] += (int) $v['sobrantes'];
                                        $totalesAreas['diferencias'] += (int) $diferencia;
                                        echo '
                                        <tr>
                                            <td class="f-s-13">
                                                <button type="button" class="btn btn-secondary"  data-html="true" data-toggle="tooltip" data-placement="top" title="' . $tooltip . '">
                                                    <i class="fa fa-2x fa-info-circle"></i>
                                                </button>
                                                ' . $k . '
                                            </td>                                            
                                            <td class="f-s-15 text-center">' . $v['puntos'] . '</td>
                                            <td class="f-s-15 text-center">' . ($v['puntos'] * $v['kit']) . '</td>
                                            <td class="f-s-15 text-center">' . $v['censados'] . '</td>
                                            <td class="f-s-15 text-center">' . $labelF . '</td>
                                            <td class="f-s-15 text-center">' . $labelS . '</td>
                                            <td class="f-s-15 text-center">' . $labelD . '</td>
                                        </tr>
                                    ';
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <?php
                                echo '
                                <tr>
                                    <th class="f-s-15 f-w-600 text-center">TOTALES</th>
                                    <th class="f-s-15 f-w-600 text-center">' . $totalesAreas['puntos'] . '</th>
                                    <th class="f-s-15 f-w-600 text-center">' . $totalesAreas['kit'] . '</th>
                                    <th class="f-s-15 f-w-600 text-center">' . $totalesAreas['censados'] . '</th>
                                    <th class="f-s-15 f-w-600 text-center text-danger">' . ($totalesAreas['faltantes'] > 0 ? '-' : '') . $totalesAreas['faltantes'] . '</th>
                                    <th class="f-s-15 f-w-600 text-center text-success">' . ($totalesAreas['sobrantes'] > 0 ? '+' : '') . $totalesAreas['sobrantes'] . '</th>
                                    <th class="f-s-15 f-w-600 text-center ' . ($totalesAreas['diferencias'] >= 0 ? 'text-success' : 'text-danger') . '">' . $totalesAreas['diferencias'] . '</th>
                                </tr>
                                ';
                                ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Diferencia de Sublíneas</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sublínea</th>
                                    <th>Equipos que deben existir<br />(basado el estandar)</th>
                                    <th>Equipos Censados</th>
                                    <th>Faltantes<br />(basado en el estandar)</th>
                                    <th>Sobrantes<br />(basado en el estandar)</th>
                                    <th>Diferencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasFull['censados']) && isset($diferenciasFull['censados']['sublineas']) && count($diferenciasFull['censados']['sublineas']) > 0) {
                                    $totalesSublineas = [
                                        'kit' => 0,
                                        'censados' => 0,
                                        'faltantes' => 0,
                                        'sobrantes' => 0,
                                        'diferencias' => 0
                                    ];

                                    foreach ($diferenciasFull['censados']['sublineas'] as $k => $v) {
                                        $labelF = '<label role="button" data-name="' . $k . '" class="missing-device f-s-15 text-danger">' . ($v['faltantes'] > 0 ? '-' : '') . $v['faltantes'] . '</label>';
                                        $labelS = '<label role="button" data-name="' . $k . '" class="leftover-device f-s-15 text-success">' . ($v['sobrantes'] > 0 ? '+' : '') . $v['sobrantes'] . '</label>';
                                        $diferencia = (int) $v['sobrantes'] - (int) $v['faltantes'];
                                        $labelD = '<label class="f-s-15 ' . ($diferencia >= 0 ? 'text-success' : 'text-danger') . '">' . $diferencia . '</label>';
                                        $tooltip = '<p>' . $sublineas[$k]['Linea'] . ($sublineas[$k]['Descripcion'] != '' ? '<br />' . $sublineas[$k]['Descripcion'] : '') . '</p>';

                                        $totalesSublineas['kit'] += (int) $v['kit'];
                                        $totalesSublineas['censados'] += (int) $v['censados'];
                                        $totalesSublineas['faltantes'] += (int) $v['faltantes'];
                                        $totalesSublineas['sobrantes'] += (int) $v['sobrantes'];
                                        $totalesSublineas['diferencias'] += (int) $diferencia;

                                        echo '
                                        <tr>
                                            <td class="f-s-13">
                                                <button type="button" class="btn btn-secondary"  data-html="true" data-toggle="tooltip" data-placement="top" title="' . $tooltip . '">
                                                    <i class="fa fa-2x fa-info-circle"></i>
                                                </button>
                                                ' . $k . '
                                            </td>
                                            <td class="f-s-15 text-center">' . $v['kit'] . '</td>
                                            <td class="f-s-15 text-center">' . $v['censados'] . '</td>
                                            <td class="f-s-15 text-center">' . $labelF . '</td>
                                            <td class="f-s-15 text-center">' . $labelS . '</td>
                                            <td class="f-s-15 text-center">' . $labelD . '</td>
                                        </tr>
                                        ';
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <?php
                                echo '
                                    <tr>
                                        <th class="f-s-15 f-w-600 text-center">TOTALES</th>
                                        <th class="f-s-15 f-w-600 text-center">' . $totalesSublineas['kit'] . '</th>
                                        <th class="f-s-15 f-w-600 text-center">' . $totalesSublineas['censados'] . '</th>
                                        <th class="f-s-15 f-w-600 text-center text-danger">' . ($totalesSublineas['faltantes'] > 0 ? '-' : '') . $totalesSublineas['faltantes'] . '</th>
                                        <th class="f-s-15 f-w-600 text-center text-success">' . ($totalesSublineas['sobrantes'] > 0 ? '+' : '') . $totalesSublineas['sobrantes'] . '</th>
                                        <th class="f-s-15 f-w-600 text-center ' . ($totalesSublineas['diferencias'] >= 0 ? 'text-success' : 'text-danger') . '">' . $totalesSublineas['diferencias'] . '</th>
                                    </tr>
                                ';
                                ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="difSeries">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Equipos que no existen en el censo de <?php echo $generales['FechaUltimo']; ?></h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Punto</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasActual) && count($diferenciasActual) > 0) {
                                    foreach ($diferenciasActual as $k => $v) {
                                        echo '
                                        <tr>
                                            <td>' . $v['Area'] . '</td>
                                            <td>' . $v['Punto'] . '</td>
                                            <td>' . $v['Linea'] . '</td>
                                            <td>' . $v['Sublinea'] . '</td>
                                            <td>' . $v['Marca'] . '</td>
                                            <td>' . $v['Modelo'] . '</td>
                                            <td>' . $v['Serie'] . '</td>
                                        </tr>
                                        ';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4>Equipos que no existen en el censo de <?php echo $generales['Fecha']; ?> (Actual)</h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Punto</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasUltimo) && count($diferenciasUltimo) > 0) {
                                    foreach ($diferenciasUltimo as $k => $v) {
                                        echo '
                                        <tr>
                                            <td>' . $v['Area'] . '</td>
                                            <td>' . $v['Punto'] . '</td>
                                            <td>' . $v['Linea'] . '</td>
                                            <td>' . $v['Sublinea'] . '</td>
                                            <td>' . $v['Marca'] . '</td>
                                            <td>' . $v['Modelo'] . '</td>
                                            <td>' . $v['Serie'] . '</td>
                                        </tr>
                                        ';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="cambiosSeries">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Equipos que posiblemente cambiaron de tener Serie a ser ILEGIBLE</h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Punto</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie Anterior</th>
                                    <th>Serie Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($cambiosSerie) && count($cambiosSerie) > 0) {
                                    foreach ($cambiosSerie as $k => $v) {
                                        echo '
                                        <tr>
                                            <td>' . $v['Area'] . '</td>
                                            <td>' . $v['Punto'] . '</td>
                                            <td>' . $v['Linea'] . '</td>
                                            <td>' . $v['Sublinea'] . '</td>
                                            <td>' . $v['Marca'] . '</td>
                                            <td>' . $v['Modelo'] . '</td>
                                            <td>' . $v['Serie'] . '</td>
                                            <td>ILEGIBLE</td>
                                        </tr>
                                        ';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="difFaltantes">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Equipos que faltan basado en el Kit Estandar de Área</h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <table id="missing-devices-table" class="table table-bordered counting-table">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Punto</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasFull['faltantes']) && count($diferenciasFull['faltantes']) > 0) {
                                    foreach ($diferenciasFull['faltantes'] as $k => $v) {
                                        echo '
                                        <tr>
                                            <td>' . $v['Area'] . '</td>
                                            <td>' . $v['Punto'] . '</td>
                                            <td>' . $v['Linea'] . '</td>
                                            <td>' . $v['Sublinea'] . '</td>
                                            <td>' . $v['Cantidad'] . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="difSobrantes">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Equipos que sobran basado en el Kit Estandar de Área</h4>
                        <div class="underline"></div>
                    </div>
                </div>
                <div class="row m-t-15">
                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                        <table id="leftover-devices-table" class="table table-bordered counting-table">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Punto</th>
                                    <th>Línea</th>
                                    <th>Sublínea</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($diferenciasFull['sobrantes']) && count($diferenciasFull['sobrantes']) > 0) {
                                    foreach ($diferenciasFull['sobrantes'] as $k => $v) {
                                        echo '
                                        <tr>
                                            <td>' . $v['Area'] . '</td>
                                            <td>' . $v['Punto'] . '</td>
                                            <td>' . $v['Linea'] . '</td>
                                            <td>' . $v['Sublinea'] . '</td>
                                            <td>' . $v['Marca'] . '</td>
                                            <td>' . $v['Modelo'] . '</td>
                                            <td>' . $v['Serie'] . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="Pruebas">
                <pre>
                <?php
                var_dump($diferenciasFull['censados']['sublineas']);
                ?>
                </pre>
            </div>
        </div>

    </div>
</div>