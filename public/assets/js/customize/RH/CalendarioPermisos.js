$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    //evento.mostrarAyuda('Ayuda_Proyectos');
    //SE MANDA LA PETICION DE DATOS
    let id = $('#spanID').html();
    let ids = new Array();
    let idUsuario= new Array();
    let idPerfil= new Array();
    let ausencia = new Array();
    let fechaDesde = new Array();
    let fechaHasta = new Array();
    let horaEntrada = new Array();
    let horaSalida = new Array();
    let descripcion = new Array();
    let archivo = new Array();


    let estatus = new Array();
    let usuario = new Array();
    let fechaAusenciaDesde = new Array();
    let fechaAusenciaHasta = new Array();

    let iteraciones;
    var eventosDinamicos;
    let colores = new Array();
    colores[0]= "bg-black";
    colores[1]= "bg-gray";
    colores[2]= "bg-aqua";
    colores[3]= "bg-blue";
    colores[4]= "bg-navy";
    colores[5]= "bg-teal";
    colores[6]= "bg-green";
    colores[7]= "bg-olive";
    colores[8]= "bg-orange";
    colores[9]= "bg-red";
    colores[10]= "bg-fuchsia";
    colores[11]= "bg-purple";
    colores[12]= "bg-maroon";
    colores[13]= "bg-darken-4";


    var datos = {
        id: id
    };

    evento.enviarEvento('CalendarioPermisos/datosPermiso', datos, '', function (respuesta) {
        //console.log(respuesta);
        iteraciones=respuesta.length;
        for (var i = 0; i < respuesta.length; i++) {
            ids[i]=respuesta[i].Id;

            ausencia[i] = respuesta[i].Ausencia;

            fechaDesde[i] = respuesta[i].FechaAusenciaDesde;

            fechaHasta[i] = respuesta[i].FechaAusenciaHasta;

            descripcion[i] = respuesta[i].Motivo;

            estatus[i]=respuesta[i].Estatus;

            usuario[i]=respuesta[i].Usuario;
            
            fechaAusenciaDesde[i]=respuesta[i].FechaAusenciaDesde;
            
            fechaAusenciaHasta[i]=respuesta[i].FechaAusenciaHasta;
            
            horaEntrada[i]=respuesta[i].HoraEntrada;
            
            horaSalida[i]=respuesta[i].HoraSalida;
            
            idUsuario[i]=respuesta[i].IdUsuario;

            idPerfil[i]=respuesta[i].IdPerfil;

            archivo[i]=respuesta[i].Archivo;


        }
        //SE PINTA EL CALENDARIO
        var handleCalendarDemo = function () {
            "use strict";
            var buttonSetting = {left: 'today prev,next ', center: 'title', right: 'month,agendaWeek,agendaDay'};
            var date = new Date();
            var m = date.getMonth();
            var y = date.getFullYear();

            var calendar = $('#calendar').fullCalendar({
                header: buttonSetting,
                selectable: true,
                selectHelper: true,
                droppable: false,
                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                },
                select: function (start, end, allDay) {
                },
                eventRender: function (event, element, calEvent) {
                    var mediaObject = (event.media) ? event.media : '';
                    var description = (event.description) ? event.description : '';
                    element.find(".fc-event-title").after($("<span class=\"fc-event-icons\"></span>").html(mediaObject));
                    element.find(".fc-event-title").append('<small>' + description + '</small>');
                },
                editable: true,
                events: [],
                eventClick: function(calEvent, jsEvent, view) {
                    $("#idPermiso").html(calEvent.id);
                    $('#usr').html(calEvent.usuarioEvento);
                    $('#sts').html(calEvent.estatusEvento);
                    $('#aus').html(calEvent.title);
                    $('#fed').html("<h5>Fecha de permiso: </h5>"+ calEvent.fechaAusenciaDesdeEvento);
                    if(calEvent.fechaAusenciaHastaEvento=="0000-00-00")
                    {
                        $('#fechaHasta').html(" ");
                    }
                    else
                    {
                        $('#feh').html("<h5>Fecha hasta: </h5>"+calEvent.fechaAusenciaHastaEvento);
                    }
                    if(calEvent.horaEntradaEvento=="00:00:00")
                    {
                        $('#hoe').html(" ");
                    }
                    else
                    {
                        $('#hoe').html("<h5>Hora entrada: </h5>"+calEvent.horaEntradaEvento);
                    }
                    if(calEvent.horaSalidaEvento=="00:00:00")
                    {
                        $('#hos').html(" ");
                    }
                    else
                    {
                        $('#hos').html("<h5>Hora salida: </h5>"+calEvent.horaSalidaEvento);
                    }

                    $('#jus').html(calEvent.description);
                    $('#mot').html(calEvent.description);
                    

                    $('#idus').html(calEvent.idUsuario);
                    $('#idper').html(calEvent.idPerfil);
                    $('#arc').html(calEvent.archivo);

                    $('#modalDatosPermiso').modal();

                    $(this).css('border-color', 'red');
                  }
            });
            /* initialize the external events
             -----------------------------------------------------------------*/
            $('#external-events .external-event').each(function () {
                var eventObject = {
                    title: $.trim($(this).attr('data-title')),
                    className: $(this).attr('data-bg'),
                    media: $(this).attr('data-media'),
                    description: $(this).attr('data-desc')
                };

                $(this).data('eventObject', eventObject);

                $(this).draggable({
                    zIndex: 999,
                    revert: true,
                    revertDuration: 0
                });
            });

        };

        var Calendar = function () {
            "use strict";
            //alert("Bien");
            return {
                //main function
                init: function () {
                    handleCalendarDemo();
                }
            };
        }();

        //Inicializa funciones de la plantilla
        App.init();
        Calendar.init();

        for (var k = 0; k < iteraciones; k++) {
            eventosDinamicos = {
                id: ids[k],
                idUsuario: idUsuario[k],
                idPerfil: idPerfil[k],
                archivo: archivo[k],
                title: ausencia[k],
                start: fechaDesde[k],
                end: fechaHasta[k],
                className: colores[Math.floor(Math.random() * 17)],
                media: '<i class="fa fa-thumb-tack"></i>',
                description: descripcion[k],

                estatusEvento: '"'+estatus[k]+'"',
                usuarioEvento: usuario[k],
                fechaAusenciaDesdeEvento: fechaAusenciaDesde[k],
                fechaAusenciaHastaEvento: fechaAusenciaHasta[k],
                horaEntradaEvento: horaEntrada[k],
                horaSalidaEvento: horaSalida[k],
                idUsuario: idUsuario[k],
                idPerfil: idPerfil[k],
                archivo: archivo[k]
            };
           console.log(eventosDinamicos);
            $('#calendar').fullCalendar( 'renderEvent', eventosDinamicos, true);
        }
    });
});
/*function cambiarEstatus()
{
     //Objetos
    var evento = new Base();
    var datos;
    datos=
    {


        idPermiso:$("#idPermiso").html,
        idPerfil:$("#idper").html,
        idUsuario:$("#idus").html,
        archivo:$("#arc").html

    };
    console.log($("#idPermiso").html);
//     evento.enviarEvento('CalendarioPermisos/cancelarPermiso', datos, '', function (respuesta) {
//        console.log(respuesta);
//    });
}*/