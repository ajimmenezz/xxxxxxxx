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
            console.log(respuesta);

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
        $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formularioValidacion.formularioValidacion);

        if ($.inArray('306', respuesta.permisos) !== -1 || $.inArray('306', respuesta.permisosAdicionales) !== -1 || $.inArray('307', respuesta.permisos) !== -1 || $.inArray('307', respuesta.permisosAdicionales) !== -1) {
            bloquerTodosCampos();
        }
    };

    var eventosGenerales = function () {
        var idTabla = arguments[0];
        var idServicio = arguments[1];

        $('#btnRegresarTabla').off('click');
        $('#btnRegresarTabla').on('click', function () {
            $('#panelTablaEquiposEnviados').removeClass('hidden');
            $('#seccionFormulariosValidacion').addClass('hidden');
        });

        $("#listaTicket").on("change", function () {
            select.cambiarOpcion("#listaServicio", '');
            $("#listaServicio").empty().append('<option value="">Seleccionar...</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'ticket': $(this).val()
                }

                evento.enviarEvento('Seguimiento/ConsultaServiciosTecnico', datos, '#panelValidacion', function (respuesta) {
                    $.each(respuesta, function (k, v) {
                        $("#listaServicio").append('<option value="' + v.Id + '" data-idModelo = "' + v.IdModelo + '" data-serie="' + v.Serie + '">' + v.Id + ' - ' + v.Descripcion + '</option>');
                    });
                    $("#listaServicio").removeAttr("disabled");
                });
            } else {
                $("#listaServicio").attr("disabled", "disabled");
            }
        });

        $("#listaServicio").on("change", function () {

            var servicioSeleccionado = $(this).find(':selected').attr('data-idModelo');
            var datos = {'idModelo': servicioSeleccionado};

            select.cambiarOpcion('#listaTipoPersonal', '');
            evento.enviarEvento('Seguimiento/MostrarEquipoDanado', datos, panel, function (respuesta) {

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
        });

        // nuevo
        $('#listaTipoPersonal').on('change', function () {
            var seleccionado = $('#listaTipoPersonal option:selected').val();
            var datos = {'idTipoPersonal': seleccionado};

            $('#listaNombrePersonal').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaNombrePersonal', '');
            evento.enviarEvento('Seguimiento/MostrarNombrePersonalValida', datos, panel, function (respuesta) {
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

        });

        $('#btnGuardarEnvio').off('click');
        $('#btnGuardarEnvio').on('click', function () {
            panel = $('#panelEnvioConGuia').val();
            var paqueteria = $('#listPaqueteria option:selected').val();
            var guia = $('#guia').val();
            var fecha = $('#fechaValidacion').val();
            var evidencia = $('#evidenciaEnvioGuia').val();
            var idServicio = $('#inputServicio').attr('data-idServicio');
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
                        eventosGenerales(respuesta.idTabla);
                        eventosComentarios(respuesta.idTabla);
                    });
                }

            } else {
                evento.mostrarMensaje("#errorFormularioEnvio", false, "Ingresa los datos solicitados", 4000);
            }
        });

        // termina nuevo

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
                        vistasDeFormularios(respuesta.datos);
                        incioEtiquetas();
                        eventosGenerales(idTabla, respuesta.idServicio);
                        eventosComentarios(idTabla, respuesta.idServicio);
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
                    }
                });
            }
        });

        $('#btnAgregarRefaccion').off('click');
        $('#btnAgregarRefaccion').on('click', function () {
            var arrayCampos = [
                {'objeto': '#listRefaccionUtil', 'mensajeError': 'Falta seleccionar la Refacción utilizada.'},
                {'objeto': '#cantidadRefaccion', 'mensajeError': 'Falta escribir la cantidad.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorAgregarRefaccion');

            if (camposFormularioValidados) {
                var idInventario = $('#listRefaccionUtil').val();
                var cantidad = $('#cantidadRefaccion').val();
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
                        evento.mostrarMensaje("#errorAgregarRefaccion", true, 'Refacción agregada correctamente.', 4000);
                    } else {
                        evento.mostrarMensaje("#errorAgregarRefaccion", false, respuesta.mensaje, 4000);
                    }
                });
            }
        });

        $('#listaRefaccionUtilizada tbody').on('click', 'tr', function () {
            var datos = $('#listaRefaccionUtilizada').DataTable().row(this).data();
            if (datos !== undefined) {
                var data = {
                    'id': datos[0],
                    'idServicio': idServicio
                };

                evento.enviarEvento('Seguimiento/EliminarRefacionUtilizada', data, '#panelLaboratorioHistorial', function (respuesta) {
                    if (respuesta.code === 200) {
                        recargandoTablaRefaccionesUtilizadas(respuesta.datos);
                        evento.mostrarMensaje("#errorAgregarRefaccion", true, respuesta.mensaje, 4000);
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
                } else {
                    evento.mostrarMensaje("#errorConcluirRevision", false, respuesta.mensaje, 4000);
                }
            });
        });

        $('#btnGuardarEnvioLogistica').off('click');
        $('#btnGuardarEnvioLogistica').on('click', function () {
            var arrayCampos = [
                {'objeto': '#listPaqueteria', 'mensajeError': 'Falta seleccionar la paqueteria utilizada.'},
                {'objeto': '#fechaEnvio', 'mensajeError': 'Falta seleccionar la fecha.'},
                {'objeto': '#guiaLogistica', 'mensajeError': 'Falta la guía.'}
            ];

            var camposFormularioValidados = evento.validarCamposObjetos(arrayCampos, '#errorFormularioEnvioLogistica');

            if (camposFormularioValidados) {
                var evidencia = $('#evidenciaEnvio').val();
                var datos = {
                    'id': idTabla,
                    'idServicio': idServicio,
                    'paqueteria': $('#listPaqueteria').val(),
                    'guia': $('#guiaLogistica').val(),
                    'fechaEnvio': $('#fechaEnvio').val()
                }

                if (evidencia !== '' || evidencia !== undefined) {
                    file.enviarArchivos('#evidenciaEnvio', 'Seguimiento/GuardarEnvioLogistica', '#panelEnvioSeguimientoLog', datos, function (respuesta) {
                        $('#divSeguimientoEntrega').removeClass('hidden');
                        $('#divBotonGuardarEntrega').removeClass('hidden');
                        $('#listPaqueteria').attr('disabled', 'disabled');
                        $('#fechaEnvio').attr('disabled', 'disabled');
                        $('#guiaLogistica').attr('disabled', 'disabled');
                        $('#btnGuardarEnvioLogistica').addClass('disabled');
                    });
                } else {
                    evento.enviarEvento('Seguimiento/GuardarEnvioLogistica', datos, panel, function (respuesta) {
                        $('#divSeguimientoEntrega').removeClass('hidden');
                        $('#divBotonGuardarEntrega').removeClass('hidden');
                        $('#listPaqueteria').attr('disabled', 'disabled');
                        $('#fechaEnvio').attr('disabled', 'disabled');
                        $('#guiaLogistica').attr('disabled', 'disabled');
                        $('#btnGuardarEnvioLogistica').addClass('disabled');
                        file.deshabilitar('#evidenciaEnvio');
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
                    eventosGenerales(respuesta.idTabla);
                    eventosComentarios(respuesta.idTabla);
                });
            }

        });
    };

    // nuevo
    var botonGuardarValidacion = function () {
        var datos = arguments[0];
        var idTabla = arguments[1];
        panel = $('#panelValidacion');

        evento.enviarEvento('Seguimiento/GuardarValidacionTecnico', datos, panel, function (respuesta) {
            if (respuesta.code === 400) {
                vistasDeFormularios(respuesta.datos);
                incioEtiquetas();
                eventosGenerales(idTabla);
                eventosComentarios(idTabla);
            } else {
                evento.mostrarMensaje("#errorFormularioValidacion", false, respuesta.mensaje, 4000);
            }
        });
    };

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

    // termina nuevo

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
            tabla.agregarFila('#listaRefaccionUtilizada', [item.Id, item.Nombre, item.Cantidad]);
        });
    };

});
