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
