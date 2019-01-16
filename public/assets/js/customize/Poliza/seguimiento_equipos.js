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

        //Fecha y hora
//        $('#fechaValidacion').datetimepicker({
//            format: 'YYYY-MM-DD HH:mm:ss'
//        });

        //obtener valor fecha
        $("#fechaValidacion").val();
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
        file.crearUpload('#evidenciaEnvio', 'Seguimiento/subirEvidenciaEnvio');
        file.crearUpload('#evidenciaRecepcionAlmacen', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#evidenciaRecepcionLab', 'Seguimiento/subirEvidenciaRecepcion');
        file.crearUpload('#archivosLabHistorial', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionLogistica', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaEntrega', 'Seguimiento/subirAdjuntosLabHistorial');
        file.crearUpload('#evidenciaRecepcionTecnico', 'Seguimiento/subirAdjuntosLabHistorial');

    };

//  ------------------------------------------ NUEVA VALIDACION
    $('#agregarEquipo').off('click');
    $('#agregarEquipo').on('click', function () {
        evento.enviarEvento('Seguimiento/VistaPorPerfil', {}, panel, function (respuesta) {

            var ticketsTamano = respuesta.dataUsuario.ticketsEnProblemas.length;

            if (ticketsTamano > 1) {
                $('#panelTablaEquiposEnviados').addClass('hidden');
                $('#seccionFormulariosValidacion').removeClass('hidden').empty().append(respuesta.formulario);
                incioEtiquetas();

                $('#btnRegresarTabla').off('click');
                $('#btnRegresarTabla').on('click', function () {
                    $('#panelTablaEquiposEnviados').removeClass('hidden');
                    $('#seccionFormulariosValidacion').addClass('hidden');
                });

                selectTicket();
                guardarValidacion();
            } else {
                evento.mostrarMensaje("#errorFormulario", true, "No hay ningun Servicio en Problema", 4000);
            }
        });
    });

    var selectTicket = function () {
        $('#listaTicket').on('change', function () {
            var seleccionado = $('#listaTicket option:selected').val();
            var datos = {'idTicket': seleccionado};
            panel = $('#panelValidacion');

            $('#listaServicio').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaServicio', '');

            evento.enviarEvento('Seguimiento/MostrarServiciosUsuario', datos, panel, function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $('#listaServicio').append('<option value="' + v.Id + '" data-idModelo="' + v.IdModelo + '" data-serie="' + v.Serie + '">' + v.Id + " - " + v.Descripcion + '</option>');
                });
                if (respuesta.length > 0) {
                    $('#listaServicio').removeAttr('disabled');
                    selectServicio();
                }
            });
            $('#listaServicio').attr('disabled', 'disabled');
        });
    };

    var selectServicio = function () {
        $('#listaServicio').on('change', function () {
            var servicioSeleccionado = $(this).find(':selected').attr('data-idModelo');
            var datos = {'idServcio': servicioSeleccionado};

            select.cambiarOpcion('#listaTipoPersonal', '');
            evento.enviarEvento('Seguimiento/MostrarEquipoDanado', datos, panel, function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $('#equipoEnviado').empty().attr({"value": v.Equipo, "data-IdEquipo": v.Id});
                });

                if (respuesta.length > 0) {
                    $('#listaTipoPersonal').removeAttr('disabled');
                    selectTipoPersonal();
                }
            });

            $('#listaTipoPersonal').attr('disabled', 'disabled');

        });
    };

    var selectTipoPersonal = function () {
        $('#listaTipoPersonal').on('change', function () {
            var seleccionado = $('#listaTipoPersonal option:selected').val();
            var datos = {'idTipoPersonal': seleccionado};

            $('#listaNombrePersonal').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaNombrePersonal', '');
            evento.enviarEvento('Seguimiento/MostrarNombrePersonalValida', datos, panel, function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $('#listaNombrePersonal').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                });
                if (respuesta.length > 0) {
                    $('#listaNombrePersonal').removeAttr('disabled');
                    $('input[type=radio][name=movimiento]').removeAttr('disabled');
                    radioMovimiento();
                }
            });
            $('#listaNombrePersonal').attr('disabled', 'disabled');
//            $('#listaNombrePersonal').empty().removeAttr('checket');
            $('input[type=radio][name=movimiento]').attr('disabled', 'disabled');

            var disabledRadio = $('input[type=radio][name=movimiento]').attr('disabled');

            if (disabledRadio === 'disabled') {
                $('#divEquipoEnvio').addClass('hidden');
                $('.divRefaccionEquipo').addClass('hidden');
                $("input[name='movimiento']").removeAttr('checked');
            }

        });
    };

    var radioMovimiento = function () {

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
                    selectEquipoValidacion();
                    break;
                default:
                    break;
            }
        });
    };

    var selectEquipoValidacion = function () {
        $('#listaSolicitarEquipo').on('change', function () {
            var seleccionado = $('#listaSolicitarEquipo option:selected').val();
            var datos = {'idEquipo': seleccionado};
            panel = $('#panelValidacion');

            $('#listaSolicitarRefaccion').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaSolicitarRefaccion', '');

            evento.enviarEvento('Seguimiento/MostrarRefaccionXEquipo', datos, panel, function (respuesta) {
//                console.log(respuesta);
                if(respuesta){
                    $.each(respuesta, function (k, v) {
                        $('#listaSolicitarRefaccion').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                }
                if (respuesta.length > 0) {
                    $('#listaSolicitarRefaccion').removeAttr('disabled');
                }
            });

            $('#listaSolicitarRefaccion').attr('disabled', 'disabled');
        });
    };

    var guardarValidacion = function () {
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
                        botonGuardarValidacion(datosValidacion);
                    }
                    break;
                case '3':
                    var idEquipoEnviado = validarEquipo();

                    if (idEquipoEnviado !== '') {
                        datosValidacion.IdModelo = idEquipoEnviado.seleccionEquipo;
                        datosValidacion.IdRefaccion = idEquipoEnviado.selectEquipoRefaccion || null;
                        datosValidacion.Serie = null;

                        botonGuardarValidacion(datosValidacion);
                    } else {
                        evento.mostrarMensaje("#errorFormularioValidacion", false, "Selecciona equipo solicitado", 4000);
                    }
                    break;
                default:
                    evento.validarFormulario('#formValidacion');
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

    var botonGuardarValidacion = function () {
        var datos = arguments[0];
        panel = $('#panelValidacion');

//        evento.enviarEvento('Seguimiento/GuardarValidacionTecnico', datos, panel, function (respuesta) {
//            if (respuesta.code === 400) {
//                evento.mostrarMensaje("#errorFormularioValidacion", true, respuesta.mensaje, 4000);
                mostrarVistaValidacion(datos);
//            }
//        });
    };

    var mostrarVistaValidacion = function () {
        var datos = arguments[0];
        
        evento.enviarEvento('Seguimiento/MostrarVistaValidacionTecnico', datos, panel, function (respuesta) {
            console.log(respuesta);
        });

    };

});