$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    tabla.generaTablaPersonal('#data-table-validaciones-servicios', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-validaciones-servicios tbody').on('click', 'tr', function () {
        var datos = $('#data-table-validaciones-servicios').DataTable().row(this).data();

        getDetalles(datos);
        eventosValidacionServicios(datos);
    });

    var getDetalles = function (datos) {
        $("#seccion-reporte").addClass("hidden");
        $("#seccion-detalles").removeClass("hidden");

        evento.enviarEvento('Buscar/Detalles', {datos: datos}, '#seccion-detalles', function (respuesta) {
            $("#panel-detalles-solicitud").empty().append(respuesta.solicitud);
            $("#panel-detalles-servicio").empty().append(respuesta.servicio);
            $("#panel-historial-servicio").empty().append(respuesta.historial);
            $("#panel-conversacion-servicio").empty().append(respuesta.conversacion);
            $("#btnExportarPdf").attr("data-id-servicio", datos[1]);

            $("#btnExportarPdf").off("click");
            $("#btnExportarPdf").on("click", function () {
                var data = {
                    'servicio': $(this).attr("data-id-servicio")
                }
                evento.enviarEvento('Servicio/Servicio_ToPdf', data, '#seccion-detalles', function (respuesta) {
                    window.open(respuesta.link);
                });
            });
        });
    };

    var eventosValidacionServicios = function () {
        var datosTablaServicios = arguments[0];

        $('#btnValidarServicio').on('click', function () {
            var modalMensaje = evento.mensajeValidar("¿Realmente quiere Concluir el Servicio?");
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            evento.mostrarModal('Advertencia', modalMensaje);
            $('#btnModalConfirmar').off('click');
            $('#btnAceptarConfirmacion').on('click', function () {
                $('#btnAceptarConfirmacion').attr('disabled', 'disabled');
                $('#btnCancelarConfirmacion').attr('disabled', 'disabled');
                var data = {'servicio': datosTablaServicios[1], ticket: datosTablaServicios[2], idSolicitud: datosTablaServicios[0], servicioConcluir: false};
                evento.enviarEvento('Servicio/Verificar_Servicio', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta.code === 200) {
                        evento.mensajeConfirmacion('Se Valido con Exito', 'Correcto');
                    } else {
                        evento.mensajeConfirmacion('Ocurrió un error al subir la información. Intente de nuevo o contacte al administrador. (' + respuesta.message + ')', 'Error');
                    }
                });
            });
            //Envento para cerrar el modal
            $('#btnCancelarConfirmacion').on('click', function () {
                evento.cerrarModal();
            });
        });
        
        $('#btnRechazarServicio').on('click', function () {
            var modalMensaje = evento.mensajeValidar("¿Realmente quiere rechazar el servicio?");
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            evento.mostrarModal('Advertencia', modalMensaje);
            $('#btnModalConfirmar').off('click');
            $('#btnAceptarConfirmacion').on('click', function () {
                var formularioRechazarServicio = modalRecharServicio();
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Rechazar Servicio', formularioRechazarServicio);
                $('#btnModalConfirmar').off('click');
                $('#btnGuardarDescripionServicio').on('click', function () {
                    if (evento.validarFormulario('#formRechazarFormulario')) {
                        var descripcion = $('#inputDescripcionRecharzarServicio').val();
                        var data = {'servicio': datosTablaServicios[1], idSolicitud: datosTablaServicios[0], descripcion: descripcion, atiende: datosTablaServicios[9], ticket: datosTablaServicios[2]};
                        $('#btnGuardarDescripionServicio').attr('disabled', 'disabled');
                        $('#btnCancelarRechazarServicio').attr('disabled', 'disabled');
                        evento.enviarEvento('Servicio/Rechazar_Servicio', data, '#modal-dialogo', function (respuesta) {
                            if (respuesta instanceof Array || respuesta instanceof Object) {
                                evento.mensajeConfirmacion('Se rechazo con exito.', 'Correcto');
                            }
                        });
                    }
                });
                $('#btnCancelarRechazarServicio').on('click', function () {
                    evento.cerrarModal();
                });
            });
            //Envento para cerrar el modal
            $('#btnCancelarConfirmacion').on('click', function () {
                evento.cerrarModal();
            });
        });

        $("#btnRegresarDetalles").on("click", function () {
            $("#seccion-detalles").addClass("hidden");
            $("#seccion-reporte").removeClass("hidden");
        })
    };

    var modalRecharServicio = function () {
        var html = '<div id="seccionRechazarServicio" > ';
        html += '       <div class="row">';
        html += '           <form class="margin-bottom-0" id="formRechazarFormulario" data-parsley-validate="true" >';
        html += '               <div class="col-md-12">';
        html += '                   <div class="form-group">';
        html += '                       <label for="rechazarServicio">Descripción del Rechazo *</label> ';
        html += '                       <input type="text" class="form-control" id="inputDescripcionRecharzarServicio" placeholder="Descripción del por que esta rechazando el servicio" data-parsley-required="true"/> ';
        html += '                   </div>';
        html += '               </div>';
        html += '               <div class="col-md-12">';
        html += '                   <div class="errorRechazarServicio"></div>';
        html += '               </div>';
        html += '               <div class="row m-t-20">';
        html += '                   <div class="col-md-12 text-center">';
        html += '                       <button id="btnGuardarDescripionServicio" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Aceptar</button>';
        html += '                       <button id="btnCancelarRechazarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>';
        html += '                   </div>';
        html += '               </div>';
        html += '           </form>'
        html += '       </div>';
        html += '</div>';
        html += '';

        return html;
    };
});
