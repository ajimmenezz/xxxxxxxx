<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table id="updateSOInfoTable" class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center never">IdCenso</th>
                    <th class="text-center all">Terminal</th>
                    <th class="text-center none">Área de Atención</th>
                    <th class="text-center none">Punto</th>
                    <th class="text-center none">Línea</th>
                    <th class="text-center all">Sublínea</th>
                    <th class="text-center none">Marca</th>
                    <th class="text-center all">Modelo</th>
                    <th class="text-center none">Serie</th>
                    <th class="text-center never">IdRegistroActualizacion</th>
                    <th class="text-center all">¿Carga imagen?</th>
                    <?php
                    if (isset($impediments) && count($impediments) > 0) {
                        foreach ($impediments as $k => $v) {
                            echo '<th class="text-center all text-danger f-w-600">' . $v['Nombre'] . '</th>';
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($updateSOInfo) && count($updateSOInfo) > 0) {
                    foreach ($updateSOInfo as $k => $v) {
                        $updated = ($v['Actualizado'] == 1) ? 'checked' : '';

                        echo '
                        <tr>
                        <td>' . $v['Id'] . '</td>
                        <td>' . $v['Terminal'] . '</td>
                        <td>' . $v['Area'] . '</td>
                        <td>' . $v['Punto'] . '</td>
                        <td>' . $v['Linea'] . '</td>
                        <td>' . $v['Sublinea'] . '</td>
                        <td>' . $v['Marca'] . '</td>
                        <td>' . $v['Modelo'] . '</td>
                        <td>' . $v['Serie'] . '</td>
                        <td>' . $v['IdRegistroActualizacion'] . '</td>
                        <td class="text-center">
                            <input 
                                role="button"
                                data-id="' . $v['Id'] . '" 
                                data-id-registry="' . $v['IdRegistroActualizacion'] . '"
                                class="updateCheck updateCheck_' . $v['Id'] . '" 
                                type="checkbox" 
                                style="transform:scale(2, 2)" ' . $updated . ' />
                            </td>
                        ';
                        if (isset($impediments) && count($impediments) > 0) {
                            foreach ($impediments as $kk => $vv) {
                                $checked = '';
                                if (isset($updateSOImpediments[$v['IdRegistroActualizacion']])) {
                                    if (in_array($vv['Id'], $updateSOImpediments[$v['IdRegistroActualizacion']])) {
                                        $checked = 'checked';
                                    }
                                }
                                echo '
                                <td class="text-center">
                                    <input 
                                        role="button"
                                        data-id="' . $v['Id'] . '" 
                                        data-impediment-id="' . $vv['Id'] . '"
                                        class="impedimentCheck impedimentCheck_' . $v['Id'] . '" 
                                        type="checkbox" 
                                        style="transform:scale(2, 2)" ' . $checked . ' />
                                </td>';
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row m-t-15">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <a id="saveSOUpdateInfo" role="button" class="btn btn-success f-w-600 f-s-13 p-10">Guardar Captura</a>
        </p>
    </div>
</div>