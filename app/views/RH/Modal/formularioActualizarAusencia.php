<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Actualizar Permiso de Ausencia</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnCancelarActualizarPermiso" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>

<!-- Empezando panel Actualizar Permiso-->
<div id="panelActualizarPermisos" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnVerPDFAutorizar" class="btn btn-warning btn-xs">
                <i class="fa fa"></i> Ver PDF
            </label>
        </div>
        <h4 class="panel-title">Actualizar Permiso</h4>
    </div>
    <div class="tab-content">
        <div class="panel-body">
            <form id="formActualizarPermiso" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Documento</label>
                        <input type="text" class="form-control date" id="inputFechaDocumentoAct" style="width: 100%" disabled/>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nombre</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputNombreAct" style="width: 100%" disabled value="'.$datosAusencia[0]["Nombre"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Departamento</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputDepartamentoAct" style="width: 100%" disabled value="'.$datosAusencia[0]["Departamento"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Puesto</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputPuestoAct" style="width: 100%" disabled value="'.$datosAusencia[0]["Puesto"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-4">                    
                    <div class="form-group">
                        <label>Tipo de Ausencia *</label>
                        <select id="selectTipoAusenciaAct" class="form-control" name="SelectTipoAusenciaAct" style="width: 100%" data-parsley-required="true">
                        <?php
                        foreach ($tiposAusencia as $tipoAusencia) {
                            if ($datosAusencia[0]["IdTipoAusencia"] == $tipoAusencia['Id']) {
                                echo '<option value="'.$tipoAusencia['Id'].'" selected="selected">'.$tipoAusencia['Nombre'].'</option>';
                            }else{
                                echo '<option value="'.$tipoAusencia['Id'].'">'.$tipoAusencia['Nombre'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">                    
                    <div class="form-group">
                        <label>Motivo Ausencia *</label>
                        <select id="selectMotivoAusenciaAct" class="form-control" name="SelectMotivoAusenciaAct" data-parsley-required="true" style="width: 100%" data-parsley-required="true">
                        <?php
                        foreach ($motivosAusencia as $motivoAusencia) {
                            if ($datosAusencia[0]["IdMotivoAusencia"] == $motivoAusencia['Id']) {
                                echo '<option value="'.$motivoAusencia['Id'].'" selected="selected">'.$motivoAusencia['Nombre'].'</option>';
                            }else{
                                echo '<option value="'.$motivoAusencia['Id'].'">'.$motivoAusencia['Nombre'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                    if ($datosAusencia[0]["FolioDocumento"] != "") {
                    echo '<div id="citaFolioAct" class="form-group">
                            <label>Cita o Folio *</label>
                            <input type="text" class="form-control" id="inputCitaFolioAct" style="width: 100%" value="'.$datosAusencia[0]["FolioDocumento"].'"/>
                        </div>';
                    }else{
                    echo '<div id="citaFolioAct" class="form-group" style="display: none">
                            <label>Cita o Folio *</label>
                            <input type="text" class="form-control" id="inputCitaFolioAct" style="width: 100%"/>
                        </div>';
                    }
                    ?>
                </div>
                <div class="col-md-10">
                    <?php
                    if ($datosAusencia[0]["Motivo"] != "") {
                    echo '<div id="descripcionAusenciaAct" class="form-group">
                        <label>Descripción de Ausencia *</label>
                        <textarea id="textareaMotivoSolicitudPermisoAct" class="form-control" name="descripcionAusenciaAct" rows="3">'.$datosAusencia[0]["Motivo"].'</textarea>
                    </div>';
                    }else{
                    echo '<div id="descripcionAusenciaAct" class="form-group" style="display: none">
                        <label>Descripción de Ausencia *</label>
                        <textarea id="textareaMotivoSolicitudPermisoAct" class="form-control" name="descripcionAusenciaAct" rows="3"></textarea>
                    </div>';
                    }
                    ?>
                </div>
                <div class="col-md-10">
                    <?php
                    if ($datosAusencia[0]["FolioDocumento"] != "") {
                    echo '<div id="archivoCitaIncapacidadAct" class="form-group">
                        <label>Archivo Cita o Incapacidad</label>
                        <input id="inputEvidenciaIncapacidadAct" name="evidenciasIncapacidadAct[]" type="file" multiple data-parsley-required="true">
                    </div>';
                    }else{
                    echo '<div id="archivoCitaIncapacidadAct" class="form-group" style="display: none">
                        <label>Archivo Cita o Incapacidad</label>
                        <input id="inputEvidenciaIncapacidadAct" name="evidenciasIncapacidadAct[]" type="file" multiple>
                    </div>';
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="DiaPermiso">Fecha de Ausencia *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                if ($datosAusencia[0]["FechaAusenciaDesde"] != "0000-00-00") {
                                echo '<div id="inputFechaDesdeAct" class="input-group date calendario">
                                        <input id="inputFechaPermisoDesdeAct" type="text" class="form-control" data-parsley-required="true" value="'.$datosAusencia[0]["FechaAusenciaDesde"].'"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                } else {
                                echo '<div id="inputFechaDesdeAct" class="input-group date calendario">
                                        <input id="inputFechaPermisoDesdeAct" type="text" class="form-control" placeholder="Desde" data-parsley-required="true"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                if ($datosAusencia[0]["FechaAusenciaHasta"] != "0000-00-00") {
                                echo '<div id="inputFechaHastaAct" class="input-group date calendario">
                                        <input id="inputFechaPermisoHastaAct" type="text" class="form-control" value="'.$datosAusencia[0]["FechaAusenciaHasta"].'"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                } else {
                                echo '<div id="inputFechaHastaAct" class="input-group date calendario">
                                        <input id="inputFechaPermisoHastaAct" type="text" class="form-control" placeholder="Hasta"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">                    
                    <?php
                    switch ($datosAusencia[0]["IdTipoAusencia"]) {
                        case '1':
                            echo '<div id="bloqueHorarioAct" class="form-group">
                                    <label id="labelHoraAct">Hora de Entrada</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="selectSolicitudHoraAct" type="text" class="form-control input-small" value="'.$datosAusencia[0]["HoraEntrada"].'">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>';
                            break;
                        case '2':
                            echo '<div id="bloqueHorarioAct" class="form-group">
                                    <label id="labelHoraAct">Hora de Salida</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="selectSolicitudHoraAct" type="text" class="form-control input-small" value="'.$datosAusencia[0]["HoraSalida"].'">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>';
                            break;
                        default:
                            echo '<div id="bloqueHorarioAct" class="form-group" style="display: none">
                                <label id="labelHoraAct">Hora</label>
                                <div class="input-group bootstrap-timepicker timepicker">
                                    <input id="selectSolicitudHoraAct" type="text" class="form-control input-small" value="">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                            </div>';
                            break;
                    }
                    ?>
                </div>
                <?php
                echo '<div class="col-md-3">
                    <div class="form-group" style="display: none">
                        <label>Oculto</label>
                        <input class="form-control" id="idPermisoAct" value="'.$datosAusencia[0]["Id"].'"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="display: none">
                        <label>Archivo</label>
                        <input class="form-control" id="archivoPDF" value="'.$datosAusencia[0]["Archivo"].'"/>
                    </div>
                </div>';
                ?>
                
                <!--Empezando mensaje--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="mensajeSolicitudPermisosAct"></div>
                    </div>
                </div>
                <!--Finalizando mensaje-->
                <div class="col-md-12">
                    <div class="form-group">                            
                        <div id="btnActualizarSolicitudPermiso" class="col-md-offset-2 col-md-8 text-center m-t-10 m-b-10">
                            <button id="btnActualizarPermiso" type="button" class="btn btn-sm btn-success m-r-5" ><i class="fa fa-check"></i> Actualizar</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>        
    </div>
    
</div>
<!-- Finalizando panel Actualizar Permiso-->   
