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
    let autorizacionJefe = new Array();
    let autorizacionRH = new Array();
    let autorizacionContabilidad = new Array();
    let autorizacionDireccion = new Array();


    let estatus = new Array();
    let usuario = new Array();
    let fechaAusenciaDesde = new Array();
    let fechaAusenciaHasta = new Array();
    let Rechazo = new Array();
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
           
            autorizacionJefe[i]= respuesta[i].AutorizacionJefe; 
            autorizacionRH[i] = respuesta[i].AutorizacionRH;
            autorizacionContabilidad[i] =  respuesta[i].AutorizacionContabilidad; 
            autorizacionDireccion[i] = respuesta[i].AutorizacionDireccion;
            Rechazo[i] = respuesta[i].Rechazo;
       
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
                    var autJefe=" ";
                    var autRH=" ";
                    var autConta=" ";
                    var autDire=" ";
                    var fill="";
                    var btns="";
                    if(calEvent.estatusEvento =="RECHAZADO" )
                    {
                        fill="";
                        fill+="<h5>Rechazado por: </h5>";
                        fill+="<h5>Motivo: "+calEvent.Rechazo+"</h5>";
                        if(calEvent.autorizacionJefe!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionJefe+"</h5>";
                        }
                        if(calEvent.autorizacionRH!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionRH+"</h5>";
                        }
                        if(calEvent.autorizacionContabilidad!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionContabilidad+"</h5>";
                        }
                        if(calEvent.autorizacionDireccion!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionDireccion+"</h5>";
                        }
                        
                        $("#BotonesAcciones").html(btns);
                        $("#datosAutorizacion").html(fill);
                        
                    }
                    if(calEvent.estatusEvento =="AUTORIZADO")
                    {
                        fill="";
                        fill+="<h5>Autorizado por: </h5>";
                        if(calEvent.autorizacionJefe!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionJefe+"</h5>";
                        }
                        if(calEvent.autorizacionRH!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionRH+"</h5>";
                        }
                        if(calEvent.autorizacionContabilidad!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionContabilidad+"</h5>";
                        }
                        if(calEvent.autorizacionDireccion!=null)
                        {
                            fill+="<h5>Por: "+calEvent.autorizacionDireccion+"</h5>";
                        }
                        $("#BotonesAcciones").html(btns);
                        $("#datosAutorizacion").html(fill);
                      
                        
                    }
                    if(calEvent.estatusEvento=="PENDIENTE POR AUTORIZAR")
                    {
                       
                        fill="";
                        if(calEvent.autorizacionJefe==null || calEvent.autorizacionJefe=="")
                        {
                            autJefe="PENDIENTE";
                        }
                        else
                        {
                             autJefe=calEvent.autorizacionJefe;
                        }
                        if(calEvent.autorizacionRH==null || calEvent.autorizacionRH=="")
                        {
                            autRH="PENDIENTE";
                        }
                        else
                        {
                             autRH=calEvent.autorizacionRH;
                        }
                        if(calEvent.autorizacionContabilidad==null || calEvent.autorizacionContabilidad=="")
                        {
                            autConta="PENDIENTE";
                        }
                        else
                        {
                             autConta=calEvent.autorizacionContabilidad;
                        }
                        if(calEvent.autorizacionDireccion==null || calEvent.autorizacionDireccion=="")
                        {
                            autDire="PENDIENTE";
                        }
                        else
                        {
                             autDire=calEvent.autorizacionDireccion;
                        }
                        fill+="<h5>Autorizado por: </h5>";
                        fill+="<div> JEFE: "+autJefe+"</div>";
                        fill+="<div> RH: "+autRH+"</div>";
                        fill+="<div> CONTABILIDAD: "+autConta+"</div>";
                        fill+="<div> DIRECCION: "+autDire+"</div>";
                        btns+="<button type='button'  class='btn bg-red text-white' onclick='aceptarPermiso();'>Aceptar permiso</button>";
                        btns+="<button type='button'  class='btn bg-green text-white ' onclick='rechazarPermiso(); '>Rechazar permiso</button>";
                        $("#BotonesAcciones").html(btns);
                        $("#datosAutorizacion").html(fill);
                        
                    }
                    
                    
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

                estatusEvento: estatus[k],
                usuarioEvento: usuario[k],
                fechaAusenciaDesdeEvento: fechaAusenciaDesde[k],
                fechaAusenciaHastaEvento: fechaAusenciaHasta[k],
                horaEntradaEvento: horaEntrada[k],
                horaSalidaEvento: horaSalida[k],
                idUsuario: idUsuario[k],
                idPerfil: idPerfil[k],
                archivo: archivo[k],
                
                autorizacionJefe : autorizacionJefe[k],
                autorizacionRH : autorizacionRH[k],
                autorizacionContabilidad : autorizacionContabilidad[k],
                autorizacionDireccion : autorizacionDireccion[k],
                Rechazo: Rechazo[k]
            };
            $('#calendar').fullCalendar( 'renderEvent', eventosDinamicos, true);
        }
    });
    
});

function aceptarPermiso()
{
    let idPermiso =$('#idPermiso').html() ;     
    let idPerfil = $('#idper').html();
    let idUser= $("#usr").html();
    let archivo= $("#arc").html();
    var evento= new Base();
    
    let datos=
            {
            perfilUsuario: idPerfil,
            idPerfil:idPerfil,
            idUser:idUser,
            archivo:archivo,
            idPermiso:idPermiso
    };
    evento.enviarEvento('EventoPermisosVacaciones/Autorizar',datos,'#panelAutorizarPermisos',function(respuesta)
    {
        
        console.log(respuesta);
    });
}

function rechazarPermiso()
{
    let idPermiso = $('#idPermiso').html();
    let idPerfil = $('#idper').html();
    let idUser = $("#usr").html();
    let archivo = $("#arc").html();
    var evento = new Base();
    var sel="";
    let datos =
            {
                perfilUsuario: idPerfil,
                idPerfil: idPerfil,
                idUser: idUser,
                archivo: archivo,
                idPermiso: idPermiso
            };
    selectMotivos();
    console.log(idPermiso, idPerfil);
    evento.enviarEvento('EventoPermisosVacaciones/Autorizar', datos, '', function (respuesta)
    {
        $('#modalRechazo').modal();
        console.log(respuesta);
        
    });
}
function selectMotivos()
{
    //Objetos
    var evento = new Base();
    let sel="";
    let motivo="";
    evento.enviarEvento('EventoPermisosVacaciones/MostarMotivosRechazo', '', '', function (respuesta) {
        let long = respuesta.lenght();
        console.log(long);
        for(let i=0; i<1; i++)
        {
            console.log(respuesta[i].Nombre);
            sel+="<option>"+respuesta[i].Nombre+"</option>";
        }
        $("#rechazos").html(sel);
    });
}