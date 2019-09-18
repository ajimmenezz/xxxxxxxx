<!-- Empezando #contenido -->
<div id="contentCalendarioPermisos" class="content">
    <!-- Empezando titulo de la pagina -->
    <h1 class="page-header">Calendario de Permisos</h1>
    <!-- Finalizando titulo de la pagina -->
    <!-- Empezando panel Calendario Permisos-->
    <div id="panelCalendarioPermisos" class="panel panel-inverse">
        <!--Empezando cabecera del panel-->
        <div class="panel-heading">
            <h4 class="panel-title">Calendario</h4>
        </div>
        <!--Finalizando cabecera del panel-->
        <!--Empezando contenido del panel-->
        <div class="panel-body">
            <div id="calendar"></div>
        </div>
        <!--Finalizando contenido del panel-->
    </div>
    <!-- Finalizando panel Calendario Permisos-->
</div>
<!-- Finalizando #contenido -->

<!-- Empezando Modal datos del permiso -->
<div id="modalDatosPermiso" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
                <h4 class="modal-title seccionInfoPermiso">Información del permiso</h4>
                <h4 class="modal-title seccionRechazarPermiso hidden">Rechazar Permiso</h4>
                <h4 class="modal-title seccionSolicitarCancelarPermiso">Solicitar Cancelacion del Permiso</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="panel" data-sortable-id="ui-media-object-3">
                    <!--Empieza seccion de la informacion del permiso-->
                    <div class="panel-body seccionInfoPermiso">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Usuario: <label id="nombreUsuario" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-6">
                                <label>Perfil: <label id="perfilUsuario" class="semi-bold limpiarCampo"></label></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="semi-bold">Información del Permiso</h4>
                            <div class="col-md-4">
                                <label>Tipo de ausencia: <label id="tipoAusencia" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-8">
                                <label>Motivo de ausencia: <label id="motivoAusencia" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha: <label id="fechaAusencia" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-3">
                                <label>Hora: <label id="horaAusencia" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-5">
                                <label>Estado: <label id="estatusAusencia" class="semi-bold limpiarCampo"></label></label>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción: </label>
                                    <textarea id="descripcionAusencia" type="text" class="form-control" rows="3" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <h4 class="semi-bold">Autorizaciones</h4>
                                <div class="col-md-12">
                                    <i id="circleJefe" class="fa fa-circle limpiarIcono"></i> <label>Jefe: <label id="autorizacionJefe" class="semi-bold limpiarCampo"></label></label>
                                </div>
                                <div class="col-md-12">
                                    <i id="circleRecursosHumanos" class="fa fa-circle limpiarIcono"></i> <label>Recursos Humanos: <label id="autorizacionRecursosHumanos" class="semi-bold limpiarCampo"></label></label>
                                </div>
                                <div class="col-md-12">
                                    <i id="circleContabilidad" class="fa fa-circle limpiarIcono"></i> <label>Contabilidad: <label id="autorizacionContabilidad" class="semi-bold limpiarCampo"></label></label>
                                </div>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>
                    </div>
                    <!--Finaliza seccion de la informacion del permiso-->
                    <!--Empieza la seccion del rechazo de un permiso-->
                    <div class="modal-body seccionRechazarPermiso hidden">
                        <div class="form-group">
                            <label>Motivo de rechazo</label>
                            <select id="motivoRechazo" class="form-control" style="width: 100%">
                            </select>
                        </div>
                    </div>
                    <!--Finaliza la seccion del rechazo de un permiso-->
                    <!--Empieza la seccion de solicitud de cancelacion-->
                    <div class="modal-body seccionSolicitarCancelarPermiso hidden">
                        <div id="descripcionSolicitud" class="form-group">
                            <form id="motivoSolicitudCancelacion" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">
                                <label>Motivo de cancelación</label>
                                <div id="listaMotivos">
                                    <select id="selectCancelacion" class="form-control" style="width: 100%" data-parsley-required="true">
                                        <option value="">Seleccionar</option>
                                        <?php
                                        foreach ($datos['motivosCancelacion'] as $value) {
                                            echo '<option value="'.$value['Id'].'">'.$value['text'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="checkOtro" class="checkbox">
                                    <label>
                                        <input type="checkbox" value="" />Otro
                                    </label>
                                </div>
                                <div id="otroMotivo" class="hidden">
                                    <label>Motivo</label>
                                    <textarea id="textareaMotivoSolicitarCancelacion" class="form-control" placeholder="Ingresa el motivo de la cancelación " rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--Finaliza la seccion de solicitud de cancelacion-->
                </div>
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <!--Empiezan botones de la informacion de modal-->
<!--                    <a id="btnAceptarModalPermisos" class="btn btn-sm btn-success seccionInfoPermiso"><i class="fa fa-check"></i> Aceptar</a>
                    <a id="btnConcluirModalPermisos" class="btn btn-sm btn-primary seccionInfoPermiso"><i class="fa fa-sign-out"></i> Aceptar y Concluir</a>
                    <a id="btnRechazarModalPermisos" class="btn btn-sm btn-danger seccionInfoPermiso"><i class="fa fa-sign-out fa-rotate-180"></i> Rechazar</a>-->
                <a id="btnCancelarModalPermisos" class="btn btn-sm btn-warning seccionInfoPermiso"><i class="fa fa-exclamation-circle"></i> Solicitar Cancelación</a>
                <a id="btnCerrarModalPermisos" class="btn btn-sm btn-default seccionInfoPermiso" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
                <!--Finalizan botones de la informacion de modal-->
                <!--Empiezan botones del rechazo de permisos-->
<!--                    <a id="btnAceptarRechazo" class="btn btn-sm btn-success seccionRechazarPermiso hidden"><i class="fa fa-check"></i> Aceptar</a>
                    <a id="btnCerrarRechazo" class="btn btn-sm btn-danger seccionRechazarPermiso hidden"><i class="fa fa-times"></i> Cerrar</a>-->
                <!--Finaliza botones del rechazo de permisos-->
                <!--Empiezan botones de solicitud de cancelacion de permisos-->
                <a id="btnAceptarCancelacion" class="btn btn-sm btn-success seccionSolicitarCancelarPermiso hidden"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrarCancelacion" class="btn btn-sm btn-danger seccionSolicitarCancelarPermiso hidden"><i class="fa fa-times"></i> Cerrar</a>
                <!--Finaliza botones de solicitud de cancelacion de permisos-->
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!-- Finalizando Modal datos del permiso -->
