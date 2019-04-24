<!-- Empezando #contenido -->
<div id="contentPermisosPendientes" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Autorizar Permisos</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel Autorizacion Permisos-->
    <div id="panelAutorizarPermisos" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <div id="botonesExtra" class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
            </div>
            <h4 class="panel-title">Autorizar Permisos</h4>
        </div>
        <div class="tab-content">
            
            <!--Empezando cuerpo del panel de Tabla Permisos-->
            <div class="tab-pane fade active in" id="misPermisos">
                <div class="panel-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="data-table-autorizar-permisos-ausencia" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">IdPermiso</th>
                                        <th class="all">Fecha de Trámite</th>
                                        <th class="all">Nombre</th>
                                        <th class="all">Tipo Ausencia</th>
                                        <th class="all">Motivo Ausencia</th>
                                        <th class="all">Fecha de Ausencia</th>
                                        <th class="all">Hora de Entrada</th>
                                        <th class="all">Hora de Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($datos['misSubordinados'] != false) {
                                    foreach ($datos['misSubordinados'] as $valores) {
                                        echo "<tr>";
                                            echo '<td>'.$valores['Id'].'</td>';
                                            echo '<td>'.$valores['FechaDocumento'].'</td>';
                                            echo '<td>'.$valores['Nombre'].'</td>';
                                            switch ($valores['IdTipoAusencia']){
                                                case '1':
                                                    echo '<td>Llegada Tarde</td>';
                                                    break;
                                                case '2':
                                                    echo '<td>Salida Temprano</td>';
                                                    break;
                                                case '3':
                                                    echo '<td>No Asistirá</td>';
                                                    break;
                                            }
                                            switch ($valores['IdMotivoAusencia']){
                                                case '1':
                                                    echo '<td>Personal</td>';
                                                    break;
                                                case '2':
                                                    echo '<td>Trabajo/Comisión</td>';
                                                    break;
                                                case '3':
                                                    echo '<td>IMSS Cita Médica</td>';
                                                    break;
                                                case '4':
                                                    echo '<td>IMSS Incapacidad</td>';
                                                    break;
                                            }
                                            if ($valores['FechaAusenciaHasta'] != $valores['FechaAusenciaDesde']) {
                                                echo '<td>'.$valores['FechaAusenciaDesde'].' al '.$valores['FechaAusenciaHasta'].'</td>';
                                            } else {
                                                echo '<td>'.$valores['FechaAusenciaDesde'].'</td>';
                                            }
                                            if ($valores['HoraEntrada'] != "00:00:00") {
                                                echo '<td>'.$valores['HoraEntrada'].'</td>';
                                            } else {
                                                echo '<td></td>';
                                            }
                                            if ($valores['HoraSalida'] != "00:00:00") {
                                                echo '<td>'.$valores['HoraSalida'].'</td>';
                                            } else {
                                                echo '<td></td>';
                                            }
                                        echo "</tr>";
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>  
                    </div>
                    <!--Empezando mensaje--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mensajeAutorizarPermisos"></div>
                        </div>
                    </div>
                    <?php
                    echo '<div class="col-md-3">
                        <div class="form-group" style="display: none">
                            <label>Perfil</label>
                            <input class="form-control" id="idPerfil" value="'.$usuario['IdPerfil'].'"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="display: none">
                            <label>Usuario</label>
                            <input class="form-control" id="idUsuarioRev" value="'.$usuario['Id'].'"/>
                        </div>
                    </div>';
                    ?>
                    <!--Finalizando mensaje-->
                </div>
            </div>
            <!--Finalizando cuerpo del panel de Tabla Permisos-->
        </div>
        
    </div>
    <!-- Finalizando panel Autorizacion Permisos-->   

</div>
<!-- Finalizando #contenido -->
<!-- Empezando #contenido -->
<div id="contentRevisarPermiso" class="content hidden"></div>
<!-- Finalizando #contenido -->
