<ul class="nav nav-pills">
    <li class="active"><a href="#nav-pill-kit-standar" data-toggle="tab">Kit Estandar de Área</a></li>
    <li><a href="#nav-pill-equipos-adicionales" data-toggle="tab">Equipos Adicionales</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade active in" id="nav-pill-kit-standar">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">      
                <a class="btn btn-danger btnCancelarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Cancelar</a>
                <a class="btn btn-success btnGuardarCapturaCenso pull-right m-r-10 m-l-10 f-s-14 f-w-600">Guardar</a>
            </div>
        </div>
        <div class="row m-t-10">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">          
                <div class="divErrorCapturaCensoEstandar"></div>
            </div>
        </div>
        <div class="row m-t-10">
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
                                    <th class="text-center">Ilegible</th>
                                    <th class="text-center">Existe</th>
                                    <th class="text-center">Dañado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 1; $i <= $value['Cantidad']; $i++) {
                                    $usado = false;
                                    $idEquipoCensado = 0;
                                    $optionsModelos = '';
                                    $serie = '';
                                    $checkedIlegible = '';
                                    $checkedExiste = '';
                                    $checkedDanado = '';
                                    $disabledSerie = '';
                                    $existe = false;
                                    foreach ($modelosStandar as $k => $v) {
                                        if ($value['IdSublinea'] == $v['Sublinea']) {
                                            $selected = '';
                                            if (!$usado) {
                                                foreach ($arrayEquiposCensados as $keyEquiposCensados => $valueEquiposCensados) {
                                                    if ($valueEquiposCensados['IdModelo'] == $v['Id']) {
                                                        $idEquipoCensado = $valueEquiposCensados['Id'];
                                                        $selected = 'selected';
                                                        $serie = $valueEquiposCensados['Serie'];
                                                        $checkedIlegible = ($serie == 'ILEGIBLE') ? 'checked="checked"' : '';
                                                        $checkedExiste = ($valueEquiposCensados['Existe'] == 1 ) ? 'checked="checked"' : 0;
                                                        $checkedDanado = ($valueEquiposCensados['Danado'] == 1 ) ? 'checked="checked"' : 0;
                                                        $disabledSerie = ($checkedIlegible !== '') ? 'disabled="disabled"' : '';
                                                        $existe = true;
                                                        unset($arrayEquiposCensados[$keyEquiposCensados]);
                                                        $usado = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            $optionsModelos .= '<option value="' . $v['Id'] . '" ' . $selected . '>' . $v['Marca'] . ' ' . $v['Modelo'] . '</option>';
                                        }
                                    }
                                    $disabled = ($existe) ? '' : 'disabled="disabled"';
                                    $border = ($existe) ? 'table-border-green' : 'table-border-red';
                                    $tipoRegistro = ($existe) ? 'registroActivo' : ' registroNuevo';
                                    ?>
                                    <tr data-id="<?php echo $idEquipoCensado; ?>" class="registroEquiposEstandar <?php echo $border . ' ' . $tipoRegistro; ?>">
                                        <td># <?php echo $i; ?></td>
                                        <td>
                                            <select class="form-control listModelosEstandar" <?php echo $disabled; ?>>
                                                <option value="">Seleccionar . . .</option>  
                                                <?php echo $optionsModelos; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input role="button" type="text" class="form-control serieModelosEstandar" value="<?php echo $serie; ?>" placeholder="XXXYYYZZZ-123" <?php echo $disabled . ' ' . $disabledSerie; ?>/>
                                        </td>
                                        <td class="text-center">
                                            <input role="button" class="m-t-10 ilegibleModelosEstandar" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedIlegible . ' ' . $disabled; ?> />
                                        </td>
                                        <td class="text-center">
                                            <input role="button" class="m-t-10 existeModelosEstandar" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedExiste; ?> />
                                        </td>
                                        <td class="text-center">
                                            <input role="button" class="m-t-10 danadoModelosEstandar" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedDanado . ' ' . $disabled; ?> />
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
    </div>
    <div class="tab-pane fade" id="nav-pill-equipos-adicionales">        
        <div class="row">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12 shadow-box">
                <div class="table-responsive m-t-20 m-b-20">
                    <table class="table table-condensed table-bordered">   
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">AGREGAR NUEVO EQUIPO</th>
                            </tr>
                            <tr>                                
                                <th class="text-center">Modelo</th>
                                <th class="text-center">Serie</th>
                                <th class="text-center">Ilegible</th>                                
                                <th class="text-center">Dañado</th>
                                <th class="text-center"></th>     
                            </tr>
                        </thead>
                        <tbody>
                        <td>
                            <select class="form-control" id="listModelosEquipoAdicional">
                                <option value="">Seleccionar . . .</option>         
                                <?php
                                if (isset($modelos) && count($modelos) > 0) {
                                    foreach ($modelos as $keyModelos => $valueModelos) {
                                        echo '<option value="' . $valueModelos['Id'] . '">' . $valueModelos['Modelo'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input role="button" type="text" id="txtSerieEquipoAdicional" class="form-control" value="" placeholder="XXXYYYZZZ-123"/>
                        </td>
                        <td class="text-center">
                            <input role="button" id="checkIlegibleEquipoAdicional" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" />
                        </td>                                    
                        <td class="text-center">
                            <input role="button" id="checkDanadoEquipoAdicional" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" />
                        </td>
                        <td class="text-center">
                            <a id="btnAgregarEquipoAdicional" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                        </td>
                        </tbody>
                    </table>
                    <div class="m-t-20 divErrorEquipoAdicional"></div>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">          
                <h4 class="text-center f-w-600 f-s-20">ADICIONALES EN <?php echo $nombreArea . ' ' . $datosGenerales['punto']; ?></h4>        
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-15">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">            
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered">   
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Modelo</th>
                                <th class="text-center">Serie</th>
                                <th class="text-center">Ilegible</th>                                
                                <th class="text-center">Dañado</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($arrayEquiposCensados as $keyEquiposCensados => $valueEquiposCensados) {
                                $i++;
                                $serie = $valueEquiposCensados['Serie'];
                                $checkedIlegible = ($serie == 'ILEGIBLE') ? 'checked="checked"' : '';
                                $disabledSerie = ($checkedIlegible !== '') ? 'disabled="disabled"' : '';
                                $checkedDanado = ($valueEquiposCensados['Danado'] == 1 ) ? 'checked="checked"' : 0;
                                unset($arrayEquiposCensados[$keyEquiposCensados]);
                                ?>
                                <tr data-id="<?php echo $valueEquiposCensados['Id']; ?>" class="registrosAdicionales">
                                    <td># <?php echo $i; ?></td>
                                    <td>
                                        <select class="form-control listModelosEquiposAdicionales">
                                            <option value="">Seleccionar . . .</option>         
                                            <?php
                                            if (isset($modelos) && count($modelos) > 0) {
                                                foreach ($modelos as $keyModelos => $valueModelos) {
                                                    $selected = ($valueModelos['Id'] == $valueEquiposCensados['IdModelo']) ? 'selected' : '';
                                                    echo '<option value="' . $valueModelos['Id'] . '" ' . $selected . '>' . $valueModelos['Modelo'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input role="button" type="text" class="form-control serieEquiposAdicionales" value="<?php echo $serie; ?>" placeholder="XXXYYYZZZ-123" <?php echo $disabledSerie; ?>/>
                                    </td>
                                    <td class="text-center">
                                        <input role="button" class="m-t-10 ilegibleEquiposAdicionales" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedIlegible; ?> />
                                    </td>                                    
                                    <td class="text-center">
                                        <input role="button" class="m-t-10 danadoEquiposAdicionales" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedDanado; ?> />
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-info btnGuardarCambiosEquiposAdicionalesCenso"><i class="fa fa-save"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <a data-id="<?php echo $valueEquiposCensados['Id']; ?>" class="btn btn-danger btnEliminarEquiposAdicionalesCenso"><i class="fa fa-trash"></i></a>
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
    </div>
</div>

