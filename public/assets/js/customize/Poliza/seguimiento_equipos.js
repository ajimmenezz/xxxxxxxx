$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var servicios = new Servicio();
    var nota = new Nota();
    var dataCategoria;
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
        file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionlog', 'Seguimiento/subirAdjuntosLabHistorial');

        file.crearUpload('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos');
        file.crearUpload('#adjuntosProblemaLab', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#adjuntosProblemaLog', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEntregaLog', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEnvioGuia', 'Seguimiento/subirAdjuntosLabHistorial');

    };

    $('#agregarEquipo').off('click');
    $('#agregarEquipo').on('click', function () {
        var IdServicio = "";
        formulario(IdServicio);
    });

    $('#lista-equipos-enviados-solicitados tbody').on('click', 'tr', function () {
        var IdServicio = "";
        var IdRefaccion = "";
        var datos = $('#lista-equipos-enviados-solicitados').DataTable().row(this).data();
        if (datos !== undefined) {
            var idTabla = datos[0];
            IdServicio = datos[1];
            IdRefaccion = datos[8];
            formulario(IdServicio, IdRefaccion, idTabla);
        }
    });

    var formulario = function () {
        var idServicio = arguments[0];
        var IdRefaccion = arguments[1];
        var idTabla = arguments[2];
        var datos = {"idServicio": idServicio, 'IdRefaccion': IdRefaccion};
        evento.enviarEvento('Seguimiento/VistaPorPerfil', datos, panel, function (respuesta) {
            console.log(respuesta);

            $('#panelTablaEquiposEnviados').addClass('hidden');
//                $('#seccionFormulariosRecepcionTecnico').removeClass('hidden').empty().append(respuesta.formulario.formularioRecepcionTecnico);
            $('#seccionFormulariosEnvSegLog').removeClass('hidden').empty().append(respuesta.formularioEnvioSeguimientoLog.formularioEnvioSeguimientoLog);
            $('#seccionFormulariosRecepcionLogistica').removeClass('hidden').empty().append(respuesta.formularioRecepcionLog.formularioRecepcionLogistica);
            $('#seccionFormulariosRevisionHistorial').removeClass('hidden').empty().append(respuesta.formularioHistorialRefaccion.formularioRevisionHistorial);
            $('#seccionFormulariosRecepcionLaboratorio').removeClass('hidden').empty().append(respuesta.formularioRecepcionLab.formularioRecepcionLaboratorio);
            $('#seccionFormulariosRecepcionAlmacen').removeClass('hidden').empty().append(respuesta.formularioRecepcionAlmacen.formularioRecepcionAlmacen);
            $('#seccionPanelEspera').removeClass('hidden').empty().append(respuesta.PanelEspera.panelEspera);
            $('#seccionFormulariosGuia').removeClass('hidden').empty().append(respuesta.formularioEnvioAlmacen.formularioGuia);
            $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formularioValidacion.formularioValidacion);
            incioEtiquetas();
            eventosComentarios(idTabla);
            cargaComentariosAdjuntos(idTabla);

            $('#btnRegresarTabla').off('click');
            $('#btnRegresarTabla').on('click', function () {
                $('#panelTablaEquiposEnviados').removeClass('hidden');
                $('#seccionFormulariosValidacion').addClass('hidden');
            });

        });
    };

    var eventosComentarios = function (idTabla) {


        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#problmeasRecepcionAlm":
                    cargaRecepcionesProblemas(idTabla, '1', '28', '#panelRecepcionAlmacen', '#divNotasAdjuntos');
                    break;
//                case "#ConsumoMaterial":
//                    cargaConsumirMaterial();
//                    break;
//                case "#NotasAdjuntos":
//                    cargaNotasAdjuntos();
//                    break;
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
            var comentarios = $.trim($("#txtNota").val());
            var adjunto = $("#adjuntosProblemaAlm").val();

            if (comentarios !== '' || adjunto !== '') {
                var datos = {
                    'id': idTabla,
                    'comentarios': comentarios,
                    'tipoProblema': 'almacen'
                };

                file.enviarArchivos('#adjuntosProblemaAlm', 'Seguimiento/AgregarRecepcionesProblemasSeguimientosEquipos', '#panelLaboratorioHistorial', datos, function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.mostrarMensaje("#errorAgregarProblemaAlm", true, "Se ha guardado la nota correctamente", 6000);
                        $("#txtNota").val('').text('');
                        file.limpiar('#adjuntosProblemaAlm');
                        cargaRecepcionesProblemas(idTabla, '1', '28', '#panelRecepcionAlmacen', '#divNotasAdjuntos');
                    } else {
                        evento.mostrarMensaje("#errorAgregarProblemaAlm", false, "Ocurrió un error al guardar la nota. Por favor recargue su página y vuelva a intentarlo.", 4000);
                    }
                });
            } else {
                evento.mostrarMensaje("#errorAgregarProblemaAlm", false, "Al menos debe agregar la nota o un adjunto para poder agregar la información", 4000);
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

});