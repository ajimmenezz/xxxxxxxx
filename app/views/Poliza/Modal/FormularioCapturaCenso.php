<?php
$totalEquipmentStandarKit = count($kitStandarArea);
$titleAnotherEquipment = 'Equipos Adicionales';
if ($totalEquipmentStandarKit <= 0) {
    $titleAnotherEquipment = 'Equipos del punto';
}
?>

<ul class="nav nav-pills">
    <?php
    if ($totalEquipmentStandarKit > 0) {
        $classAnotherEquipmentPill = '';
        $classAnotherEquipmentContent = '';
        ?>
        <li class="active"><a href="#nav-pill-kit-standar" data-toggle="tab">Kit Estandar de Área</a></li>        
        <?php
    } else {
        $classAnotherEquipmentPill = 'active';
        $classAnotherEquipmentContent = 'active in';
    }
    ?>
    <li class="<?php echo $classAnotherEquipmentPill; ?>"><a href="#nav-pill-equipos-adicionales" data-toggle="tab"><?php echo $titleAnotherEquipment; ?></a></li>
</ul>

<div class="tab-content">
    <?php
    $arrayEquiposCensados = $equiposCensados;
    if ($totalEquipmentStandarKit > 0) {
        ?>
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
        <?php
    }
    ?>
    <div class="tab-pane fade <?php echo $classAnotherEquipmentContent; ?>" id="nav-pill-equipos-adicionales">        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 shadow-box">
                <div class="table-responsive m-t-20 m-b-20">
                    <table class="table table-condensed table-bordered">                           
                        <tr>
                            <th colspan="6" class="text-center">AGREGAR NUEVO EQUIPO</th>
                        </tr>
                        <tr>                                
                            <th colspan="2" class="text-center">Modelo</th>
                            <th class="text-center">Serie</th>
                            <th class="text-center">Ilegible</th>                                
                            <?php
                            if ($cliente == 1) {
                                ?>
                                <th class="text-center">Dañado</th>
                                <?php
                            }
                            ?>                                
                            <th class="text-center"></th>     
                        </tr>
                        <tr>
                            <td colspan="2">
                                <select class="form-control" id="listModelosEquipoAdicional" style="min-width: 230px">
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
                                <input role="button" type="text" id="txtSerieEquipoAdicional" class="form-control" value="" placeholder="XXXYYYZZZ-123"  style="min-width: 150px"/>
                            </td>                        
                            <td class="text-center">
                                <input role="button" id="checkIlegibleEquipoAdicional" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" />
                            </td>
                            <?php
                            if ($cliente == 1) {
                                ?>
                                <td class="text-center">
                                    <input role="button" id="checkDanadoEquipoAdicional" class="m-t-10" style="transform:scale(2, 2);" type="checkbox" />
                                </td>
                                <?php
                            }
                            ?>
                            <td <?php echo ($cliente == 20) ? 'rowspan="3"' : ''; ?> class="text-center">
                                <a id="btnAgregarEquipoAdicional" class="btn btn-success <?php echo ($cliente == 20) ? 'm-t-40' : ''; ?>"><i class="fa fa-plus-circle"></i></a>
                            </td>
                        </tr>   
                        <?php
                        if ($cliente == 20) {
                            ?>
                            <tr>                                
                                <th class="text-center">Etiqueta</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">MAC Address</th>
                                <th class="text-center">S.O.</th>                                         
                            </tr>
                            <tr>
                                <td>
                                    <input role="button" type="text" id="txtEtiquetaEquipoAdicional" class="form-control" value="" placeholder="001-TOL003-97-0108" style="min-width: 200px"/>
                                </td>                                
                                <td>
                                    <select class="form-control" id="listEstadosEquipoAdicional" style="min-width: 130px">
                                        <option value="">Seleccionar . . .</option>         
                                        <?php
                                        if (isset($estatus) && count($estatus) > 0) {
                                            foreach ($estatus as $keyEstatus => $valueEstatus) {
                                                echo '<option value="' . $valueEstatus['Id'] . '">' . $valueEstatus['Nombre'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input role="button" type="text" id="txtMACEquipoAdicional" class="form-control" value="" placeholder="00:11:22:33:44:55" style="min-width: 130px" disabled="disabled"/>
                                </td>
                                <td>
                                    <select class="form-control" id="listSOEquipoAdicional" style="min-width: 120px" disabled="disabled">
                                        <option value="">Seleccionar . . .</option>         
                                        <?php
                                        if (isset($so) && count($so) > 0) {
                                            foreach ($so as $keySO => $valueSO) {
                                                echo '<option value="' . $valueSO['Id'] . '">' . $valueSO['Nombre'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <div class="m-t-20 divErrorEquipoAdicional"></div>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-offset-1 col-md-10 col-sm-offset-0 col-sm-12 col-xs-offset-0 col-xs-12">          
                <h4 class="text-center f-w-600 f-s-20"><?php echo $titleAnotherEquipment . ' ' . $nombreArea . ' ' . $datosGenerales['punto']; ?></h4>        
                <div class="underline"></div>
            </div>
        </div>
        <div class="row m-t-15">
            <div class="col-md-12 col-sm-12 col-xs-12">            
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered">   
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Modelo</th>
                                <th class="text-center">Serie</th>
                                <th class="text-center">Ilegible</th>   
                                <?php
                                if ($cliente == 1) {
                                    ?>
                                    <th class="text-center">Dañado</th>
                                    <?php
                                }
                                ?>
                                <th class="text-center">Etiqueta</th>   
                                <th class="text-center">Estatus</th>   
                                <th class="text-center">MAC Address</th>   
                                <th class="text-center">S.O.</th>   
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
                                    <?php
                                    if ($cliente == 1) {
                                        ?>
                                        <td class="text-center">
                                            <input role="button" class="m-t-10 danadoEquiposAdicionales" style="transform:scale(2, 2);" type="checkbox" <?php echo $checkedDanado; ?> />
                                        </td>
                                        <?php
                                    }
                                    ?>
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

