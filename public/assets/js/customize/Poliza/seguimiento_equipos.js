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
        $('#fechaValidacion').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        
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
                    $('#listaServicio').append('<option value="' + v.Id + '" data-idModelo="' + v.IdModelo + '">' + v.Id + " - " + v.Descripcion + '</option>');
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
//                console.log(respuesta);
//                var equipo = respuesta.Equipo;
                $.each(respuesta, function (k, v) {
                    $('#equipoEnviado').empty().attr("value", v.Equipo);
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
            $('#listaNombrePersonal').empty().removeAttr('checket');
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
        var radioMovimiento = $("input[name='movimiento']:checked").val();

        $('input[type=radio][name=movimiento]').change(function () {

            switch (this.value) {
                case 'foraneos':
                    $('#divEquipoEnvio').removeClass('hidden');
                    $('.divRefaccionEquipo').addClass('hidden');
                    select.cambiarOpcion('#listaSolicitarEquipo', '');
                    select.cambiarOpcion('#listaSolicitarRefaccion', '');
                    break;
                case 'locales':
                    $('#divEquipoEnvio').removeClass('hidden');
                    $('.divRefaccionEquipo').addClass('hidden');
                    select.cambiarOpcion('#listaSolicitarEquipo', '');
                    select.cambiarOpcion('#listaSolicitarRefaccion', '');
                    break;
                case 'EquipoRefaccion':
                    $('#divEquipoEnvio').addClass('hidden');
                    $('.divRefaccionEquipo').removeClass('hidden');
                    $('#listaSolicitarEquipo').removeAttr('disabled');
                    selectEquipo();
                    break;
                default:
                    break;
            }
        });
    };

    var selectEquipo = function () {
        $('#listaSolicitarEquipo').on('change', function () {
            var seleccionado = $('#listaSolicitarEquipo option:selected').val();
            var datos = {'idEquipo': seleccionado};
            panel = $('#panelValidacion');

            $('#listaSolicitarRefaccion').empty().append('<option value="">Seleccionar</option>');
            select.cambiarOpcion('#listaSolicitarRefaccion', '');

            evento.enviarEvento('Seguimiento/MostrarRefaccionXEquipo', datos, panel, function (respuesta) {
                $.each(respuesta, function (k, v) {
                    $('#listaSolicitarRefaccion').append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                });
                if (respuesta.length > 0) {
                    $('#listaSolicitarRefaccion').removeAttr('disabled');
                }
            });

            $('#listaSolicitarRefaccion').attr('disabled', 'disabled');
        });
    };

});