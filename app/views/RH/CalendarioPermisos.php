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
                <h4 class="modal-title">Información del permiso</h4>
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <!--Empieza seccion de evidencia-->
                <div class="panel" data-sortable-id="ui-media-object-3">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label>Usuario: <label id="nombreUsuario" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-6">
                                <label>Perfil: <label id="perfilUsuario" class="semi-bold"></label></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="semi-bold">Información del Permiso</h4>
                            <div class="col-md-4">
                                <label>Tipo de ausencia: <label id="tipoAusencia" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-8">
                                <label>Motivo de ausencia: <label id="motivoAusencia" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha: <label id="" class="semi-bold">2019-05-09</label></label>
                            </div>
                            <div class="col-md-3">
                                <label>Hora: <label id="" class="semi-bold">05:00:00</label></label>
                            </div>
                            <div class="col-md-5">
                                <label>Estado: <label id="estatusAusencia" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción: </label>
                                    <textarea id="descripcionAusencia" type="text" class="form-control" rows="3" disabled></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="semi-bold">Autorizaciones</h4>
                            <div class="col-md-12">
                                <i class="fa fa-circle text-success"></i> <label>Jefe: <label id="autorizacionJefe" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-12">
                                <i class="fa fa-circle text-danger"></i> <label>Recursos Humanos: <label id="autorizacionRecursosHumanos" class="semi-bold"></label></label>
                            </div>
                            <div class="col-md-12">
                                <i class="fa fa-circle text-danger"></i> <label>Contabilidad: <label id="autorizacionContabilidad" class="semi-bold"></label></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza seccion de evidencia-->
            </div>
            <!--Finaliza cuerpo del modal-->
            <!--Empieza pie del modal-->
            <div class="modal-footer text-center">
                <a id="btnCerrarAM" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-primary"><i class="fa fa-sign-out"></i> Aceptar y Concluir</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-danger"><i class="fa fa-sign-out fa-rotate-180"></i> Rechazar</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-circle"></i> Solicitar Cancelación</a>
                <a id="btnCerrarAM" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
            </div>
            <!--Finaliza pie del modal-->
        </div>
    </div>
</div>
<!-- Finalizando Modal datos del permiso -->

<!-- Finalizando panel Revisar Permiso-->
<div id="modalRechazo" class="modal modal-message fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loader"></div>
            <!--Empieza titulo del modal-->
            <div class="modal-header" style="text-align: center">
            </div>
            <!--Finaliza titulo del modal-->
            <!--Empieza cuerpo del modal-->
            <div class="modal-body">
                <div class="form-group">
                    <label>Motivo de rechazo</label>
                    <select id="motivoRechazo" class="form-control efectoDescuento" style="width: 100%">
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
