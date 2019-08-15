<!-- Empezando #contenido -->
<div id="contentPermisosVacaciones" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Permisos Ausencia</h1>
    <!-- Finalizando titulo de la pagina -->

    <!-- Empezando panel Servicios-->
    <div id="panelPermisosVacaciones" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading p-0">
            <div class="tab-overflow">
                <ul class="nav nav-tabs nav-tabs-inverse" id="change">
                    <li class="active" id="idPermiso"><a href="#misPermisos" data-toggle="tab">Mis Permisos</a></li>
                    <?php echo '<li class="" id="idUsuario" value="'.$usuario["Id"].'"><a href="#Permisos" data-toggle="tab">Permisos Ausencia</a></li>'?>
                </ul>
            </div>
        </div>
        <!--Finalizando cabecera del panel-->
        <div class="tab-content">
            <!--Empezando cuerpo del panel de Tabla Permisos-->
            <div class="tab-pane fade active in" id="misPermisos">
                <div class="panel-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="table-permisos-ausencia" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">
                                <thead>
                                    <tr>
                                        <th class="never">IdPermiso</th>
                                        <th class="all">Fecha de Trámite</th>
                                        <th class="all">Tipo Ausencia</th>
                                        <th class="all">Motivo Ausencia</th>
                                        <th class="all">Fecha de Ausencia</th>
                                        <th class="all">Hora de Entrada</th>
                                        <th class="all">Hora de Salida</th>
                                        <th class="all">Estado</th>
                                        <th class="all">Falta Autorizar</th>
                                        <th class="never">Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($datos['permisosAusencias'] != false) {
                                        foreach ($datos['permisosAusencias'] as $value) {
                                            echo "<tr>";
                                                echo '<td>'.$value['Id'].'</td>';
                                                echo '<td>'.$value['FechaDocumento'].'</td>';
                                                echo '<td>'.$value['IdTipoAusencia'].'</td>';
                                                echo '<td>'.$value['IdMotivoAusencia'].'</td>';                                                
                                                if ($value['FechaAusenciaHasta'] != $value['FechaAusenciaDesde'] && $value['FechaAusenciaHasta'] != "0000-00-00") {
                                                    echo '<td>'.$value['FechaAusenciaDesde'].' al '.$value['FechaAusenciaHasta'].'</td>';
                                                } else {
                                                    echo '<td>'.$value['FechaAusenciaDesde'].'</td>';
                                                }
                                                if ($value['HoraEntrada'] != "00:00:00") {
                                                    echo '<td>'.$value['HoraEntrada'].'</td>';
                                                } else {
                                                    echo '<td></td>';
                                                }
                                                if ($value['HoraSalida'] != "00:00:00") {
                                                    echo '<td>'.$value['HoraSalida'].'</td>';
                                                } else {
                                                    echo '<td></td>';
                                                }
                                                switch ($value['IdEstatus']){
                                                    case '6':
                                                        echo '<td>Cancelado</td>';
                                                        echo '<td></td>';
                                                        break;
                                                    case '7':
                                                        echo '<td style="color: green">Autorizado</td>';
                                                        echo '<td></td>';
                                                        break;
                                                    case '9':
                                                        echo '<td>Pendiente por Autorizar</td>';
                                                        if ($value['IdUsuarioJefe'] == NULL) {
                                                            echo '<td>
                                                                    <li>Jefe Inmediato</li>
                                                                    <li>RH</li>
                                                                    <li>Contador</li>
                                                                    <li>Director</li>
                                                                </td>';
                                                        } else {
                                                            if ($value['IdUsuarioRH'] == NULL) {
                                                                echo '<td>
                                                                        <li>RH</li>
                                                                        <li>Contador</li>
                                                                        <li>Director</li>
                                                                    </td>';
                                                            } else {
                                                                if ($value['IdUsuarioContabilidad'] == NULL) {
                                                                    echo '<td>
                                                                            <li>Contador</li>
                                                                            <li>Director</li>
                                                                        </td>';
                                                                } else {
                                                                    if ($value['IdUsuarioDireccion'] == NULL) {
                                                                        echo '<td>
                                                                                <li>Director</li>
                                                                            </td>';
                                                                    } else {
                                                                        echo '<td></td>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        break;
                                                    case '10':
                                                        echo '<td style="color: red">Rechazado</td>';
                                                        echo '<td></td>';
                                                        break;
                                                }
                                                echo '<td>'.$value['Archivo'].'</td>';
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
                            <div class="mensajeSolicitudPermisosV1">
                            </div>
                        </div>
                    </div>
                    <!--Finalizando mensaje-->
                </div>
            </div>
            <!--Finalizando cuerpo del panel de Tabla Permisos-->

            <!--Empezando cuerpo del panel de Permisos-->
            <div class="tab-pane fade" id="Permisos">
                <div class="panel-body">
                    <form id="formSolicitudPermiso" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <h5><strong>ACOTACIONES DE ACUERDO CON POLITICA DE ASISTENCIA DE PERSONAL:</strong></h5>
                            <label><strong>LOS AVISOS DE AUSENCIA</strong> deberán ser entregados con una anticipación de <strong>48 HORAS HÁBILES.</strong></label><br><br>
                            <label><strong>EN SITUACIONES DE FUERZA MAYOR</strong>, el empleado que <strong>ACUDA A SU CLÍNICA, SIN CITA MÉDICA</strong>, deberá de notificar y entregar la
                            <strong>CONSTANCIA DE ASISTENCIA MÉDICA Y/O INCAPACIDAD IMSS AL DÍA SIGUIENTE</strong> de la atención recibida a Recursos Humanos o
                            Contabilidad.</label><br><br>
                            <label>Los empleados únicamente podrán <strong>JUSTIFICAR SUS FALTAS</strong> mediante la presentación de la <strong>INCAPACIDAD EMITIDA POR EL IMSS</strong>.</label><br><br>
                            <label>El personal que por motivo de <strong>TRABAJO EXTERNO</strong> a las oficinas o lugar de trabajo, deberá <strong>NOTIFICAR VÍA CORREO ELECTRÓNICO</strong> a su jefe
                            inmediato, Recursos Humanos y Contabilidad con antelación de <strong>24 HORAS HÁBILES</strong>.</label>
                            <div class="underline m-b-15 m-t-15"></div>
                        </div>
                        <div class="col-md-12" style="display: none">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <?php
                                        echo 
                                        '<input type="text" class="form-control" id="inputNombre" style="width: 100%" disabled value="'.$usuario["Nombre"].'"/>';
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Departamento</label>
                                    <?php
                                        foreach ($datos["departamento"] as $departamento) {
                                            if ($usuario["IdDepartamento"] == $departamento["Id"]) {
                                                echo 
                                                '<input type="text" class="form-control" id="inputDepartamento" style="width: 100%" disabled value="'.$departamento["Nombre"].'"/>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Puesto</label>
                                    <?php
                                        echo 
                                        '<input type="text" class="form-control" id="inputPuesto" style="width: 100%" disabled value="'.$usuario["Perfil"].'"/>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">                    
                            <div class="form-group">
                                <label>Tipo de Ausencia *</label>
                                <select id="selectTipoAusencia" class="form-control efectoDescuento" name="SelectTipoAusencia" style="width: 100%" data-parsley-required="true">
                                    <option value="">Seleccionar</option>
                                    <?php
                                        foreach ($datos['tipoAusencia'] as $tipoAusencia) {
                                            echo '<option value="'.$tipoAusencia['Id'].'">'.$tipoAusencia['Nombre'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">                    
                            <div class="form-group">
                                <label>Motivo Ausencia *</label>
                                <select id="selectMotivoAusencia" class="form-control efectoDescuento" name="SelectMotivoAusencia" data-parsley-required="true" style="width: 100%" data-parsley-required="true" disabled>
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">                    
                            <div id="observaciones" class="form-group">
                                <label>Observaciones</label>
                                <input id="inputObservaciones" type="text" class="form-control" style="width: 100%" disabled/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="descripcionAusencia" class="form-group">
                                <label>Descripción de Motivos *</label>
                                <textarea id="textareaMotivoSolicitudPermiso" class="form-control" name="descripcionAusencia" placeholder="Ingresa el motivo u observaciones de su ausencia... " rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="archivoCitaIncapacidad" class="form-group" style="display: none">
                                <label>Archivo Cita o Incapacidad</label><br>
                                <label style="color: red">Todos los archivos que se requiera adjuntar deben ser escaneados a color y en formato PDF</label>
                                <input id="inputEvidenciaIncapacidad" name="evidenciasIncapacidad[]" type="file" multiple>
                            </div>
                        </div>
                        <div class="col-md-3">                    
                            <div id="citaFolio" class="form-group" style="display: none">
                                <br>
                                <label>Cita o Folio *</label>
                                <input type="text" class="form-control" id="inputCitaFolio" style="width: 100%"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DiaPermiso">Fecha de Ausencia *</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class='input-group date' id='inputFechaDesde'>
                                                <input id='inputFechaPermisoDesde' type='text' class="form-control" placeholder="Desde" data-parsley-required="true"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="bloqueFechaHasta" class="form-group" style="display: none;">
                                            <div class='input-group date' id='inputFechaHasta'>
                                                <input id='inputFechaPermisoHasta' type='text' class="form-control" placeholder="Hasta(Opcional)"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">                    
                            <div id="bloqueHorario" class="form-group" style="display: none;">
                                <label id="labelHora"></label>
                                <div id="selectHora" class="input-group bootstrap-timepicker timepicker">
                                    <input id="selectSolicitudHora" type="text" class="form-control input-small">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <!--Empezando mensaje--> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mensajeSolicitudPermisos"></div>
                            </div>
                        </div>
                        <!--Finalizando mensaje-->
                        <div class="col-md-12">
                            <div class="form-group">                            
                                <div id="btnSeccionGenerarSolicitudPermiso" class="col-md-offset-2 col-md-8 text-center m-t-10 m-b-10">
                                    <button id="btnGenerarSolicitudPermiso" type="button" class="btn btn-sm btn-success m-r-5" ><i class="fa fa-check"></i> Solicitar</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
            <!--Finalizando cuerpo del panel de Permisos-->
        </div>
        
    </div>
    <!-- Finalizando panel Servicios-->   

</div>
<!-- Finalizando #contenido -->

<!-- Empezando #contenido -->
<div id="contentActualizar" class="content hidden"></div>
<!-- Finalizando #contenido -->
