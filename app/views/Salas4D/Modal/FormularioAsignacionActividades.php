<div class="row" id="AsigActividades">
    <div class="col-md-12">
        <div id="errorDatosActividadesMantenimiento"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Asignacion de Actividades</h3>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-xs 12">
        <div class="panel-group" id="accordion">
            
            <!--<div class="panel panel-inverse overflow-hidden">-->
            <?php
            $arraySistemas = [];
            foreach ($actividades as $k => $valor) {
                if (!in_array($valor['IdSistema'], $arraySistemas)) {
                    array_push($arraySistemas, $valor['IdSistema']);
                }
            }
            foreach ($sistemas as $key => $value) {
                if (!in_array($value['Id'], $arraySistemas)) {
                    continue;
                }
                ?>
                <div class="panel panel-inverse overflow-hidden panel-sistema">
                    <div class="panel-heading m-t-15"> 

                        <h3 class="panel-title">
                            <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion" href=#collapse_<?php echo $value['Id']; ?>>
                                <i class="fa fa-plus-circle pull-right"></i>
                                <?php
                                echo $value['Nombre'];
                                ?>
                            </a>
                        </h3>
                    </div>

                    <!--Finalizando Error-->

                    <div id="collapse_<?php echo $value['Id']; ?>" class="panel-collapse collapse in">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-actividades">
                                        <thead>
                                            <tr>
                                                <th class="all">Actividad</th>
                                                <th class="all">Actividad Padre</th>
                                                <th class="all">Estatus</th>
                                                <th class="all">Atiende</th>
                                                <th class="all"></th>
                                                <th class="all"></th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <?php
                                            $arrayActividades = [];
                                            foreach ($actividades as $k => $v) {
                                                if ($v['IdSistema'] == $value['Id']) {
                                                    
                                                    foreach ($idPadreAct as $i => $p) {
                                                        
                                                        if ($p == $v['Id']) {
                                                            if(in_array($v['Id'], $arrayActividades)){
                                                                continue;
                                                            }else{
                                                                array_push($arrayActividades,$v['Id']);
                                                            }
                                                            echo ""
                                                                . "<tr>"
                                                                . " <td>".$v['Actividad'] . "</td>"
                                                                . " <td>" . $v['ActividadPadre'] . "</td>"
                                                                . " <td class='text-center'>" . $v['Estatus'] . "</td>"
                                                                . " <td>";
                                                            
                                                            if (in_array($v['IdEstatus'], [1, 2])) {
                                                                echo""
                                                                . " <select id='list_usuarios_" . $v['Id'] . "' class='form-control' style='width: 100%'>"
                                                                . "     <option value=''>Seleccionar . . .</option>";
                                                                foreach ($usuarios as $ku => $vu) {
                                                                    $selected = ($v['IdAtiende'] == $vu['Id']) ? "selected" : "";
                                                                    echo "<option data-servicio='" . $idServicio . "' data-estatus='" . $v['IdEstatus'] . "' value='" . $vu['Id'] . "' " . $selected . ">" . $vu['Nombre'] . "</option>";
                                                                }
                                                                echo ""
                                                                . " </select>"
                                                                . "</td>"
                                                                . "<td class='text-center'><i role='button' data-guardar-actividad='" . $v['Id'] . "' class='fa fa-2x fa-floppy-o btn-guardar-actividad' aria-hidden='true'></i></td>"
                                                                . "<td class='text-center'><i class='fa fa-2x fa-info-circle mostrar-informe' data-informe='" . $v['Id'] . "' data-servicio = '". $idServicio ."' data-actividad ='".$v['IdManttoActividades']."' aria-hidden='true'></i></td>"
                                                                . "</tr>";
                                                            } else {
                                                                foreach ($usuarios as $ku => $vu) {
                                                                    if ($v['IdAtiende'] == $vu['Id']) {
                                                                        echo $v['NombreAtiende'];
//                                                             var_dump($v);
                                                                    }
                                                                }

                                                                echo""
                                                                . "<td class='text-center'><i id='list_usuarios_" . $v['Id'] . "' data-servicio='" . $idServicio . "' data-estatus='" . $v['IdEstatus'] . "' data-id-actividad='" . $v['Id'] . "' data-atiende ='" . $v['IdAtiende'] . "' role='button' class='fa fa-2x fa-unlock btn-reabrir-actividad' aria-hidden='true')></i></td>"
                                                                . "<td class='text-center'><i class='fa fa-2x fa-info-circle mostrar-informe' data-informe='" . $v['Id'] . "' data-servicio = '". $idServicio ."' data-actividad ='".$v['IdManttoActividades']."' aria-hidden='true'></i></td>"
                                                                . "</tr>";
                                                            }
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
                <?php
            }
            ?>
            <!--</div>-->
        </div>
    </div>
</div>
<div id="informacion-actividades" style="display:none"></div>
