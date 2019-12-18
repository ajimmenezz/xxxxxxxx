$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
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
        select.crearSelect('#listChofer');


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
        tabla.generaTablaPersonal('#listaRefaccionUtilizadaLaboratorio', null, null, true, true, [[0, 'desc']]);
        tabla.generaTablaPersonal('#lista-solicitud-producto', null, null, true, true);

        //Iniciar input archivos
        file.crearUpload('#archivosProblemaGuia', 'Seguimiento/GuardarProblemaGuiaLogistica');
        file.crearUpload('#evidenciaEnvio', 'Seguimiento/GuardarEnvioLogistica');
        file.crearUpload('#evidenciaRecepcionAlmacen', 'Seguimiento/GuardarRecepcionAlmacen');
        file.crearUpload('#evidenciaRecepcionLab', 'Seguimiento/GuardarRecepcionLaboratorio');
        file.crearUpload('#archivosLabHistorial', 'Seguimiento/AgregarComentarioSeguimientosEquipos');
        file.crearUpload('#evidenciaRecepcionLogistica', 'Seguimiento/GuardarRecepcionLogistica');
        file.crearUpload('#evidenciaEntrega', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/GuardarRecepcionTecnico');
        file.crearUpload('#evidenciaRecepcionlog', 'Seguimiento/subirAdjuntosLabHistorial');

        file.crearUpload('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaLab', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaLog', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaTec', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#evidenciaEntregaLog', 'Seguimiento/GuardarEntregaLogistica');
        file.crearUpload('#evidenciaEnvioGuia', 'Seguimiento/GuardarEnvioAlmacen');

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
            vistasDeFormularios(respuesta);
            incioEtiquetas();
            eventosGenerales(idTabla, idServicio);
            eventosComentarios(idTabla, idServicio);
            cargaComentariosAdjuntos(idTabla, respuesta.formularioHistorialRefaccion);
        });
    };

    var vistasDeFormularios = function (respuesta) {
        $('#panelTablaEquiposEnviados').addClass('hidden');
        $('#seccionFormulariosRecepcionTecnico').removeClass('hidden').empty().append(respuesta.formularioRecepcionTecnico.formularioRecepcionTecnico);
        $('#seccionFormulariosEnvSegLog').removeClass('hidden').empty().append(respuesta.formularioEnvioSeguimientoLog.formularioEnvioSeguimientoLog);
        $('#seccionFormulariosRecepcionLogistica').removeClass('hidden').empty().append(respuesta.formularioRecepcionLog.formularioRecepcionLogistica);
        $('#seccionFormulariosRevisionHistorial').removeClass('hidden').empty().append(respuesta.formularioHistorialRefaccion.formularioRevisionHistorial);
        $('#seccionFormulariosRecepcionLaboratorio').removeClass('hidden').empty().append(respuesta.formularioRecepcionLab.formularioRecepcionLaboratorio);
        $('#seccionFormulariosRecepcionAlmacen').removeClass('hidden').empty().append(respuesta.formularioRecepcionAlmacen.formularioRecepcionAlmacen);
        $('#seccionPanelEspera').removeClass('hidden').empty().append(respuesta.PanelEspera.panelEspera);
        $('#seccionFormulariosGuia').removeClass('hidden').empty().append(respuesta.formularioEnvioAlmacen.formularioGuia);
        $('#seccionFormulariosSinGuia').removeClass('hidden').empty().append(respuesta.formularioGuia.formularioParaGuia);
        $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formularioValidacion.formularioValidacion);

        if ($.inArray('306', respuesta.permisos) !== -1 || $.inArray('306', respuesta.permisosAdicionales) !== -1 || $.inArray('307', respuesta.permisos) !== -1 || $.inArray('307', respuesta.permisosAdicionales) !== -1) {
//            bloquerTodosCampos();
        }

        if (respuesta.formularioEnvioSeguimientoLog !== undefined) {
            if (respuesta.formularioEnvioSeguimientoLog.datos !== undefined) {
                if (respuesta.formularioEnvioSeguimientoLog.datos.informacionEnvioLog !== null) {
                    var $radiosTipoEnvio = $('input[name="radioTipoEnvio"]');
                    if (respuesta.formularioEnvioSeguimientoLog.datos.informacionEnvioLog[0].IdUsuarioTransito !== null) {
                        $radiosTipoEnvio.filter('[value=0]').attr('checked', true);
                    } else {
                        $radiosTipoEnvio.filter('[value=1]').attr('checked', true);
                    }
                    $('input[name="radioTipoEnvio"]').attr("disabled", "disabled");
                    var $radiosCuenta = $('input[name="radioCuenta"]');
                    $radiosCuenta.filter('[value=' + respuesta.formularioEnvioSeguimientoLog.datos.informacionEnvioLog[0].CuentaSiccob + ']').attr('checked', true);
                    $('input[name="radioCuenta"]').attr("disabled", "disabled");
                }
            }
        }
    };

    var eventosGenerales = function () {
        var idTabla = arguments[0];
        var idServicio = arguments[1];

        $('.btnRegresarTabla').removeClass('hidden');

        $('.btnRegresarTabla').off('click');
        $('.btnRegresarTabla').on('click', function () {
            $('#panelTablaEquiposEnviados').removeClass('hidden');
            $('#seccionFormulariosValidacion').addClass('hidden');
            $('#seccionPanelEspera').addClass('hidden');
            $('#seccionFormulariosRecepcionTecnico').addClass('hidden');
            $('#seccionFormulariosEnvSegLog').addClass('hidden');
            $('#seccionFormulariosRecepcionLogistica').addClass('hidden');
            $('#seccionFormulariosRevisionHistorial').addClass('hidden');
            $('#seccionFormulariosRecepcionLaboratorio').addClass('hidden');
            $('#seccionFormulariosRecepcionAlmacen').addClass('hidden');
            $('#seccionFormulariosAsignacionGuiaLogistica').addClass('hidden');
            $('#seccionFormulariosAsignacionGuia').addClass('hidden');
            $('#seccionFormulariosGuiaLogistica').addClass('hidden');
            $('#seccionFormulariosSinGuia').addClass('hidden');
            $('#seccionFormulariosGuia').addClass('hidden');
            $('#seccionFormulariosValidacion').addClass('hidden');
            $('.btnRegresarTabla').addClass('hidden');
        });

        $("#listaTicket").on("change", function () {
            select.cambiarOpcion("#listaServicio", '');
            if ($(this).val() !== '') {
                var datos = {
                    'ticket': $(this).val()
                }

                evento.enviarEvento('Seguimiento/ConsultaServiciosTecnico', datos, '#panelValidacion', function (respuesta) {
                    $('#listaServicio').empty().append('<option value="" data-idModelo = "" data-serie="">Seleccionar</option>').attr('disabled', 'disabled');

                    $.each(respuesta, function (k, v) {
                        $("#listaServicio").append('<option value="' + v.Id + '" data-idModelo = "' + v.IdModelo + '" data-serie="' + v.Serie + '">' + v.Id + ' - ' + v.Descripcion + '</option>');
                    });
                    $("#listaServicio").removeAttr("disabled");
                });
            } else {
                $("#listaServicio").empty().append('<option value="">Seleccionar...</option>');
                $("#listaServicio").attr("disabled", "disabled");
            }
        });

        $("#listaServicio").on("change", function () {
            var servicioSeleccionado = $('#listaServicio option:selected').attr('data-idModelo');

            if (servicioSeleccionado !== '') {
                var datos = {'idModelo': servicioSeleccionado};

                select.cambiarOpcion('#listaTipoPersonal', '');
                evento.enviarEvento('Seguimiento/MostrarEquipoDanado', datos, '#panelValidacion', function (respuesta) {

                    if (respuesta.length > 0) {
                        $.each(respuesta, function (k, v) {
                            $('#equipoEnviado').empty().attr({"value": v.Equipo, "data-IdEquipo": v.Id});
                        });
                        $('#listaTipoPersonal').removeAttr('disabled');
                    }
                });

                if ($(this).val() !== '') {
                    $("#listaTipoPersonal").removeAttr("disabled");
                } else {
                    select.cambiarOpcion('#listaTipoPersonal', '');
                    $("#listaTipoPersonal").attr("disabled", "disabled");
                }
            }
        });

        $('#listaTipoPersonal').on('change', function () {
            var seleccionado = $('#listaTipoPersonal option:selected').val();
            var datos = {'idTipoPersonal': seleccionado};

            $('#listaNombrePersonal').empty().append('<option value="">Seleccionar</option>').attr('disabled', 'disabled');

            select.cambiarOpcion('#listaNombrePersonal', '');
            evento.enviarEvento('Seguimiento/MostrarNombrePersonalValida', datos, '#panelValidacion', function (respuesta) {
                if (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $('#listaNombrePersonal').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                }
                if (respuesta.length > 0) {
                    $('#listaNombrePersonal').removeAttr('disabled');
                    $('#fechaValidacion').removeAttr('disabled');
                    $('input[type=radio][name=movimiento]').removeAttr('disabled');
                }
            });
            $('#listaNombrePersonal').attr('disabled', 'disabled');
            $('input[type=radio][name=movimiento]').attr('disabled', 'disabled');

            var disabledRadio = $('input[type=radio][name=movimiento]').attr('disabled');

            if (disabledRadio === 'disabled') {
                $('#divEquipoEnvio').addClass('hidden');
                $('.divRefaccionEquipo').addClass('hidden');
                $("input[name='movimiento']").removeAttr('checked');
            }
        });

        $('input[type=radio][name=movimiento]').change(function () {

            switch (this.value) {
                case '1':
                    $('#divEquipoEnvio').removeClass('hidden');
                    $('.divRefaccionEquipo').addClass('hidden');
                    select.cambiarOpcion('#listaSolicitarEquipo', '');
                    select.cambiarOpcion('#listaSolicitarRefaccion', '');
                    $("#inputMovimiento").attr('value', '1');
                    break;
                case '2':
                    $('#divEquipoEnvio').removeClass('hidden');
                    $('.divRefaccionEquipo').addClass('hidden');
                    select.cambiarOpcion('#listaSolicitarEquipo', '');
                    select.cambiarOpcion('#listaSolicitarRefaccion', '');
                    $("#inputMovimiento").attr('value', '2');
                    break;
                case '3':
                    $('#divEquipoEnvio').addClass('hidden');
                    $('.divRefaccionEquipo').removeClass('hidden');
                    $('#listaSolicitarEquipo').removeAttr('disabled');
                    $("#inputMovimiento").attr('value', '3');
                    break;
                default:
                    break;
            }
        });

        $('#listaSolicitarEquipo').on('change', function () {
            var seleccionado = $('#listaSolicitarEquipo option:selected').val();
            var datos = {'idEquipo': seleccionado};
            panel = $('#panelValidacion');

            $('#listaSolicitarRefaccion').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaSolicitarRefaccion', '');

            evento.enviarEvento('Seguimiento/MostrarRefaccionXEquipo', datos, panel, function (respuesta) {
                if (respuesta.length > 0) {
                    $.each(respuesta, function (k, v) {
                        $('#listaSolicitarRefaccion').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    $('#listaSolicitarRefaccion').removeAttr('disabled');
                }
            });

            $('#listaSolicitarRefaccion').attr('disabled', 'disabled');
        });

        $('#btnGuardarValidacion').off('click');
        $('#btnGuardarValidacion').on('click', function () {
            var arrayCampos = [
                {'objeto': '#listaTicket', 'mensajeError': 'Falta seleccionar el ticket.'},
                {'objeto': '#listaServicio', 'mensajeError': 'Falta seleccionar el servicio.'},
                {'objeto': '#listaTipoPersonal', 'mensajeError': 'Falta seleccionar el tipo de personal que valida.'},
                {'objeto': '#listaNombrePersonal', 'mensajeError': 'Falta seleccionar el personal que valida.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioValidacion');

            if (camposFormularioValidados) {
                var tipoMovimiento = $('#inputMovimiento').val();
                var IdServicio = $('#listaServicio').val();
                var IdPersonalValida = $('#listaNombrePersonal').val();
                var FechaValidacion = $("#fechaValidacion").val();
                var IdTipoMovimiento = $("input[name='movimiento']:checked").val();
                var IdModelo = $('#listaServicio').find(':selected').attr('data-idmodelo');
                var Serie = $('#listaServicio').find(':selected').attr('data-serie');
                var IdTipoPersonal = $('#listaTipoPersonal').val();
                var equipoEnviado = $("#equipoEnviado").attr('data-IdEquipo');

                var datosValidacion = {'IdServicio': IdServicio,
                    'IdPersonalValida': IdPersonalValida,
                    'FechaValidacion': FechaValidacion,
                    'IdTipoMovimiento': IdTipoMovimiento,
                    'IdModelo': IdModelo,
                    'Serie': Serie,
                    'IdRefaccion': null,
                    'equipoEnviado': equipoEnviado,
                    'IdTipoPersonal': IdTipoPersonal};

                if (tipoMovimiento !== '') {
                    switch (tipoMovimiento) {
                        case '1':
                        case '2':
                            if (evento.validarFormulario('#formValidacion')) {
                                botonGuardarValidacion(datosValidacion, idTabla);
                            }
                            break;
                        case '3':
                            var idEquipoEnviado = validarEquipo();

                            if (idEquipoEnviado !== '') {
                                datosValidacion.IdModelo = idEquipoEnviado.seleccionEquipo;
                                datosValidacion.IdRefaccion = idEquipoEnviado.selectEquipoRefaccion || null;
                                datosValidacion.Serie = null;

                                botonGuardarValidacion(datosValidacion, idTabla);
                            } else {
                                evento.mostrarMensaje("#errorFormularioValidacion", false, "Selecciona equipo solicitado", 4000);
                            }
                            break;
                        default:
                            evento.validarFormulario('#formValidacion');
                    }
                } else {
                    evento.mostrarMensaje("#errorFormularioValidacion", false, "Selecciona el movimiento que va a realizar.", 4000);
                }
            }
        });

        $('#btnGuardarEnvio').off('click');
        $('#btnGuardarEnvio').on('click', function () {
            panel = $('#panelEnvioConGuia').val();
            var paqueteria = $('#listPaqueteria option:selected').val();
            var guia = $('#guia').val();
            var fecha = $('#fechaValidacion').val();
            var evidencia = $('#evidenciaEnvioGuia').val();
            var idServicio = $('#inputServicio').attr('data-idServicio');

            if (guia === '') {
                guia = $('#guiaColocada').val();
            }
            
            var datos = {'IdPaqueteria': paqueteria, 'Guia': guia, 'Fecha': fecha, 'idServicio': idServicio};

            if (evento.validarFormulario('#formEnvioAlmacen')) {
                if (evidencia !== '' || evidencia !== undefined) {
                    file.enviarArchivos('#evidenciaEnvioGuia', 'Seguimiento/GuardarEnvioAlmacen', '#panelEnvioConGuia', datos, function (respuesta) {
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(respuesta.idTabla);
                        eventosComentarios(respuesta.idTabla);
                    });
                } else {
                    evento.enviarEvento('Seguimiento/GuardarEnvioAlmacen', datos, panel, function (respuesta) {
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(respuesta.idTabla, respuesta.idServicio);
                        eventosComentarios(respuesta.idTabla, respuesta.idServicio);
                    });
                }
            } else {
                evento.mostrarMensaje("#errorFormularioEnvio", false, "Ingresa los datos solicitados", 4000);
            }
        });

        $('#btnGuardarRecepcionTec').off('click');
        $('#btnGuardarRecepcionTec').on('click', function () {
            var arrayCampos = [
                {'objeto': '#fechaRecepcionTecnico', 'mensajeError': 'Falta seleccionar la Fecha de Recepción.'},
                {'objeto': '#evidenciaRecepcionTecnico', 'mensajeError': 'Falta seleccionar la evidencia de recepción.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioTecnico');

            if (camposFormularioValidados) {
                var data = {
                    'id': idTabla,
                    'idServicio': idServicio
                }

                file.enviarArchivos('#evidenciaRecepcionTecnico', 'Seguimiento/GuardarRecepcionTecnico', '#panelRecepcionTecnico', data, function (respuesta) {
                    if (respuesta.code == 200) {
                        location.reload();
                    } else {
                        evento.mostrarMensaje("#errorFormularioTecnico", false, "Ocurrió un error al guardar el comentario. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            }
        });

        $('#btnGuardarRecepcionLog').off('click');
        $('#btnGuardarRecepcionLog').on('click', function () {
            var arrayCampos = [
                {'objeto': '#fechaRecepcionLogistica', 'mensajeError': 'Falta seleccionar la Fecha de Recepción.'},
                {'objeto': '#evidenciaRecepcionLogistica', 'mensajeError': 'Falta seleccionar la evidencia de recepción.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioLogistica');

            if (camposFormularioValidados) {
                var data = {
                    'id': idTabla,
                    'idServicio': idServicio
                }

                file.enviarArchivos('#evidenciaRecepcionLogistica', 'Seguimiento/GuardarRecepcionLogistica', '#panelRecepcionLogistica', data, function (respuesta) {
                    if (respuesta.code == 200) {
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(idTabla, respuesta.idServicio);
                        eventosComentarios(idTabla, respuesta.idServicio);
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                    }
                });
            }
        });

        $('#btnGuardarRecepcionAlm').off('click');
        $('#btnGuardarRecepcionAlm').on('click', function () {
            var arrayCampos = [
                {'objeto': '#fechaRecepcionAlm', 'mensajeError': 'Falta seleccionar la Fecha de Recepción.'},
                {'objeto': '#evidenciaRecepcionAlmacen', 'mensajeError': 'Falta seleccionar la evidencia de recepción.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioAlmacen');

            if (camposFormularioValidados) {
                var data = {
                    'id': idTabla,
                    'idServicio': idServicio
                }

                file.enviarArchivos('#evidenciaRecepcionAlmacen', 'Seguimiento/GuardarRecepcionAlmacen', '#panelRecepcionAlmacen', data, function (respuesta) {
                    if (respuesta.code == 200) {
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(idTabla, respuesta.idServicio);
                        eventosComentarios(idTabla, respuesta.idServicio);
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                    }
                });
            }
        });

        $('#btnGuardarRecepcionLab').off('click');
        $('#btnGuardarRecepcionLab').on('click', function () {
            var arrayCampos = [
                {'objeto': '#fechaRecepcionAlm', 'mensajeError': 'Falta seleccionar la Fecha de Recepción.'},
                {'objeto': '#evidenciaRecepcionLab', 'mensajeError': 'Falta seleccionar la evidencia de recepción.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioLaboratorio');

            if (camposFormularioValidados) {
                var data = {
                    'id': idTabla,
                    'idServicio': idServicio
                }

                file.enviarArchivos('#evidenciaRecepcionLab', 'Seguimiento/GuardarRecepcionLaboratorio', '#panelRecepcionLaboratorio', data, function (respuesta) {
                    if (respuesta.code == 200) {
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(idTabla, respuesta.idServicio);
                        eventosComentarios(idTabla, respuesta.idServicio);
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                    }
                });
            }
        });

        $('#btnAgregarRefaccion').off('click');
        $('#btnAgregarRefaccion').on('click', function () {
            var arrayCampos = [
                {'objeto': '#listRefaccionUtil', 'mensajeError': 'Falta seleccionar la Refacción utilizada.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorAgregarRefaccion');

            if (camposFormularioValidados) {
                var idInventario = $('#listRefaccionUtil').val();
                var cantidad = '1';
                var data = {
                    'id': idTabla,
                    'idInvetario': idInventario,
                    'cantidad': cantidad,
                    'idServicio': idServicio
                }

                evento.enviarEvento('Seguimiento/GuardarRefacionUtilizada', data, '#panelLaboratorioHistorial', function (respuesta) {
                    if (respuesta.code === 200) {
                        $('#cantidadRefaccion').val('');
                        select.cambiarOpcion('#listRefaccionUtil', '');
                        recargandoTablaRefaccionesUtilizadas(respuesta.datos);
                        $("#listRefaccionUtil").empty().append('<option value="">Seleccionar...</option>');
                        $.each(respuesta.componentesEquipo, function (k, v) {
                            $("#listRefaccionUtil").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        evento.mostrarMensaje("#errorAgregarRefaccion", true, 'Refacción agregada correctamente.', 4000);
                    } else {
                        evento.mostrarMensaje("#errorAgregarRefaccion", false, respuesta.mensaje, 4000);
                    }
                });
            }
        });

        $('#consluirRevisionLab').off('click');
        $('#consluirRevisionLab').on('click', function () {
            var data = {
                'id': idTabla,
                'idServicio': idServicio
            }

            evento.enviarEvento('Seguimiento/ConcluirRevicionLaboratorio', data, '#panelLaboratorioHistorial', function (respuesta) {
                if (respuesta.code === 200) {
                    vistasDeFormularios(respuesta.datos);
                    incioEtiquetas();
                    eventosGenerales(idTabla, respuesta.idServicio);
                    eventosComentarios(idTabla, respuesta.idServicio);
                    cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
                    recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                } else {
                    evento.mostrarMensaje("#errorConcluirRevision", false, respuesta.mensaje, 4000);
                }
            });
        });

        $('#btnGuardarEnvioLogistica').off('click');
        $('#btnGuardarEnvioLogistica').on('click', function () {
            var tipoEnvio = $('input[name=radioTipoEnvio]:checked').val();
            if (tipoEnvio === '1') {
                var paqueteria = $('#listPaqueteria option:selected').val();
                if (paqueteria === '2') {
                    var cuenta = $('input[name=radioCuenta]:checked').val();
                    var arrayCampos = [
                        {'objeto': 'input[name=radioTipoEnvio]:checked', 'mensajeError': 'Falta seleccionar tipo de envió'},
                        {'objeto': '#listPaqueteria', 'mensajeError': 'Falta seleccionar la paqueteria utilizada.'},
                        {'objeto': '#fechaEnvio', 'mensajeError': 'Falta seleccionar la fecha.'},
                        {'objeto': '#guiaLogistica', 'mensajeError': 'Falta la guía.'},
                        {'objeto': 'input[name=radioCuenta]:checked', 'mensajeError': 'Falta seleccionar el tipo de cuenta'}
                    ];
                    var datos = {
                        'id': idTabla,
                        'idServicio': idServicio,
                        'paqueteria': $('#listPaqueteria').val(),
                        'guia': $('#guiaLogistica').val(),
                        'fechaEnvio': $('#fechaEnvio').val(),
                        'cuenta': cuenta,
                        'tipoEnvio': tipoEnvio
                    }
                } else {
                    var arrayCampos = [
                        {'objeto': 'input[name=radioTipoEnvio]:checked', 'mensajeError': 'Falta seleccionar tipo de envió'},
                        {'objeto': '#listPaqueteria', 'mensajeError': 'Falta seleccionar la paqueteria utilizada.'},
                        {'objeto': '#fechaEnvio', 'mensajeError': 'Falta seleccionar la fecha.'},
                        {'objeto': '#guiaLogistica', 'mensajeError': 'Falta la guía.'}
                    ];
                    var datos = {
                        'id': idTabla,
                        'idServicio': idServicio,
                        'paqueteria': $('#listPaqueteria').val(),
                        'guia': $('#guiaLogistica').val(),
                        'fechaEnvio': $('#fechaEnvio').val(),
                        'tipoEnvio': tipoEnvio
                    }
                }
            } else {
                var arrayCampos = [
                    {'objeto': 'input[name=radioTipoEnvio]:checked', 'mensajeError': 'Falta seleccionar tipo de envió'},
                    {'objeto': '#listChofer', 'mensajeError': 'Falta seleccionar el chofer.'},
                    {'objeto': '#fechaEnvio', 'mensajeError': 'Falta seleccionar la fecha.'}
                ];
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'chofer': $('#listChofer').val(),
                    'fechaEnvio': $('#fechaEnvio').val(),
                    'tipoEnvio': tipoEnvio
                }
            }

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioEnvioLogistica');

            if (camposFormularioValidados) {
                var evidencia = $('#evidenciaEnvio').val();

                if (evidencia !== '' || evidencia !== undefined) {
                    file.enviarArchivos('#evidenciaEnvio', 'Seguimiento/GuardarEnvioLogistica', '#panelEnvioSeguimientoLog', datos, function (respuesta) {
                        if (respuesta.code === 200) {
                            $('#divSeguimientoEntrega').removeClass('hidden');
                            $('#divBotonGuardarEntrega').removeClass('hidden');
                            $('#listPaqueteria').attr('disabled', 'disabled');
                            $('#fechaEnvio').attr('disabled', 'disabled');
                            $('#guiaLogistica').attr('disabled', 'disabled');
                            $('#btnGuardarEnvioLogistica').addClass('disabled');
                        }
                    });
                } else {
                    evento.enviarEvento('Seguimiento/GuardarEnvioLogistica', datos, panel, function (respuesta) {
                        if (respuesta.code === 200) {
                            $('#divSeguimientoEntrega').removeClass('hidden');
                            $('#divBotonGuardarEntrega').removeClass('hidden');
                            $('#listPaqueteria').attr('disabled', 'disabled');
                            $('#fechaEnvio').attr('disabled', 'disabled');
                            $('#guiaLogistica').attr('disabled', 'disabled');
                            $('#btnGuardarEnvioLogistica').addClass('disabled');
                            file.deshabilitar('#evidenciaEnvio');
                        }
                    });
                }
            }
        });

        $('#listDondeRecibe').on('change', function () {
            if ($(this).val() === '2') {
                $('#selectSucursal').removeClass('hidden');
            } else {
                $('#selectSucursal').addClass('hidden');
            }
        });

        $('#btnGuardarEntrega').off('click');
        $('#btnGuardarEntrega').on('click', function () {
            if ($('#listDondeRecibe').val() === '2') {
                var arrayCampos = [
                    {'objeto': '#listDondeRecibe', 'mensajeError': 'Falta seleccionar donde recibe.'},
                    {'objeto': '#listSucursal', 'mensajeError': 'Falta seleccionar la sucursal.'},
                    {'objeto': '#fechaEnvioSegLog', 'mensajeError': 'Falta la fecha.'},
                    {'objeto': '#personaRecibe', 'mensajeError': 'Falta la persona que recibe.'},
                    {'objeto': '#evidenciaEntregaLog', 'mensajeError': 'Falta seleccionar la evidencia de entrega.'}
                ];
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'tipoLugarRecepcion': $('#listDondeRecibe').val(),
                    'sucursal': $('#listSucursal').val(),
                    'fechaRecepcion': $('#fechaEnvioSegLog').val(),
                    'recibe': $('#personaRecibe').val()
                }
            } else {
                var arrayCampos = [
                    {'objeto': '#listDondeRecibe', 'mensajeError': 'Falta seleccionar donde recibe.'},
                    {'objeto': '#fechaEnvioSegLog', 'mensajeError': 'Falta la fecha.'},
                    {'objeto': '#personaRecibe', 'mensajeError': 'Falta la parsona que recibe.'},
                    {'objeto': '#evidenciaEntregaLog', 'mensajeError': 'Falta seleccionar la evidencia de entrega..'}
                ];
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'tipoLugarRecepcion': $('#listDondeRecibe').val(),
                    'sucursal': null,
                    'fechaRecepcion': $('#fechaEnvioSegLog').val(),
                    'recibe': $('#personaRecibe').val()
                }
            }

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarEntrega');

            if (camposFormularioValidados) {
                file.enviarArchivos('#evidenciaEntregaLog', 'Seguimiento/GuardarEntregaLogistica', '#panelEnvioSeguimientoLog', datos, function (respuesta) {
                    vistasDeFormularios(respuesta.datos);
                    incioEtiquetas();
                    eventosGenerales(idTabla, respuesta.idServicio);
                    eventosComentarios(idTabla, respuesta.idServicio);
                    cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
                    recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                });
            }

        });

        $('#solicitarGuia').off('click');
        $('#solicitarGuia').on('click', function () {
            var dataToShowTheForm = {
                'idService': idServicio
            }
            evento.enviarEvento('Seguimiento/MostrarFormularioInformacionGeneracionGuia', dataToShowTheForm, '#panelEnvioConGuia', function (respuesta) {
                evento.iniciarModal('#modalEdit', 'Información para generar guía', respuesta.modal);
                select.crearSelect('#lista-TI');

                $("#inputNumeroCajas").bind('keyup mouseup', function () {
                    createChecklistInformation();
                });

                $('#btnGuardarCambios').off('click');
                $('#btnGuardarCambios').on('click', function () {
                    var informationGuide = creatingInformationGenerateGuide();

                    var data = {'id': idTabla, 'idServicio': idServicio, informationGuide: informationGuide};
                    evento.enviarEvento('Seguimiento/SolicitarGuia', data, '#modalEdit', function (respuesta) {
                        if (respuesta.code === 200) {
                            vistasDeFormularios(respuesta.datos);
                            incioEtiquetas();
                            eventosGenerales(idTabla, respuesta.idServicio);
                            eventosComentarios(idTabla, respuesta.idServicio);
                            cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
                            recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                            evento.cerrarModal();
                            evento.terminarModal('#modalEdit');
                        }
                    });
                });
            });


        });

        $('#btnGuardarProblema').off('click');
        $('#btnGuardarProblema').on('click', function () {
            var arrayCampos = [
                {'objeto': '#txtComentariosGuia', 'mensajeError': 'Falta escribir comentarios.'},
                {'objeto': '#archivosProblemaGuia', 'mensajeError': 'Falta seleccionar la evidencia.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarGuiaLogistica');

            if (camposFormularioValidados) {
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'comentarios': $('#txtComentariosGuia').val(),
                    'idEstatus': 27,
                    'flag': '1'
                }

                file.enviarArchivos('#archivosProblemaGuia', 'Seguimiento/GuardarProblemaGuiaLogistica', '#panelAsignacionGuia', datos, function (respuesta) {
                    vistasDeFormularios(respuesta.datos);
                    incioEtiquetas();
                    eventosGenerales(idTabla, respuesta.idServicio);
                    eventosComentarios(idTabla, respuesta.idServicio);
                    cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
                    recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                });
            }
        });

        $('#btnGuardarSolicitud').off('click');
        $('#btnGuardarSolicitud').on('click', function () {
            var arrayCampos = [
                {'objeto': '#txtGuia', 'mensajeError': 'Falta escribir la Guía.'},
                {'objeto': '#txtComentariosGuia', 'mensajeError': 'Falta escribir comentarios.'},
                {'objeto': '#archivosProblemaGuia', 'mensajeError': 'Falta seleccionar la evidencia.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorGuardarGuiaLogistica');

            if (camposFormularioValidados) {
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'guia': $('#txtGuia').val(),
                    'comentarios': $('#txtComentariosGuia').val(),
                    'idEstatus': 37,
                    'flag': '1'
                }

                file.enviarArchivos('#archivosProblemaGuia', 'Seguimiento/GuardarProblemaGuiaLogistica', '#panelAsignacionGuia', datos, function (respuesta) {
                    vistasDeFormularios(respuesta.datos);
                    incioEtiquetas();
                    eventosGenerales(idTabla, respuesta.idServicio);
                    eventosComentarios(idTabla, respuesta.idServicio);
                    cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
                    recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                });
            }
        });

        $('#btnValidarSolicitud').off('click');
        $('#btnValidarSolicitud').on('click', function () {
            var data = {'id': idTabla, 'idServicio': idServicio, 'tipoValidacion': 'validar', 'cobrable': '0', 'idEstatus': '7'};
            botonValidacionSupervisor(data, idTabla);
        });

        $('#btnCobrarRefaccion').off('click');
        $('#btnCobrarRefaccion').on('click', function () {
            var data = {'id': idTabla, 'idServicio': idServicio, 'tipoValidacion': 'cobrar', 'cobrable': '1', 'idEstatus': '7'};
            botonValidacionSupervisor(data, idTabla);
        });

        var listaIds = [];

        $('#lista-solicitud-producto').on('change', 'input.editor-active', function () {
            var dataId = $(this).attr('data-id');

            if ($(this).is(":checked")) {
                listaIds.push(dataId);
            } else {
                listaIds.splice($.inArray(dataId, listaIds), 1);
            }
        });

        $('#btnTerminarSeleccionLocal').off('click');
        $('#btnTerminarSeleccionLocal').on('click', function () {
            if (listaIds.length > 0) {
                var data = {'listaProductos': listaIds, 'id': idTabla, 'idServicio': idServicio, 'idEstatus': '38', 'flag': '1'};
                terminarSeleccion(data, idTabla);
            } else {
                evento.mostrarMensaje("#errorSolicitudProducto", false, 'Seleccione un producto.', 4000);
            }
        });

        $('#btnTerminarSeleccionForaneo').off('click');
        $('#btnTerminarSeleccionForaneo').on('click', function () {
            if (listaIds.length > 0) {
                var data = {'listaProductos': listaIds, 'id': idTabla, 'idServicio': idServicio, 'idEstatus': '38', 'flag': '0'};
                terminarSeleccion(data, idTabla);
            } else {
                evento.mostrarMensaje("#errorSolicitudProducto", false, 'Seleccione un producto.', 4000);
            }
        });

        $('#listaRefaccionUtilizada tbody').on('click', 'tr', function () {
            var datos = $('#listaRefaccionUtilizada').DataTable().row(this).data();
            if (datos !== undefined) {
                var data = {'id': datos[0], 'idInventario': datos[3], 'idServicio': idServicio};
                evento.enviarEvento('Seguimiento/EliminarRefacionUtilizada', data, '#panelLaboratorioHistorial', function (respuesta) {
                    if (respuesta.code === 200) {
                        recargandoTablaRefaccionesUtilizadas(respuesta.datos);
                        $("#listRefaccionUtil").empty().append('<option value="">Seleccionar...</option>');
                        $.each(respuesta.componentesEquipo, function (k, v) {
                            $("#listRefaccionUtil").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                        });
                        evento.mostrarMensaje("#errorAgregarRefaccion", true, 'Refacción eliminada correctamente.', 4000);
                    } else {
                        evento.mostrarMensaje("#errorAgregarRefaccion", false, respuesta.mensaje, 4000);
                    }
                });
            }
        });

        $('#solicitarLaboratorio').off('click');
        $('#solicitarLaboratorio').on('click', function () {
            var data = {'idServicio': idServicio};
            botonSolicitarLaboratorio(data, idTabla);
        });

        $('#btnTerminarSeleccionLaboratorio').off('click');
        $('#btnTerminarSeleccionLaboratorio').on('click', function () {
            if (listaIds.length > 0) {
                var data = {'listaProductos': listaIds, 'id': idTabla, 'idServicio': idServicio, 'idEstatus': '2', 'flag': '0'};
                botonTerminarSeleccionLaboratorio(data);
            } else {
                evento.mostrarMensaje("#errorSolicitudProducto", false, 'Seleccione un producto.', 4000);
            }
        });

        $('#listPaqueteria').on('change', function () {
            $('input[name="radioCuenta"]').attr('checked', false);

            var seleccionado = $('#listPaqueteria option:selected').val();

            if (seleccionado === '2') {
                $('#divCuentas').removeClass('hidden');
            } else {
                $('#divCuentas').addClass('hidden');
            }
        });

        $('.tipoEnvio').on('change', function () {
            var tipoEnvio = $('input[name=radioTipoEnvio]:checked').val();

            if (tipoEnvio === '1') {
                $('#divPaqueteria').removeClass('hidden');
                $('#divLogistica').addClass('hidden');
            } else {
                $('#divLogistica').removeClass('hidden');
                $('#divPaqueteria').addClass('hidden');
            }
        });

        $('#btnSolicitarCotizacionRevisionLaboratorio').off('click');
        $('#btnSolicitarCotizacionRevisionLaboratorio').on('click', function () {
            var data = {
                'servicio': idServicio,
                'estatus': 29
            }
            mandarCotizacion(data, idTabla, '#panelLaboratorioHistorial');
        });

        $('#solicitarCotizacion').off('click');
        $('#solicitarCotizacion').on('click', function () {
            var data = {
                'servicio': idServicio,
                'estatus': 2
            }
            mandarCotizacion(data, idTabla, '#panelValidacionExistencia');
        });

    };

    var mandarCotizacion = function () {
        var data = arguments[0];
        var idTabla = arguments[1];
        var panel = arguments[2];

        evento.enviarEvento('Seguimiento/crearDatosCotizarOpcionRevision', data, panel, function (respuesta) {
            evento.iniciarModal('#modalSolicitarCotizacion', 'Solicitar Cotización', respuesta.modal);
            $('input[type=radio][name=cotizacion]').change(function () {
                switch (this.value) {
                    case '1':
                        $('#equipoCompleto').removeClass('hidden');
                        $('#componentes').addClass('hidden');
                        break;
                    case '2':
                        $('#componentes').removeClass('hidden');
                        $('#equipoCompleto').addClass('hidden');
                        break;

                }
            });
            $("#btnAceptarSolicitarCotizacion").off("click");
            $("#btnAceptarSolicitarCotizacion").on("click", function () {
                var radioValue = $("input[name='cotizacion']:checked").val();
                var datosCotizarComp = [];

                var informacionCotizacion = $('#data-table-solicitar-componentes').DataTable().rows().data();
                for (var index = 0; index < informacionCotizacion.length; index++) {
                    datosCotizarComp.push({'componente': informacionCotizacion[index][0], 'cantidad': document.getElementById("inputCantidad" + index).value});
                }
                if (radioValue == 1) {
                    data['componentes'] = '';
                    datosCotizar = {'servicio': data};
                } else {
                    data['componentes'] = datosCotizarComp;
                    datosCotizar = {'servicio': data};
                }
                evento.enviarEvento('Seguimiento/enviarDatosCotizarOpcionRevision', datosCotizar, panel, function (respuesta) {
                    if (respuesta) {
                        location.reload();
                    } else {
                        evento.mostrarMensaje('.errorModalSolicitarCotizacion', false, 'Hubo un problema con la solicitud.', 3000);
                    }
                });
            });
        });
        /*evento.enviarEvento('Seguimiento/crearDatosCotizarOpcionRevision', data, panel, function (respuesta) {
         if (respuesta.code === 200) {
         vistasDeFormularios(respuesta.datos);
         incioEtiquetas();
         eventosGenerales(idTabla, respuesta.idServicio);
         eventosComentarios(idTabla, respuesta.idServicio);
         cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
         }
         });*/
    }

    var terminarSeleccion = function () {
        var data = arguments[0];
        var idTabla = arguments[1];

        evento.enviarEvento('Seguimiento/GuardarSolicitudProducto', data, '#panelValidacionExistencia', function (respuesta) {
            if (respuesta.code === 200) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(idTabla, respuesta.idServicio);
                eventosComentarios(idTabla, respuesta.idServicio);
                cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
            }
        });
    }

    var botonValidacionSupervisor = function () {
        var data = arguments[0];
        var idTabla = arguments[1];

        evento.enviarEvento('Seguimiento/ValidarSolicitudEquipo', data, '#panelValidacionSolicitudRefaccion', function (respuesta) {
            if (respuesta.code === 200) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(idTabla, respuesta.idServicio);
                eventosComentarios(idTabla, respuesta.idServicio);
                cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
            }
        });
    }

    var botonGuardarValidacion = function () {
        var datos = arguments[0];
        panel = $('#panelValidacion');

        evento.enviarEvento('Seguimiento/GuardarValidacionTecnico', datos, panel, function (respuesta) {
            if (respuesta.code === 400) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(respuesta.idTabla, respuesta.idServicio);
                eventosComentarios(respuesta.idTabla, respuesta.idServicio);
                recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
            } else {
                evento.mostrarMensaje("#errorFormularioValidacion", false, respuesta.mensaje, 4000);
            }
        });
    };

    var botonSolicitarLaboratorio = function () {
        var data = arguments[0];
        var idTabla = arguments[1];

        evento.enviarEvento('Seguimiento/SolicitarRefaccionLaboratorio', data, '#panelValidacionExistencia', function (respuesta) {
            if (respuesta.code === 200) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(idTabla, respuesta.idServicio);
                eventosComentarios(idTabla, respuesta.idServicio);
            }
        });
    }

    var botonTerminarSeleccionLaboratorio = function () {
        var data = arguments[0];

        evento.enviarEvento('Seguimiento/AsignarRefaccionAlmacen', data, '#panelValidacionExistencia', function (respuesta) {
            if (respuesta.code === 200) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(data.idTabla, respuesta.idServicio);
                eventosComentarios(data.idTabla, respuesta.idServicio);
            }
        });
    }

    var validarEquipo = function () {
        var seleccionEquipo = $('#listaSolicitarEquipo option:selected').val();
        var selectEquipoRefaccion = $('#listaSolicitarRefaccion option:selected').val();

        if (seleccionEquipo !== "") {
            var equipoRefaccion = {'seleccionEquipo': seleccionEquipo, 'selectEquipoRefaccion': selectEquipoRefaccion};
            return equipoRefaccion;
        } else {
            evento.mostrarMensaje("#errorFormularioValidacion", false, "Selecciona el equipo solicitado", 4000);
        }
    };

    var eventosComentarios = function () {
        var idTabla = arguments[0];
        var idServicio = arguments[1];

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
                    cargaRecepcionesProblemas(idTabla, '39', '31', '#panelRecepcionTecnico', '#divNotasAdjuntosTecnico');
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
                    'comentarios': comentarios,
                    'idServicio': idServicio
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
                    'tipoProblema': 'almacen',
                    'idServicio': idServicio
                };

                file.enviarArchivos('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionAlmacen', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaAlm", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaAlmacen").val('').text('');
                        file.limpiar('#adjuntosProblemaAlm');
                        cargaRecepcionesProblemas(idTabla, '1', '28', '#panelRecepcionAlmacen', '#divNotasAdjuntosAlmacen');
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
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
                    'tipoProblema': 'laboratorio',
                    'idServicio': idServicio
                };

                file.enviarArchivos('#adjuntosProblemaLab', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionLaboratorio', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaLab", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaLaboratorio").val('').text('');
                        file.limpiar('#adjuntosProblemaLab');
                        cargaRecepcionesProblemas(idTabla, '2', '29', '#panelRecepcionLaboratorio', '#divNotasAdjuntosLaboratorio');
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
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
                    'tipoProblema': 'logistica',
                    'idServicio': idServicio
                };

                file.enviarArchivos('#adjuntosProblemaLog', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionLogistica', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaLog", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaLogistica").val('').text('');
                        file.limpiar('#adjuntosProblemaLog');
                        cargaRecepcionesProblemas(idTabla, '3', '30', '#panelRecepcionLogistica', '#divNotasAdjuntosLogistica');
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
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
                    'tipoProblema': 'tecnico',
                    'idServicio': idServicio
                };

                file.enviarArchivos('#adjuntosProblemaTec', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelRecepcionTecnico', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaTec", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNotaTecnico").val('').text('');
                        file.limpiar('#adjuntosProblemaTec');
                        cargaRecepcionesProblemas(idTabla, '39', '31', '#panelRecepcionTecnico', '#divNotasAdjuntosTecnico');
                        recargandoTablaEquiposEnviadosSolicitados(respuesta.tablaEquiposEnviadosSolicitados.datosTabla);
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaTec", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaTec", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
            }
        });
    }

    var cargaComentariosAdjuntos = function () {
        var idTabla = arguments[0];
        var formularioHistorialRefaccion = arguments[1] || 1;
        var datos = {
            'id': idTabla
        };

        if (formularioHistorialRefaccion.length !== 0) {
            evento.enviarEvento('Seguimiento/CargaComentariosAdjuntos', datos, '#panelLaboratorioHistorial', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#divComentariosAdjuntos").empty().append(respuesta.formulario);
                } else {
                    evento.mostrarMensaje("#errorAgregarComentario", false, respuesta.error, 4000);
                }
            });
        }
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
        $('#guia').attr('disabled', 'disabled');
        $('#fechaValidacion').attr('disabled', 'disabled');
        $('#IdUsuarioRecibe').attr('disabled', 'disabled');
        $('#fechaRecepcionAlm').attr('disabled', 'disabled');
        $('#fechaRecepcionTecnico').attr('disabled', 'disabled');
        $('#txtNotaAlmacen').attr('disabled', 'disabled');
        $('#txtNotaLaboratorio').attr('disabled', 'disabled');
        $('#txtNotaLogistica').attr('disabled', 'disabled');
        $('#txtNotaTecnico').attr('disabled', 'disabled');
        $('#comentariosObservaciones').attr('disabled', 'disabled');
        $('#cantidad').attr('disabled', 'disabled');
        $('#fechaRecepcionAlm').attr('disabled', 'disabled');
        $('#solicitarGuia').addClass('disabled');
        $('#btnGuardarEnvio').addClass('disabled');
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

    var recargandoTablaRefaccionesUtilizadas = function (refaccionesUtilizadas) {
        tabla.limpiarTabla('#listaRefaccionUtilizada');

        $.each(refaccionesUtilizadas, function (key, item) {
            tabla.agregarFila('#listaRefaccionUtilizada', [item.Id, item.Nombre, item.Cantidad, item.IdInventario]);
        });
    };

    var recargandoTablaEquiposEnviadosSolicitados = function (equipoEnviadosSolicitados) {
        tabla.limpiarTabla('#lista-equipos-enviados-solicitados');

        $.each(equipoEnviadosSolicitados, function (key, item) {
            tabla.agregarFila('#lista-equipos-enviados-solicitados', [item.Id, item.IdServicio, item.Ticket, item.NombreSucursal, item.Equipo, item.FechaValidacion, item.IdEstatus, item.NombreEstatus, item.IdRefaccion, item.Refaccion, item.TipoMovimiento]);
        });
    };

    var terminarSeleccion = function () {
        var data = arguments[0];
        var idTabla = arguments[1];

        evento.enviarEvento('Seguimiento/GuardarSolicitudProducto', data, '#panelValidacionExistencia', function (respuesta) {
            if (respuesta.code === 200) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(idTabla, respuesta.idServicio);
                eventosComentarios(idTabla, respuesta.idServicio);
                cargaComentariosAdjuntos(idTabla, respuesta.datos.formularioHistorialRefaccion);
            }
        });
    }

    var createChecklistInformation = function () {
        var cantidad = $("#inputNumeroCajas").val();
        var campos = $("#formInformationBoxes").children('div.classForm').length;
        var contador = 0;

        if (cantidad !== campos) {
            if (cantidad < campos) {
                contador = 0;
                $("#formInformationBoxes > div.classForm").each(function () {
                    var _this = $(this);
                    contador++;
                    if (contador > cantidad) {
                        _this.remove();
                    }
                });
            } else {
                for (var i = 0; i < cantidad; i++) {
                    if (!$("#formInformationBoxes").children('div.classForm').eq(i).length) {
                        $("#formInformationBoxes").append(htmlViewFormBoxes(i));
                    }
                }
            }
        }
    }

    var htmlViewFormBoxes = function (contador) {
        var html = `<div class="classForm">
                        <div class="row m-t-5">
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <div class="form-grup">
                                    <label class="f-w-600">Caja</label>
                                    <input type="text" value="#` + (contador + 1) + `" disabled="disabled" class="form-control f-s-16 text-center" />
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-5">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <label class="f-w-600">Peso *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="info-peso-` + (contador + 1) + `" data-parsley-required="true"/>
                                    <span class="input-group-addon">kg</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <label class="f-w-600">Largo *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="info-largo-` + (contador + 1) + `" data-parsley-required="true"/>
                                    <span class="input-group-addon">cm</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <label class="f-w-600">Ancho *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="info-ancho-` + (contador + 1) + `" data-parsley-required="true"/>
                                    <span class="input-group-addon">cm</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <label class="f-w-600">Alto *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="info-alto-` + (contador + 1) + `" data-parsley-required="true"/>
                                    <span class="input-group-addon">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>`;
        return html;
    }

    var creatingInformationGenerateGuide = function () {
        var dataToValidateForm = [
            {'objeto': '#inputOrigen', 'mensajeError': 'Falta escribir origen.'},
            {'objeto': '#inputDestino', 'mensajeError': 'Falta escribir destino.'},
            {'objeto': '#lista-TI', 'mensajeError': 'Falta seleccionar personal de TI que autoriza.'},
            {'objeto': '#inputNumeroCajas', 'mensajeError': 'Falta el no. de cajas.'}
        ];

        var validatedFormsFields = evento.validarCamposObjetos(dataToValidateForm, '#errorFormularioInformacionGeneracionGuia');
        if (validatedFormsFields) {
            if ($('#inputNumeroCajas').val() > 0) {
                if (evento.validarFormulario('#formInformationBoxes')) {
                    var noIncidente = $('#inputNoIncidente').val();
                    var nombreTecnico = $('#inputNombreTecnico').val();
                    var origen = $('#inputOrigen').val();
                    var destino = $('#inputDestino').val();
                    var personalAutoriza = $('#lista-TI').val();
                    var numeroCajas = $('#inputNumeroCajas').val();
                    var textoInformacionGuia = 'No. Incidente: ' + noIncidente + '\n';
                    textoInformacionGuia += 'Persona que solicita: ' + nombreTecnico + '\n';
                    textoInformacionGuia += 'Origen: ' + origen + '\n';
                    textoInformacionGuia += 'Destino: ' + destino + '\n';
                    textoInformacionGuia += 'Personal de TI que autoriza: ' + personalAutoriza + '\n';
                    textoInformacionGuia += 'No. Cajas: ' + numeroCajas + '\n';

                    for (var i = 1; i <= numeroCajas; i++) {
                        var peso = $('#info-peso-' + i).val();
                        var largo = $('#info-largo-' + i).val();
                        var ancho = $('#info-ancho-' + i).val();
                        var alto = $('#info-alto-' + i).val();

                        textoInformacionGuia += 'Caja: ' + i + '\n';
                        textoInformacionGuia += 'Peso: ' + peso + 'kg \n';
                        textoInformacionGuia += 'Largo: ' + largo + 'cm \n';
                        textoInformacionGuia += 'Ancho: ' + ancho + 'cm \n';
                        textoInformacionGuia += 'Alto: ' + alto + 'cm \n';
                    }

                    return textoInformacionGuia;
                }
            } else {
                evento.mostrarMensaje("#errorFormularioInformacionGeneracionGuia", false, 'El campo de no. cajas deber ser positivo', 4000);
            }
        }
    }
});
