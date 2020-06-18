<div class="panel-body">
    <!-- begin timeline -->
    <ul id="listaHistorial" class="timeline">
        <?php
        if (!empty($avanceServicio)) {
            foreach ($avanceServicio as $key => $item) {
                $arrayArchivos = explode(",", $item['Archivos']);
                $arrayFecha = explode(' ', $item['Fecha']);
                ?>
                <li>

                    <!-- begin timeline-time -->
                    <div class="timeline-time">
                        <span class="date"><?php echo $arrayFecha[0] ?></span>
                        <span class="time"><?php echo $arrayFecha[1] ?></span>
                    </div>
                    <!-- end timeline-time -->

                    <!-- begin timeline-icon -->
                    <div class="timeline-icon">
                        <?php (($item['IdTipo'] === '1')) ? $icono = 'fa fa-check' : $icono = 'fa fa-ban'; ?>
                        <?php (($item['IdTipo'] === '1')) ? $colorIcono = '' : $colorIcono = 'background:#ff5b57'; ?>
                        <a href="javascript:;" style="<?php echo $colorIcono ?>"><i class="<?php echo $icono ?>"></i></a>
                    </div>
                    <!-- end timeline-icon -->

                    <!-- begin timeline-body -->
                    <div class="timeline-body">
                        <div class="timeline-header">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <?php (empty($item['Foto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $item['Foto']; ?>
                                    <span class="userimage"><img src="<?php echo $foto ?>" alt="" /></span>
                                    <span class="username"><?php echo $item['Usuario'] ?></span>
                                </div>
                                <div class="col-md-3 col-xs-3 text-right">
                                    <?php (($item['IdTipo'] === '1')) ? $colorTituloAvance = 'color:#337ab7' : $colorTituloAvance = 'color:#ff5b57'; ?>
                                    <span class="pull-right text-muted "><h4 style="<?php echo $colorTituloAvance ?>"><?php echo $item['TipoAvance'] ?></h4></span>
                                </div>
                                <div class="col-md-3 col-xs-3 text-right seccion-botones-problema">
                                    <label class="btnEditarAvanceSeguimientoSinEspecificar btn btn-primary btn-xs" data-id="<?php echo $item['Id'] ?>" >
                                        <i class="fa fa-pencil"></i> Editar
                                    </label>  
                                    <label class="btnEliminarAvanceSeguimientoSinEspecificar btn btn-danger btn-xs" data-id="<?php echo $item['Id'] ?>" >
                                        <i class="fa fa-trash-o"></i> Eliminar
                                    </label>  
                                </div>
                            </div>
                        </div>
                        <div class="timeline-content">
                            <p>
                                <?php echo $item['Descripcion'] ?>
                            </p>
                        </div>
                        <div class="row">
                            <?php
                            $htmlArchivos = '';
                            foreach ($arrayArchivos as $key => $value) {
                                $htmlArchivos .= ''
                                        . '<div class="col-md-4 m-t-15">  '
                                        . '<div style="display: inline-block; padding: 10px; box-shadow: 3px 3px 0.5em;">'
                                        . ' <a class="m-l-5 m-r-5"'
                                        . '     href="' . $value . '" '
                                        . '     data-lightbox="evidencias' . $item['Descripcion'] . '" '
                                        . '     data-title="' . basename($value) . '">'
                                        . '     <img src="' . $value . '" width="70" height="70" '
                                        . '         style="max-height:100px !important;" '
                                        . '         alt="' . basename($value) . '">     '
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
                        if (!empty($item[0]['tablaEquipos'])) {
                            ?>
                            <div class="timeline-footer">
                                <br>
                                <div class="row m-r-10">
                                    <div class="col-md-12">
                                        <h4 class="m-t-10">Lista de Equipos o Materiales</h4>
                                    </div>
                                </div>

                                <!--Empezando Separador-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                </div>
                                <!--Finalizando Separador-->

                                <div class="table-responsive">
                                    <table id="data-table-avance-servicio-<?php echo $item['Id']; ?>" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="never">Tipo Item</th>
                                                <th class="all">Descripción</th>
                                                <th class="all">Serie</th>
                                                <th class="all">Cantidad</th>
                                                <th class="all">Tipo de Falla</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($item[0]['tablaEquipos'] as $key => $value) {
                                                switch ($value['IdItem']) {
                                                    case '1':
                                                        $tipoItem = 'Equipo';
                                                        break;
                                                    case '2':
                                                        $tipoItem = 'Material';
                                                        break;
                                                    case '3':
                                                        $tipoItem = 'Refacción';
                                                        break;
                                                    case '4':
                                                        $tipoItem = 'Elemento';
                                                        break;
                                                    case '5':
                                                        $tipoItem = 'Sub-Elemento';
                                                        break;
                                                }
                                                echo '<tr>';
                                                echo '<td>' . $tipoItem . '</td>';
                                                echo '<td>' . $value['EquipoMaterial'] . '</td>';
                                                echo '<td>' . $value['Serie'] . '</td>';
                                                echo '<td>' . $value['Cantidad'] . '</td>';
                                                echo '<td>' . $value['TipoDiagnostico'] . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <!-- end timeline-body -->
                </li>
                <?php
            }
        }
        ?>
        <li>
            <!-- begin timeline-icon -->
            <div class="timeline-icon">
                <a href="javascript:;" style="background:#707478"><i class="fa fa-spinner"></i></a>
            </div>
            <!-- end timeline-icon -->
            <!-- begin timeline-body -->
            <div class="timeline-body">
                Fin del Historial...
            </div>
            <!-- begin timeline-body -->
        </li>
    </ul>
    <!-- end timeline -->
</div>