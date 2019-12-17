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
                <?php
                if ($usuario["IdPerfil"] == 37 || $usuario["IdPerfil"] == 21) {
                    echo '<a href="#modalExportar" class="btn btn-xs btn-success" data-toggle="modal"><i class="fa fa-file-excel-o"></i></a>';
                }
                ?>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
            <h4 class="panel-title">Autorizar Permisos</h4>
        </div>
        <div class="tab-content">

            <!--Empezando cuerpo del panel de Tabla Permisos-->
            <div class="tab-pane fade active in" id="misPermisos">
                <div class="panel-body">
                    <!--Empezando mensaje--> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mensajeErrorExcel"></div>
                        </div>
                    </div>
                    <!--Finalizando mensaje-->
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
                                        <th class="all">Estado</th>
                                        <th class="all">Falta Autorizar</th>
                                        <th class="all">Vigencia</th>
                                        <th class="never">Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $fecha = mdate('%Y-%m-%d', now('America/Mexico_City'));
                                    if ($datos['misSubordinados'] != false) {
                                        foreach ($datos['misSubordinados'] as $valores) {
                                            echo "<tr>";
                                            echo '<td>' . $valores['Id'] . '</td>';
                                            echo '<td>' . $valores['FechaDocumento'] . '</td>';
                                            echo '<td>' . $valores['Nombre'] . '</td>';
                                            switch ($valores['IdTipoAusencia']) {
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
                                            echo '<td>' . $valores['MotivoAusencia'] . '</td>';
                                            if ($valores['FechaAusenciaHasta'] != $valores['FechaAusenciaDesde'] && $valores['FechaAusenciaHasta'] != "0000-00-00") {
                                                echo '<td>' . $valores['FechaAusenciaDesde'] . ' al ' . $valores['FechaAusenciaHasta'] . '</td>';
                                            } else {
                                                echo '<td>' . $valores['FechaAusenciaDesde'] . '</td>';
                                            }
                                            if ($valores['HoraEntrada'] != "00:00:00") {
                                                echo '<td>' . $valores['HoraEntrada'] . '</td>';
                                            } else {
                                                echo '<td></td>';
                                            }
                                            if ($valores['HoraSalida'] != "00:00:00") {
                                                echo '<td>' . $valores['HoraSalida'] . '</td>';
                                            } else {
                                                echo '<td></td>';
                                            }
                                            switch ($valores['IdEstatus']) {
                                                case '6':
                                                    echo '<td style="color: orange">Cancelado</td>';
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                    break;
                                                case '7':
                                                    echo '<td style="color: green">Autorizado</td>';
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                    break;
                                                case '9':
                                                    echo '<td>Pendiente por Autorizar</td>';
                                                    if ($valores['IdUsuarioJefe'] == NULL) {
                                                        echo '<td>
                                                                    <li>Jefe Inmediato</li>
                                                                    <li>RH</li>
                                                                    <li>Contador</li>
                                                                    <li>Director</li>
                                                                </td>';
                                                    } else {
                                                        if ($valores['IdUsuarioRH'] == NULL) {
                                                            echo '<td>
                                                                        <li>RH</li>
                                                                        <li>Contador</li>
                                                                        <li>Director</li>
                                                                    </td>';
                                                        } else {
                                                            if ($valores['IdUsuarioContabilidad'] == NULL) {
                                                                echo '<td>
                                                                            <li>Contador</li>
                                                                            <li>Director</li>
                                                                        </td>';
                                                            } else {
                                                                if ($valores['IdUsuarioDireccion'] == NULL) {
                                                                    echo '<td>
                                                                                <li>Director</li>
                                                                            </td>';
                                                                } else {
                                                                    echo '<td></td>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $date1 = new DateTime($fecha);
                                                    $date2 = new DateTime($valores['FechaAusenciaDesde']);
                                                    $diff = $date1->diff($date2);
                                                    if ($valores['FechaAusenciaDesde'] > $fecha) {
                                                        echo '<td class="semi-bold" style="color: green">Solicitud dentro de ' . $diff->days . ' días</td>';
                                                    }
                                                    if ($valores['FechaAusenciaDesde'] == $fecha) {
                                                        echo '<td class="semi-bold"> Para Hoy </td>';
                                                    }
                                                    if ($valores['FechaAusenciaDesde'] < $fecha) {
                                                        echo '<td class="semi-bold" style="color: red">Expiro hace ' . $diff->days . ' días</td>';
                                                    }
                                                    break;
                                                case '10':
                                                    echo '<td style="color: red">Rechazado</td>';
                                                    echo '<td></td>';
                                                    echo '<td></td>';
                                                    break;
                                            }
                                            echo '<td>' . $valores['Archivo'] . '</td>';
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
                            <input class="form-control" id="idPerfil" value="' . $usuario['IdPerfil'] . '"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="display: none">
                            <label>Usuario</label>
                            <input class="form-control" id="idUsuarioRev" value="' . $usuario['Id'] . '"/>
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

<!--Empieza modal de evidencia-->
<div id="modalExportar" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title">Exportar Excel</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <!--Empieza seccion de evidencia-->
                <div class="panel">
                    <div class="col-md-3"></div>
                    <div class="col-md-8">
                        <form id="fechasReportePermisos" data-parsley-validate="true" class="input-group input-daterange">
                            <div class="input-group input-daterange">
                                <input id="comienzo" type="text" class="form-control" name="start" placeholder="Comienzo Fecha" data-parsley-required="true"/>
                                <span class="input-group-addon">a</span>
                                <input id="fin" type="text" class="form-control" name="end" placeholder="Fin Fecha" data-parsley-required="true"/>
                            </div>
                        </form>
                    </div>
                </div>
                <!--Finaliza seccion de evidencia-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnExcel" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrarModal" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!--Finaliza modal de evidencia-->