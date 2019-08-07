<div class="row">
    <div class="col-md-9 col-sm-6 col-xs-12">
        <h1 class="page-header">Autorizar Permiso de Ausencia</h1>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12 text-right">
        <label id="btnCancelarRevisarPermiso" class="btn btn-success">
            <i class="fa fa fa-reply"></i> Regresar
        </label>  
    </div>
</div>

<!-- Empezando panel Revisar Permiso-->
<div id="panelRevisarPermisos" class="panel panel-inverse">
    <!--Empezando cabecera del panel-->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <label id="btnVerPDFAutorizar" class="btn btn-warning btn-xs">
                <i class="fa fa"></i> Ver PDF
            </label>
            <label id="btnCancelarPermiso" class="btn btn-danger btn-xs" href="#modalRechazo" data-toggle="modal">
                <i class="fa fa"></i> Rechazar
            </label>
            <?php
            if ($perfilUsuario != 44) {
                echo '<label id="btnAutorizarPermiso" class="btn btn-success btn-xs">
                    <i class="fa fa"></i> Autorizar
                </label>&nbsp';
            }
            $fechaDoc = explode(" ", $datosAusencia[0]["FechaDocumento"]);
            if($datosAusencia[0]["FechaAusenciaDesde"] > $fechaDoc[0] && $datosAusencia[0]['IdMotivoAusencia'] != 3 && $datosAusencia[0]['IdMotivoAusencia'] != 4){
                echo '<label id="btnConluirAutorizacion" class="btn btn-primary btn-xs">
                    <i class="fa fa"></i>Autorizar y Concluir
                </label>';
            }else{
                if ($perfilUsuario == 37 || $perfilUsuario == 44) {
                    echo '<label id="btnConluirAutorizacion" class="btn btn-primary btn-xs">
                        <i class="fa fa"></i>Autorizar y Concluir
                    </label>';
                }
            }
            ?>
        </div>
        <h4 class="panel-title">Revisar Permiso</h4>
    </div>
    <div class="tab-content">
        <div class="panel-body">
            <?php
            if(($datosAusencia[0]['IdMotivoAusencia'] == '3' || $datosAusencia[0]['IdMotivoAusencia'] == '4') && $datosAusencia[0]["ArchivosOriginales"] == ''){
                echo '<label style="color: red">Sin archivo de cita o incapacidad</label>';
            }
            ?>
            <form id="formRevisarPermiso" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Documento</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputNombreRevisar" style="width: 100%" disabled value="'.$datosAusencia[0]["FechaDocumento"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nombre</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputNombreRevisar" style="width: 100%" disabled value="'.$datosAusencia[0]["Nombre"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Departamento</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputDepartamentoRevisar" style="width: 100%" disabled value="'.$datosAusencia[0]["Departamento"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Puesto</label>
                        <?php
                        echo 
                        '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="'.$datosAusencia[0]["Puesto"].'"/>';
                        ?>
                    </div>
                </div>
                <div class="col-md-4">                    
                    <div class="form-group">
                        <label>Tipo de Ausencia</label>
                        <?php
                        switch ($datosAusencia[0]["IdTipoAusencia"]){
                            case '1':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="Llegada Tarde"/>';
                                break;
                            case '2':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="Salida Temprano"/>';
                                break;
                            case '3':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="No Asistirá"/>';
                                break;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-4">                    
                    <div class="form-group">
                        <label>Motivo Ausencia</label>
                        <?php
                        switch ($datosAusencia[0]['IdMotivoAusencia']){
                            case '1':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="Personal"/>';
                                break;
                            case '2':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="Trabajo/Comisión"/>';
                                break;
                            case '3':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="IMSS Cita Médica"/>';
                                break;
                            case '4':
                                echo '<input type="text" class="form-control" id="inputPuestoRevisar" style="width: 100%" disabled value="IMSS Incapacidad"/>';
                                break;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                    if ($datosAusencia[0]["FolioDocumento"] != "") {
                    echo '<div id="citaFolio" class="form-group">
                            <label>Cita o Folio</label>
                            <input type="text" class="form-control" id="inputCitaFolioRevisar" style="width: 100%" disabled value="'.$datosAusencia[0]["FolioDocumento"].'"/>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="display: none">
                                <label>Oculto</label>
                                <input class="form-control" id="archivoPDF" value="'.$datosAusencia[0]["Archivo"].'"/>
                            </div>
                        </div>';
                    }else{
                    echo '<div id="citaFolio" class="form-group" style="display: none">
                            <label>Cita o Folio</label>
                            <input type="text" class="form-control" id="inputCitaFolioRevisar" style="width: 100%" disabled/>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="display: none">
                                <label>Oculto</label>
                                <input class="form-control" id="archivoPDF" value="'.$datosAusencia[0]["Archivo"].'"/>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
                <div class="col-md-10">
                    <?php
                    if ($datosAusencia[0]["Motivo"] != "") {
                    echo '<div id="descripcionAusenciaRevisar" class="form-group">
                        <label>Descripción de Ausencia</label>
                        <textarea id="textareaMotivoSolicitudPermisoRevisar" class="form-control" name="descripcionAusenciaRevisar" rows="3" disabled>'.$datosAusencia[0]["Motivo"].'</textarea>
                    </div>';
                    }else{
                    echo '<div id="descripcionAusenciaRevisar" class="form-group" style="display: none">
                        <label>Descripción de Ausencia</label>
                        <textarea id="textareaMotivoSolicitudPermisoRevisar" class="form-control" name="descripcionAusenciaRevisar" rows="3" disabled></textarea>
                    </div>';
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="DiaPermiso">Fecha de Ausencia</label>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                if ($datosAusencia[0]["FechaAusenciaDesde"] != "0000-00-00") {
                                echo '<div id="inputFechaDesdeRevisar" class="input-group date calendario">
                                        <input id="inputFechaPermisoDesdeRevisar" type="text" class="form-control" data-parsley-required="true" disabled value="'.$datosAusencia[0]["FechaAusenciaDesde"].'"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                } else {
                                echo '<div id="inputFechaDesdeRevisar" class="input-group date calendario">
                                        <input id="inputFechaPermisoDesdeRevisar" type="text" class="form-control" placeholder="Desde" data-parsley-required="true" disabled/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                if ($datosAusencia[0]["FechaAusenciaHasta"] != $datosAusencia[0]["FechaAusenciaDesde"]) {
                                echo '<div id="inputFechaHastaRevisar" class="input-group date calendario">
                                        <input id="inputFechaPermisoHastaRevisar" type="text" class="form-control" disabled value="'.$datosAusencia[0]["FechaAusenciaHasta"].'"/>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>';
                                } else {
                                echo '<div id="inputFechaHastaRevisar" class="input-group date calendario">
                                        <input id="inputFechaPermisoHastaRevisar" type="text" class="form-control" disabled placeholder="Hasta"/>
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
                            echo '<div id="bloqueHorarioRevisar" class="form-group">
                                    <label>Hora de Entrada</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="selectSolicitudHoraRevisar" type="text" class="form-control input-small" disabled value="'.$datosAusencia[0]["HoraEntrada"].'">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>';
                            break;
                        case '2':
                            echo '<div id="bloqueHorarioRevisar" class="form-group">
                                    <label>Hora de Salida</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="selectSolicitudHoraRevisar" type="text" class="form-control input-small" disabled value="'.$datosAusencia[0]["HoraSalida"].'">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                                </div>';
                            break;
                        default:
                            echo '<div id="bloqueHorarioRevisar" class="form-group" style="display: none">
                                <label>Hora</label>
                                <div class="input-group bootstrap-timepicker timepicker">
                                    <input id="selectSolicitudHoraRevisar" type="text" class="form-control input-small" disabled value="">
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
                        <input class="form-control" id="idPermisoRevisar" value="'.$datosAusencia[0]["Id"].'"/>
                    </div>
                </div>';
                ?>
                
                <!--Empezando mensaje--> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="mensajeSolicitudPermisosRevisar"></div>
                    </div>
                </div>
                <!--Finalizando mensaje-->
                
            </form>
        </div>        
    </div>
    
</div>
<!-- Finalizando panel Revisar Permiso-->
<div id="modalRechazo" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="form-group">
                    <label>Motivo de rechazo</label>
                    <select id="motivoRechazo" class="form-control efectoDescuento" name="motivoRechazo" style="width: 100%">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($motivosRechazo as $value) {
                            echo '<option value="'.$value['Id'].'">'.$value['Nombre'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnAceptarRechazo" class="btn btn-sm btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Cerrar</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
