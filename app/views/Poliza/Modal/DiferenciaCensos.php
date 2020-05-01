<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="f-s-15 f-w-600 text-center" colspan="3"><?php echo $generales['Sucursal']; ?></th>
                </tr>
                <tr>
                    <th class="f-s-15 f-w-600 text-center">Censo<br /><?php echo $generales['FechaUltimo']; ?></th>
                    <th class="f-s-15 f-w-600 text-center">Censo<br /><?php echo $generales['Fecha']; ?></th>
                    <th class="f-s-15 f-w-600 text-center">Diferencia<br />de Equipos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="f-s-15 f-w-600 text-center"><?php echo count($ultimo); ?></td>
                    <td class="f-s-15 f-w-600 text-center"><?php echo count($actual); ?></td>
                    <td class="f-s-15 f-w-600 text-center"><?php echo '<label class="f-s-15 f-w-600 ' . ($conteo > 0 ? 'text-success' : 'text-danger') . '">' . ($conteo > 0 ? '+' : '') . $conteo . '</label>' ?></td>
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
            <li <?php echo $active; ?>><a href="#difConteos" data-toggle="tab">Diferencias (Conteos)</a></li>
            <li><a href="#difSeries" data-toggle="tab">Diferencias (Series)</a></li>
            <li><a href="#cambiosSeries" data-toggle="tab">Cambios Serie</a></li>
            <li><a href="#difFaltantes" data-toggle="tab">Faltantes</a></li>
            <li><a href="#difSobrantes" data-toggle="tab">Sobrantes</a></li>
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
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>Diferencia de Puntos x Área</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Área</th>
                                    <th>Total de Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                if (isset($diferenciaAreas) && count($diferenciaAreas) > 0) {
                                    foreach ($diferenciaAreas as $k => $v) {
                                        if ($v !== 0) {
                                            $label = '<label class="f-s-15 ' . ($v > 0 ? 'text-success' : 'text-danger') . '">' . ($v > 0 ? '+' . $v : $v) . '</label>';
                                            echo '
                            <tr>
                                <td>' . $k . '</td>
                                <td>' . $label . '</td>
                            </tr>
                            ';
                                            $c++;
                                        }
                                    }
                                }
                                if ($c <= 0) {
                                    echo '
                        <tr><td colspan="2">Sin diferencias encontradas</td></tr>
                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>Diferencia de Líneas</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Línea</th>
                                    <th>Total de Equipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                if (isset($diferenciaLineas) && count($diferenciaLineas) > 0) {
                                    foreach ($diferenciaLineas as $k => $v) {
                                        if ($v !== 0) {
                                            $label = '<label class="f-s-15 ' . ($v > 0 ? 'text-success' : 'text-danger') . '">' . ($v > 0 ? '+' . $v : $v) . '</label>';
                                            echo '
                            <tr>
                                <td>' . $k . '</td>
                                <td>' . $label . '</td>
                            </tr>
                            ';
                                            $c++;
                                        }
                                    }
                                }
                                if ($c <= 0) {
                                    echo '
                        <tr><td colspan="2">Sin diferencias encontradas</td></tr>
                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>Diferencia de Sublíneas</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sublínea</th>
                                    <th>Total de Equipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                if (isset($diferenciaSublineas) && count($diferenciaSublineas) > 0) {
                                    foreach ($diferenciaSublineas as $k => $v) {
                                        if ($v !== 0) {
                                            $label = '<label class="f-s-15 ' . ($v > 0 ? 'text-success' : 'text-danger') . '">' . ($v > 0 ? '+' . $v : $v) . '</label>';
                                            echo '
                            <tr>
                                <td>' . $k . '</td>
                                <td>' . $label . '</td>
                            </tr>
                            ';
                                            $c++;
                                        }
                                    }
                                }
                                if ($c <= 0) {
                                    echo '
                        <tr><td colspan="2">Sin diferencias encontradas</td></tr>
                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h4>Diferencia de Modelos</h4>
                        <div class="underline"></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Modelo</th>
                                    <th>Total de Equipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                if (isset($diferenciaModelos) && count($diferenciaModelos) > 0) {
                                    foreach ($diferenciaModelos as $k => $v) {
                                        if ($v !== 0) {
                                            $label = '<label class="f-s-15 ' . ($v > 0 ? 'text-success' : 'text-danger') . '">' . ($v > 0 ? '+' . $v : $v) . '</label>';
                                            echo '
                            <tr>
                                <td>' . $k . '</td>
                                <td>' . $label . '</td>
                            </tr>
                            ';
                                            $c++;
                                        }
                                    }
                                }
                                if ($c <= 0) {
                                    echo '
                        <tr><td colspan="2">Sin diferencias encontradas</td></tr>
                    ';
                                }
                                ?>
                            </tbody>
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
                        <table class="table table-bordered">
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
                                if (isset($diferenciasKit['faltantes']) && count($diferenciasKit['faltantes']) > 0) {
                                    foreach ($diferenciasKit['faltantes'] as $kArea => $vArea) {
                                        foreach ($vArea as $kPunto => $vPunto) {
                                            foreach ($vPunto as $k => $v) {
                                                echo '
                                                <tr>
                                                    <td>' . $v['Area'] . '</td>
                                                    <td>' . str_replace("P", "", $kPunto) . '</td>
                                                    <td>' . $v['Linea'] . '</td>
                                                    <td>' . $v['Sublinea'] . '</td>
                                                    <td>' . $v['Cantidad'] . '</td>
                                                </tr>';
                                            }
                                        }
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
                                if (isset($diferenciasKit['sobrantes']) && count($diferenciasKit['sobrantes']) > 0) {
                                    foreach ($diferenciasKit['sobrantes'] as $kArea => $vArea) {
                                        foreach ($vArea as $kPunto => $vPunto) {
                                            foreach ($vPunto as $k => $v) {
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
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>