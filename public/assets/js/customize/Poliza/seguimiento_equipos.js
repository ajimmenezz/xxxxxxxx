$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
//    var servicios = new Servicio();
//    var nota = new Nota();
//    var dataCategoria;
    var file = new Upload();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-poliza', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal('#lista-equipos-enviados-solicitados', null, null, true, true, [[0, 'desc']]);
    var panel = '#panelTablaEquiposEnviados';

    var idPerfil = $('#IdPerfil').val();
    if (idPerfil === `57`) {
        $('#botonNuevoValidacion').removeClass('hidden');
    }

    var incioEtiquetas = function () {
        select.crearSelect('#listaTicket');
        select.crearSelect('#listaServicio');
        select.crearSelect('#listaTipoPersonal');
        select.crearSelect('#listaNombrePersonal');
        select.crearSelect('#listaSolicitarEquipo');
        select.crearSelect('#listaSolicitarRefaccion');
        select.crearSelect('#listPaqueteria');
        select.crearSelect('.listUsuarioRecibe');
        select.crearSelect('#listSucursal');
        select.crearSelect('#listRefaccionUtil');
        select.crearSelect('#listDondeRecibe');


        //obtener valor fecha
        $("#fechaEnvio").val();
        $("#fechaRecepcionAlmacen").val();
        $("#fechaRecepcionLab").val();
        $("#fechaRecepcionLogistica").val();
        $("#fechaRecepcion").val();
        $("#fechaRecepcionTecnico").val();

        //radio inputs valor
        $('input:radio[name=optionsRadios]:checked').val();

        //tablas
        tabla.generaTablaPersonal('#listaRefaccionUtilizada', null, null, true, true, [[0, 'desc']]);

        //Iniciar input archivos
        file.crearUpload('#archivosProblemaGuia', 'Seguimiento/subirProblema');
        file.crearUpload('#evidenciaEnvio', 'Seguimiento/GuardarEnvioAlmacen');
        file.crearUpload('#evidenciaRecepcionAlmacen', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#evidenciaRecepcionLab', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#archivosLabHistorial', 'Seguimiento/AgregarComentarioSeguimientosEquipos');
        file.crearUpload('#evidenciaRecepcionLogistica', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEntrega', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/GuardarRecepcionTecnico');
        file.crearUpload('#evidenciaRecepcionlog', 'Seguimiento/subirAdjuntosLabHistorial');

        file.crearUpload('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaLab', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaLog', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaTec', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#evidenciaEntregaLog', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEnvioGuia', 'Seguimiento/subirAdjuntosLabHistorial');

    };

    $('#agregarEquipo').off('click');
    $('#agregarEquipo').on('click', function () {
        var IdServicio = "";
        formulario(IdServicio);
    });

    $('#lista-equipos-enviados-solicitados tbody').on('click', 'tr', function () {
        var Id = "";
        var IdServicio = "";
        var IdRefaccion = "";
        var idEstatus = "";

        var datos = $('#lista-equipos-enviados-solicitados').DataTable().row(this).data();
        if (datos !== undefined) {
            var idTabla = datos[0];
            IdServicio = datos[1];
            idEstatus = datos[6];
            IdRefaccion = datos[8];
            formulario(IdServicio, IdRefaccion, idEstatus, idTabla);
        }
    });

    var formulario = function () {
        var idServicio = arguments[0];
        var IdRefaccion = arguments[1];
        var idEstatus = arguments[2];
        var idTabla = arguments[3];

        var datos = {"idServicio": idServicio, 'IdRefaccion': IdRefaccion, 'idEstatus': idEstatus};

        evento.enviarEvento('Seguimiento/VistaPorPerfil', datos, panel, function (respuesta) {
            console.log(respuesta);

            $('#panelTablaEquiposEnviados').addClass('hidden');
            $('#seccionFormulariosRecepcionTecnico').removeClass('hidden').empty().append(respuesta.formularioRecepcionTecnico.formularioRecepcionTecnico);
            $('#seccionFormulariosEnvSegLog').removeClass('hidden').empty().append(respuesta.formularioEnvioSeguimientoLog.formularioEnvioSeguimientoLog);
            $('#seccionFormulariosRecepcionLogistica').removeClass('hidden').empty().append(respuesta.formularioRecepcionLog.formularioRecepcionLogistica);
            $('#seccionFormulariosRevisionHistorial').removeClass('hidden').empty().append(respuesta.formularioHistorialRefaccion.formularioRevisionHistorial);
            $('#seccionFormulariosRecepcionLaboratorio').removeClass('hidden').empty().append(respuesta.formularioRecepcionLab.formularioRecepcionLaboratorio);
            $('#seccionFormulariosRecepcionAlmacen').removeClass('hidden').empty().append(respuesta.formularioRecepcionAlmacen.formularioRecepcionAlmacen);
            $('#seccionPanelEspera').removeClass('hidden').empty().append(respuesta.PanelEspera.panelEspera);
            $('#seccionFormulariosGuia').removeClass('hidden').empty().append(respuesta.formularioEnvioAlmacen.formularioGuia);
            $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formularioValidacion.formularioValidacion);
            incioEtiquetas();
            eventosGenerales(idTabla);
            eventosComentarios(idTabla);
//            cargaComentariosAdjuntos(idTabla);

            if ($.inArray('306', respuesta.permisos) !== -1 || $.inArray('306', respuesta.permisosAdicionales) !== -1 || $.inArray('307', respuesta.permisos) !== -1 || $.inArray('307', respuesta.permisosAdicionales) !== -1) {
                bloquerTodosCampos();
            }

            $('#btnRegresarTabla').off('click');
            $('#btnRegresarTabla').on('click', function () {
                $('#panelTablaEquiposEnviados').removeClass('hidden');
                $('#seccionFormulariosValidacion').addClass('hidden');
            });
        });
    };

    var eventosGenerales = function (idTabla) {

        $("#listaTicket").on("change", function () {
            select.cambiarOpcion("#listaServicio", '');
            $("#listaServicio").empty().append('<option value="">Seleccionar...</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'ticket': $(this).val()
                }

                evento.enviarEvento('Seguimiento/ConsultaServiciosTecnico', datos, '#panelValidacion', function (respuesta) {
                    console.log(respuesta);
                    $.each(respuesta, function (k, v) {
                        $("#listaServicio").append('<option value="' + v.ID + '">' + v.Id + ' - ' + v.Descripcion + '</option>')
                    });
                    $("#listaServicio").removeAttr("disabled");
//
//                    if (editarOrdenComprarGapsi !== null) {
//                        select.cambiarOpcion('#selectProyectoOrdenCompra', editarOrdenComprarGapsi.Proyecto);
//                    } else {
//                        select.cambiarOpcion("#selectProyectoOrdenCompra", '');
//                    }
                });
            } else {
                $("#listaServicio").attr("disabled", "disabled");
            }
        });

        $("#listaServicio").on("change", function () {
            $("#listaTipoPersonal").removeAttr("disabled");

//            $("#listaServicio").empty().append('<option value="">Seleccionar...</option>');
//            if ($(this).val() !== '') {
//                var datos = {
//                    'ticket': $(this).val()
//                }
//
//                evento.enviarEvento('Seguimiento/ConsultaServiciosTecnico', datos, '#panelValidacion', function (respuesta) {
//                    console.log(respuesta);
//                    $.each(respuesta, function (k, v) {
//                        $("#listaServicio").append('<option value="' + v.ID + '">' + v.Id + ' - ' + v.Descripcion + '</option>')
//                    });
//                    $("#listaServicio").removeAttr("disabled");
////
////                    if (editarOrdenComprarGapsi !== null) {
////                        select.cambiarOpcion('#selectProyectoOrdenCompra', editarOrdenComprarGapsi.Proyecto);
////                    } else {
////                        select.cambiarOpcion("#selectProyectoOrdenCompra", '');
////                    }
//                });
//            } else {
//                $("#listaServicio").attr("disabled", "disabled");
//                select.cambiarOpcion("#listaServicio", '');
//            }
        });

        $('#btnGuardarRecepcionTec').off('click');
        $('#btnGuardarRecepcionTec').on('click', function () {
            var arrayCampos = [
                {'objeto': '#fechaRecepcionTecnico', 'mensajeError': 'Falta seleccionar la Fecha de Recepción.'},
                {'objeto': '#evidenciaRecepcionTecnico', 'mensajeError': 'Falta seleccionarel la evidencia de recepción.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioTecnico');

            if (camposFormularioValidados) {
                var data = {
                    'id': idTabla
                }

                file.enviarArchivos('#evidenciaRecepcionTecnico', 'Seguimiento/GuardarRecepcionTecnico', '#panelRecepcionTecnico', data, function (respuesta) {
                    if (respuesta.code == 200) {
                        $('#fechaRecepcionTecnico').attr('disabled', 'disabled');
                        $('#btnGuardarRecepcionTec').addClass('disabled');
                        evento.mostrarMensaje("#errorFormularioTecnico", true, "Se ha guardado correctamente.", 4000);
                    } else {
                        evento.mostrarMensaje("#errorFormularioTecnico", false, "Ocurrió un error al guardar el comentario. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            }
        });
    }

    var eventosComentarios = function (idTabla) {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#problemasRecepcionAlm":
                    cargaRecepcionesProblemas(idTabla, '1', '28', '#panelRecepcionAlmacen', '#divNotasAdjuntosAlmacen');
                    break;
                case "#problemasRecepcionLab":
                    cargaRecepcionesProblemas(idTabla, '2', '29', '#panelRecepcionLaboratorio', '#divNotasAdjuntosLaboratorio');
                    break;
                case "#problemasRecepcionLog":
                    cargaRecepcionesProblemas(idTabla, '3', '30', '#panelRecepcionLogistica', '#divNotasAdjuntosLogistica');
                    break;
                case "#problemasRecepcionTenico":
                    cargaRecepcionesProblemas(idTabla, '4', '31', '#panelRecepcionTecnico', '#divNotasAdjuntosTecnico');
                    break;
            }
        });

        $('#agregarComentarioHistorial').off('click');
        $('#agregarComentarioHistorial').on('click', function () {
            var comentarios = $.trim($("#comentariosObservaciones").val());
            var adjunto = $("#archivosLabHistorial").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios
                };

                file.enviarArchivos('#archivosLabHistorial', 'Seguimiento/AgregarComentarioSeguimientosEquipos', '#panelLaboratorioHistorial', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarComentario", true, "Se ha guardado el comentario correctamente", 6000);
                        $("#comentariosObservaciones").val('').text('');
                        file.limpiar('#archivosLabHistorial');
                        cargaComentariosAdjuntos(idTabla);
                    } else {
                        evento.mostrarMensaje("#errorAgregarComentario", false, "Ocurrió un error al guardar el comnetario. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarComentario", false, "Al menos debe agregar el comentario o un adjunto para poder agregar la información", 4000);
            }
        });

        $('#btnAgregarProblemaAlm').off('click');
        $('#btnAgregarProblemaAlm').on('click', function () {
            var comentarios = $.trim($("#txtNotaAlmacen").val());
            var adjunto = $("#adjuntosProblemaAlm").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios,
                    'tipoProblema': 'almacen'
                };

                file.enviarArchivos('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionAlmacen', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaAlm", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaAlmacen").val('').text('');
                        file.limpiar('#adjuntosProblemaAlm');
                        cargaRecepcionesProblemas(idTabla, '1', '28', '#panelRecepcionAlmacen', '#divNotasAdjuntosAlmacen');
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaAlm", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaAlm", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
            }
        });

        $('#btnAgregarProblemaLab').off('click');
        $('#btnAgregarProblemaLab').on('click', function () {
            var comentarios = $.trim($("#txtNotaLaboratorio").val());
            var adjunto = $("#adjuntosProblemaLab").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios,
                    'tipoProblema': 'laboratorio'
                };

                file.enviarArchivos('#adjuntosProblemaLab', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionLaboratorio', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaLab", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaLaboratorio").val('').text('');
                        file.limpiar('#adjuntosProblemaLab');
                        cargaRecepcionesProblemas(idTabla, '2', '29', '#panelRecepcionLaboratorio', '#divNotasAdjuntosLaboratorio');
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaLab", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaLab", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
            }
        });

        $('#btnAgregarProblemaLog').off('click');
        $('#btnAgregarProblemaLog').on('click', function () {
            var comentarios = $.trim($("#txtNotaLogistica").val());
            var adjunto = $("#adjuntosProblemaLog").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios,
                    'tipoProblema': 'logistica'
                };

                file.enviarArchivos('#adjuntosProblemaLog', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionLogistica', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaLog", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaLogistica").val('').text('');
                        file.limpiar('#adjuntosProblemaLog');
                        cargaRecepcionesProblemas(idTabla, '3', '30', '#panelRecepcionLogistica', '#divNotasAdjuntosLogistica');
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaLog", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaLab", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
            }
        });

        $('#btnAgregarProblemaTec').off('click');
        $('#btnAgregarProblemaTec').on('click', function () {
            var comentarios = $.trim($("#txtNotaTecnico").val());
            var adjunto = $("#adjuntosProblemaTec").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios,
                    'tipoProblema': 'tecnico'
                };

                file.enviarArchivos('#adjuntosProblemaTec', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionTecnico', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaTec", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaTecnico").val('').text('');
                        file.limpiar('#adjuntosProblemaTec');
                        cargaRecepcionesProblemas(idTabla, '4', '31', '#panelRecepcionTecnico', '#divNotasAdjuntosTecnico');
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaTec", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaTec", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
            }
        });
    }

    var cargaComentariosAdjuntos = function (idTabla) {
        var datos = {
            'id': idTabla
        };

        evento.enviarEvento('Seguimiento/CargaComentariosAdjuntos', datos, '#panelLaboratorioHistorial', function (respuesta) {
            if (respuesta.code == 200) {
                $("#divComentariosAdjuntos").empty().append(respuesta.formulario);
            } else {
                evento.mostrarMensaje("#errorAgregarComentario", false, respuesta.error, 4000);
            }
        });
    }

    var cargaRecepcionesProblemas = function () {
        var idTabla = arguments[0];
        var idDepartamento = arguments[1];
        var idEstatus = arguments[2];
        var panel = arguments[3];
        var div = arguments[4];
        var datos = {
            'id': idTabla,
            'idDepartamento': idDepartamento,
            'idEstatus': idEstatus
        };

        evento.enviarEvento('Seguimiento/CargaRecepcionesProblemas', datos, panel, function (respuesta) {
            if (respuesta.code == 200) {
                $(div).empty().append(respuesta.formulario);
            }
        });
    }

    var bloquerTodosCampos = function () {
        $('#IdUsuarioRecibe').attr('disabled', 'disabled');
        $('#fechaRecepcionAlm').attr('disabled', 'disabled');
        $('#fechaRecepcionTecnico').attr('disabled', 'disabled');
        $('#txtNotaAlmacen').attr('disabled', 'disabled');
        $('#txtNotaLaboratorio').attr('disabled', 'disabled');
        $('#txtNotaLogistica').attr('disabled', 'disabled');
        $('#txtNotaTecnico').attr('disabled', 'disabled');
        $('#comentariosObservaciones').attr('disabled', 'disabled');
        $('#cantidad').attr('disabled', 'disabled');
        $('#btnAgregarProblemaAlm').addClass('disabled');
        $('#btnAgregarProblemaLab').addClass('disabled');
        $('#btnAgregarProblemaLog').addClass('disabled');
        $('#btnAgregarProblemaTec').addClass('disabled');
        $('#btnGuardarRecepcionTec').addClass('disabled');
        $('#agregarComentarioHistorial').addClass('disabled');
        $('#consluirRevisionLab').addClass('disabled');
        $('#btnAgregarRefaccion').addClass('disabled');
        file.deshabilitar('#archivosProblemaGuia');
        file.deshabilitar('#evidenciaEnvio');
        file.deshabilitar('#evidenciaRecepcionAlmacen');
        file.deshabilitar('#evidenciaRecepcionLab');
        file.deshabilitar('#archivosLabHistorial');
        file.deshabilitar('#evidenciaRecepcionLogistica');
        file.deshabilitar('#evidenciaEntrega');
        file.deshabilitar('#evidenciaRecepcionTecnico');
        file.deshabilitar('#evidenciaRecepcionlog');
        file.deshabilitar('#adjuntosProblemaAlm');
        file.deshabilitar('#adjuntosProblemaLab');
        file.deshabilitar('#adjuntosProblemaLog');
        file.deshabilitar('#adjuntosProblemaTec');
        file.deshabilitar('#evidenciaEntregaLog');
        file.deshabilitar('#evidenciaEnvioGuia');
        $('#listaTicket').attr('disabled', 'disabled');
        $('#listaServicio').attr('disabled', 'disabled');
        $('#listaTipoPersonal').attr('disabled', 'disabled');
        $('#listaNombrePersonal').attr('disabled', 'disabled');
        $('#listaSolicitarEquipo').attr('disabled', 'disabled');
        $('#listaSolicitarRefaccion').attr('disabled', 'disabled');
        $('#listPaqueteria').attr('disabled', 'disabled');
        $('.listUsuarioRecibe').attr('disabled', 'disabled');
        $('#listSucursal').attr('disabled', 'disabled');
        $('#listRefaccionUtil').attr('disabled', 'disabled');
        $('#listDondeRecibe').attr('disabled', 'disabled');
    }

});
