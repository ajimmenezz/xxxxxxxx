$(function () {

    var evento = new Base();
    var peticion = new Utileria();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();

    App.init();

    let calendario = new Calendario('calendar');
    let permisos = [];

    peticion.enviar('', 'CalendarioPermisos/datosPermiso', null, function (respuesta) {
        let listaTemporalPermisos = {}, titulo = '', puesto = '';

        $.each(respuesta, function (key, value) {
            titulo = value.Usuario.substring(0, 8) + "...";
            puesto = value.Perfil.substring(0, 9) + "...";
            listaTemporalPermisos = {
                id: value.Id,
                title: titulo + "\n" + puesto,
                nombreUsuario: value.Usuario,
                perfil: value.Perfil,
                tipoAusencia: value.Ausencia,
                MotivoAusencia: value.Motivo,
                start: value.FechaAusenciaDesde,
                end: value.FechaAusenciaHasta,
                horaEntrada: value.HoraEntrada,
                horaSalida: value.HoraSalida,
                estatus: value.Estatus,
                description: value.Ausencia + ": " + value.Descripcion,
                autorizacionJefe: value.AutorizacionJefe,
                autorizacionRH: value.AutorizacionRH,
                autorizacionContador: value.AutorizacionContabilidad,
                archivo: value.Archivo,
                idPerfil: value.IdPerfil,
                idUsuario: value.IdUsuario,
                rechazo: value.Rechazo
            }
            permisos.push(listaTemporalPermisos)
        });
        calendario.cargarInformacionCalendario(permisos);
        calendario.setEventoClick(function () {
            console.log("click eventos");
        });
    });

//        //SE PINTA EL CALENDARIO
//        var handleCalendarDemo = function () {
//
//            $('#calendar').fullCalendar({
//                select: function (start, end, allDay) {
//                },
//                eventRender: function (event, element, calEvent) {
//                },
//                eventClick: function (calEvent) {
//                    $('#modalDatosPermiso').modal();
//                    $('#nombreUsuario').text(calEvent.usuarioEvento);
//                }
//            });
//
//        };
//
//        var Calendar = function () {
//            "use strict";
//            return {
//                init: function () {
//                    handleCalendarDemo();
//                }
//            };
//        }();
//
//        Calendar.init();
//
});