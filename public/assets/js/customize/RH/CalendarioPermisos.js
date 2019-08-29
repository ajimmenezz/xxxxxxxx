$(function () {
        //Objetos
        var evento = new Base();
        var peticion= new Utileria();

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
        let Justificacion= new Array();
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
                Justificacion[i]=respuesta[i].Justificacion;
           
            }
            console.log(Justificacion);
            //SE PINTA EL CALENDARIO
            var handleCalendarDemo = function () {
                "use strict";
                var buttonSetting = {left: '', center: 'title', right: ''};
                var date = new Date();
                var m = date.getMonth();
                var y = date.getFullYear();

                var calendar = $('#calendar').fullCalendar({
                    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                    dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
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
                    editable: false,
                    events: [],
                    
                    eventTextColor: '#fff',
                    eventClick: function(calEvent, jsEvent, view) {
                        
                        /*LLENAR EVENTOS*/
                        $("#idPermiso").html(calEvent.id);
                        $("#idUsr").html(calEvent.idUser);
                        $('#usr').val(calEvent.usuarioEvento);
                        $('#sts').val(calEvent.estatusEvento);
                        $('#aus').val(calEvent.ausencia);
                        $('#fed').val(calEvent.fechaAusenciaDesdeEvento);
                        var autJefe=" ";
                        var autRH=" ";
                        var autConta=" ";
                        var autDire=" ";
                        var fill="";
                        var btns="";
                        if(calEvent.estatusEvento =="RECHAZADO" )
                        {
                            fill="";
                            fill+="<h4 style='color: #d68f8f'>Rechazado</h4>";
                            let bandera=true;
                            
                            if(calEvent.autorizacionJefe!=null && bandera==true)
                            {
                                fill+="<h5><input type='text' class='form-control text-center' readonly='readonly' id='' value='"+calEvent.autorizacionJefe+"'></h5>";
                                bandera=false;
                            }
                            if(calEvent.autorizacionRH!=null && bandera==true)
                            {
                                fill+="<h5><input type='text' class='form-control text-center' readonly='readonly' id='' value='"+calEvent.autorizacionRH+"'></h5>";
                                bandera=false;
                            }
                            if(calEvent.autorizacionContabilidad!=null && bandera==true)
                            {
                                fill+="<h5><input type='text' class='form-control text-center' readonly='readonly' id='' value='"+calEvent.autorizacionContabilidad+"'></h5>";
                                bandera=false;
                            }
                            if(calEvent.autorizacionDireccion!=null && bandera==true)
                            {
                                fill+="<h5><input type='text' class='form-control text-center' readonly='readonly' id='' value='"+calEvent.autorizacionDireccion+"'></h5>";
                                bandera=false;
                            }
                            fill+="<h4>Motivo:</h4>";
                            fill+= "<input type='text' class='form-control text-center' readonly='readonly' id='' value='"+calEvent.Rechazo+"'>";
                            
                            $("#BotonesAcciones").html(btns);
                            $("#datosAutorizacion").html(fill);
                            
                        }
                        if(calEvent.estatusEvento =="AUTORIZADO")
                        {
                            fill="";
                            fill+="<h5 style='color: #96cc75'>Autorizó:  </h5>";
                            if(calEvent.autorizacionJefe!=null)
                            {
                                fill+="<h5><input type=text class='form-control text-center' readonly=readonly value='"+calEvent.autorizacionJefe+"'></h5>";
                            }
                            if(calEvent.autorizacionRH!=null)
                            {
                                fill+="<h5> "+calEvent.autorizacionRH+"</h5>";
                            }
                            if(calEvent.autorizacionContabilidad!=null)
                            {
                                fill+="<h5> "+calEvent.autorizacionContabilidad+"</h5>";
                            }
                            if(calEvent.autorizacionDireccion!=null)
                            {
                                fill+="<h5> "+calEvent.autorizacionDireccion+"</h5>";
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
                            fill+="<h5>Autorizado  </h5>";
                            fill+="<h5>Jefe :</h5>";
                            fill+="<div><input type=text class='form-control text-center' readonly=readonly value='"+autJefe+"'></div>";
                            fill+="<h5>Recursos humanos: </h5>";
                            fill+="<div><input type=text class='form-control text-center' readonly=readonly value='"+autRH+"'></div>";
                            fill+="<h5>Contabilidad: </h5>";
                            fill+="<div><input type=text class='form-control text-center' readonly=readonly value='"+autConta+"'></div>";
                            fill+="<h5>Dirección: </h5>";
                            fill+="<div><input type=text class='form-control text-center' readonly=readonly value='"+autDire+"'></div>";

                            btns+="<button type='button'  class='btn bg-green text-white' onclick='aceptarPermiso();'>Aceptar permiso</button>";
                            btns+="<button type='button'  class='btn bg-red text-white ' onclick='modalMotivo(); '>Rechazar permiso</button>";
                            $("#BotonesAcciones").html(btns);
                            $("#datosAutorizacion").html(fill);
                            
                        }
                        
                        
                        if(calEvent.fechaAusenciaHastaEvento=="0000-00-00")
                        {
                            $('#fechaHasta').html(" ");
                        }
                        else
                        {
                            $('#feh').html("<h5>Fecha hasta: </h5><input type=text class='form-control text-center' readonly=readonly value='"+calEvent.fechaAusenciaHastaEvento+"'>");
                        }
                        if(calEvent.horaEntradaEvento=="00:00:00")
                        {
                            $('#hoe').html(" ");
                        }
                        else
                        {
                            $('#hoe').html("<h5>Hora entrada: </h5><input type='text' class='form-control text-center' readonly='readonly' id='' value ='"+calEvent.horaEntradaEvento+"'>");
                        }
                        if(calEvent.horaSalidaEvento=="00:00:00")
                        {
                            $('#hos').html(" ");
                        }
                        else
                        {
                            $('#hos').html("<h5>Hora salida: </h5><input type='text' class='form-control text-center' readonly='readonly' id='' value ='"+calEvent.horaSalidaEvento+"'>");
                        }

                        $('#jus').val(calEvent.Justificacion);
                        $('#mot').val(calEvent.descripcion);
                        

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
                        media: $(this).attr('data-media'),
                        className: $(this).attr('data-bg'),
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

                let titulo="";
            for (var k = 0; k < iteraciones; k++) {
                    titulo=usuario[k].substring(0,9)+"...";
                eventosDinamicos = {
                    id: ids[k],
                   // media: '<i class="fa fa-circle"></i>',
                    title: titulo ,
                    idUsuario: idUsuario[k],
                    idPerfil: idPerfil[k],
                    archivo: archivo[k],
                    ausencia: ausencia[k],
                    start: fechaDesde[k],
                    end: fechaHasta[k],
                   // className: colores[Math.floor(Math.random() * 17)],
                   className: "bg-teal",

                    descripcion: descripcion[k],
                    Justificacion: Justificacion[k],
                    estatusEvento: estatus[k],
                    usuarioEvento: usuario[k],
                    fechaAusenciaDesdeEvento: fechaAusenciaDesde[k],
                    fechaAusenciaHastaEvento: fechaAusenciaHasta[k],
                    horaEntradaEvento: horaEntrada[k],
                    horaSalidaEvento: horaSalida[k],
                    
                    autorizacionJefe : autorizacionJefe[k],
                    autorizacionRH : autorizacionRH[k],
                    autorizacionContabilidad : autorizacionContabilidad[k],
                    autorizacionDireccion : autorizacionDireccion[k],
                    Rechazo: Rechazo[k]
                };
                $('#calendar').fullCalendar( 'renderEvent', eventosDinamicos, true);
            }
        });
        $('#btnAceptarRechazo').on('click', function () {
            if($("#motivoRechazo").val()=="" || $("#motivoRechazo").val()== null)
            {
                console.log("No se seleccionó nada");
            }
            else
            {
                let motivo=$("#motivoRechazo").val();
                enviarCancelacion(motivo);
            }
        });
        
    });

    function aceptarPermiso()
    {
        let idPermiso =$('#idPermiso').html() ;     
        let idPerfil;
        let idUser;
        let archivo= $("#arc").html();
        var evento= new Base();
        var peticion= new Utileria();
        
        
        peticion.enviar('modalDatosPermiso','EventoPermisosVacaciones/obtenerDatos','',function(respuesta)
        {
            idPerfil = respuesta.ID;
            idUser = respuesta.Perfil;
           // location.reload();
            let datosPerm=
                    {
                        idPermiso:idPermiso,
                        idPerfil:idPerfil,
                        idUser:idUser,
                        archivo: archivo
            };
            peticion.enviar('modalDatosPermiso','EventoPermisosVacaciones/ConluirAutorizacion',datosPerm,function(respuesta)
            {
                location.reload();
            });
        });
    }

    function modalMotivo()
    {
        selectMotivos();
        $('#modalRechazo').modal();
        
    }
    function selectMotivos()
    {
        //Objetos
        var evento = new Base();
        let sel="";
        let motivo="";
        evento.enviarEvento('EventoPermisosVacaciones/MostarMotivosRechazo', '', '', function (respuesta) {
            sel+="<option value='' selected disabled>Seleccionar...</option>";
            for(let i=0; i<respuesta.length; i++)
            {
                sel+="<option value='1'>"+respuesta[i].Nombre+"</option>";
            }
            $("#motivoRechazo").html(sel);
        });
    }
    function enviarCancelacion(motivo)
    {
        let idPermiso = $('#idPermiso').html();
        let idPerfil;
        let idUser;
        let archivo = $("#arc").html();
        var evento = new Base();
        var peticion = new Utileria();
        let idMotivo= motivo;
        
        peticion.enviar('modalDatosPermiso','EventoPermisosVacaciones/obtenerDatos', '', function (respuesta) {
           idUser= respuesta.ID;
           idPerfil=respuesta.Perfil;
            let datos =
                    {
                        perfilUsuario: idPerfil,
                        idPerfil: idPerfil,
                        idUser: idUser,
                        archivo: archivo,
                        idPermiso: idPermiso,
                        motivoRechazo: idMotivo
                    };
            peticion.enviar('modalDatosPermiso','EventoPermisosVacaciones/cancelarPermisoCalendario', datos,  function (respuesta)
            {
               location.reload();
            });
              // 
        });

    }
