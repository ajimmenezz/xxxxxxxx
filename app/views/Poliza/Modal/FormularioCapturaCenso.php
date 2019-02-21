<div class="row">
    <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">      
        <a class="btn btn-danger btnCancelarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Cancelar</a>
        <a class="btn btn-success btnGuardarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Guardar</a>
    </div>
</div>
<div class="row">
    <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">          
        <h4 class="text-center f-w-600 f-s-20"><?php echo $nombreArea . ' ' . $datosGenerales['punto']; ?></h4>        
        <div class="underline"></div>
    </div>
</div>

<?php
$arrayEquiposCensados = $equiposCensados;

foreach ($kitStandarArea as $key => $value) {
    $title = ($value['Linea'] == $value['Sublinea']) ? ucwords(mb_strtolower($value['Sublinea'])) : ucwords(mb_strtolower($value['Sublinea'])) . ' (' . ucwords(mb_strtolower($value['Linea'])) . ') ';
    ?>
    <div class="row m-t-10">
        <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">            
            <h5 class="f-w-600"><?php echo $title; ?></h5>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">            
            <div class="table-responsive">
                <table class="table table-condensed table-bordered">   
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Modelo</th>
                            <th class="text-center">Serie</th>
                            <th class="text-center">¿Ilegible?</th>
                            <th class="text-center">Existe</th>
                            <th class="text-center">Dañado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 1; $i <= $value['Cantidad']; $i++) {
                            $optionsModelos = '';
                            $serie = '';
                            $checkedIlegible = '';
                            $checkedExiste = '';
                            $checkedDanado = '';
                            $existe = false;
                            foreach ($modelosStandar as $k => $v) {
                                if ($value['IdSublinea'] == $v['Sublinea']) {
                                    $selected = '';
                                    foreach ($arrayEquiposCensados as $keyEquiposCensados => $valueEquiposCensados) {
                                        if ($valueEquiposCensados['IdModelo'] == $v['Id']) {
                                            $selected = 'selected';
                                            $serie = $valueEquiposCensados['Serie'];
                                            $checkedIlegible = ($serie == 'ILEGIBLE') ? 'checked="checked"' : '';
                                            $checkedExiste = ($valueEquiposCensados['Existe'] == 1 ) ? 'checked="checked"' : 0;
                                            $checkedDanado = ($valueEquiposCensados['Danado'] == 1 ) ? 'checked="checked"' : 0;
                                            $existe = true;
                                            unset($arrayEquiposCensados[$keyEquiposCensados]);
                                            break;
                                        }
                                    }
                                    $optionsModelos .= '<option value="' . $v['Id'] . '" ' . $selected . '>' . $v['Marca'] . ' ' . $v['Modelo'] . '</option>';
                                }
                            }
                            $disabled = ($existe) ? '' : 'disabled="disabled"';
                            $border = ($existe) ? 'table-border-green' : 'table-border-red"';
                            ?>
                            <tr class="<?php echo $border; ?>">
                                <td># <?php echo $i; ?></td>
                                <td>
                                    <select class="form-control" <?php echo $disabled; ?>>
                                        <option value="">Seleccionar . . .</option>  
                                        <?php echo $optionsModelos; ?>
                                    </select>
                                </td>
                                <td>
                                    <input role="button" type="text" class="form-control" value="<?php echo $serie; ?>" placeholder="XXXYYYZZZ-123" <?php echo $disabled; ?>/>
                                </td>
                                <td class="text-center">
                                    <input role="button" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedIlegible . ' ' . $disabled; ?> />
                                </td>
                                <td class="text-center">
                                    <input role="button" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedExiste; ?> />
                                </td>
                                <td class="text-center">
                                    <input role="button" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedDanado . ' ' . $disabled; ?> />
                                </td>
                            </tr>
                            <?php
                        }
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">      
        <a class="btn btn-danger btnCancelarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Cancelar</a>
        <a class="btn btn-success btnGuardarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Guardar</a>
    </div>
</div>

