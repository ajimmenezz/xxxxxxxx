$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    $("#listAreasServicios").change(function () {
        reloadCalendar();
    });

    var t = new Date;
    var n = t.getMonth();
    var r = t.getFullYear();
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            agendaWeek: 'Semana',
            list: 'Lista'
        },
        locale: 'es',
        firstDay: 1,
        viewRender: function (view, element) {
            reloadCalendar();
        },
        eventRender: function (event, element, view) {
            var html = `                
                <div class="hr-line-solid-no-margin"></div>
                <span style="font-size: 0.90em">` + (event.description == null ? '' : event.description) + `</span></div>
            `;
            var e = element.find('.fc-title');
            e.attr('style', 'white-space: normal !important; font-size: 1.15em !important; font-weight: 600;');
            e.append(html);
            e.addClass(event.className[0]);
            e.removeClass('bg-blue');
        },
        eventClick: function (calEvent, jsEvent, view) {
            cargaInfoServicio(calEvent.id);
        }
    });

    function reloadCalendar() {
        var moment = $('#calendar').fullCalendar('getDate').format();
        var calendarDate = new Date(moment);
        var calendarMes = calendarDate.getMonth() + 1;
        var calendarYear = calendarDate.getFullYear();
        var area = $("#listAreasServicios").val();
        cargaDatosCalendario(calendarMes, calendarYear, area);
    }


    /*Carga los servicios correspondientes al usuario y sus permisos*/
    function cargaDatosCalendario() {
        var data = {
            mes: arguments[0],
            anio: arguments[1],
            area: arguments[2]
        };
        var serviciosCalendario = [];
        evento.enviarEvento('Calendario/Servicios', data, '#seccion-notificaciones', function (respuesta) {
            $.each(respuesta, function (k, v) {
                var title = (v.Folio == null ? v.Ticket : v.Ticket + ' - (SD)' + v.Folio);
                var sucursal = (v.Sucursal == null ? '' : v.Sucursal + '<br />');
                var className = '';
                switch (v.IdEstatus) {
                    case 1:
                    case '1':
                    case 2:
                    case '2':
                    case 10:
                    case '10':
                    case 12:
                    case '12':
                        className = 'bg-orange';
                        break;
                    case 3:
                    case '3':
                        className = 'bg-red';
                        break;
                    case 4:
                    case '4':
                        className = 'bg-green-darker';
                        break;
                    case 5:
                    case '5':
                        className = 'bg-green';
                        break;
                    case 6:
                    case '6':
                        className = 'bg-purple';
                        break;
                    default:
                        className = 'bg-blue';
                        break;
                }

                var description = v.Tipo + '<br />' + sucursal + v.Atiende + '<br />' + v.Estatus;

                serviciosCalendario.push({
                    id: v.Id,
                    title: title,
                    start: v.Fecha,
                    className: className,
                    description: description
                });
            });

            $("#calendar").fullCalendar('removeEvents');
            $("#calendar").fullCalendar('renderEvents', serviciosCalendario);
        });
    }

    function cargaInfoServicio() {
        var data = {
            servicio: arguments[0]
        };
        evento.enviarEvento('Calendario/DetallesServicio', data, '#seccion-notificaciones', function (respuesta) {
            evento.mostrarModal('Servicio ' + data.servicio, respuesta.html);
            $('#tentativa').datetimepicker({
                format: 'YYYY-MM-DD',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                }
            });
            
            $("#btnModalConfirmar").off("click");
            $("#btnModalConfirmar").on("click", function(){
                var tentativa = $("#txtTentativa").val();
                evento.cerrarModal();                
                actualizaTentativaServicio(data.servicio,tentativa);
            });
        });
    }
    
    function actualizaTentativaServicio() {
        $("#page-loader").removeClass("hide");
        var data = {
            id: arguments[0],
            tentativa: arguments[1]
        }
        
        evento.enviarEvento('Calendario/ActualizaTentativa', data, '#seccion-notificaciones', function (respuesta) {
            reloadCalendar();
        });
                
        $("#page-loader").addClass("hide");
    }
});