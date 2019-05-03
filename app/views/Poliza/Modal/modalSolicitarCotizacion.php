<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="radio">
                <label>
                    <input type="radio" name="cotizacion" value="1" checked/>
                    Equipo Completo
                </label>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="radio">
                <label>
                    <input type="radio" name="cotizacion" value="2"/>
                    Componentes
                </label>
            </div>
        </div>

        <div id="equipoCompleto">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                <label><?php echo $infoEquipo[0]['Equipo']?></label>
                </div>
            </div>
        </div>

        <div id="componentes" class="hidden">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                <?php
                if (empty($componentes)) {
                    echo '<label>No hay componentes para este equipo</label>';
                } else {
                    echo '<div class="table-responsive">
                    <table id="data-table-solicitar-componentes" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Nombre</th>
                                <th class="all">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $index = 0;
                            foreach ($componentes as $value) {
                                echo '<tr>';
                                    echo '<td>'.$value['Nombre'].'</td>';
                                    echo '<td>
                                            <input type="number" class="form-control" id="inputCantidad'.$index.'" class="chk" style="width: 50%" value="0"/>
                                        </td>';
                                echo '</tr>';
                                $index++;
                            }
                        echo '</tbody>
                    </table>
                </div>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-10">
        <div class="col-md-12">
            <div id="errorModalSolicitarCotizacion"></div>
        </div>
    </div>
</div>
