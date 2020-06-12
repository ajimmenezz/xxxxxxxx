$(function () {

    //Objetos
    var evento = new Base();

    var tabla = new Tabla();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    initAgenda();

    function initAgenda() {
        loadGoogleEvents();

        $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            var target = $(e.target).attr("href");
            switch (target) {
                case "#nav-tab-program":
                    loadPendingServices();
                    break;
            }
        });

    }

    function reloadGoogleEvents(events, gotoDate = null) {
        $("#calendar").fullCalendar('removeEvents');
        $("#calendar").fullCalendar('renderEvents', events);
        $("#calendar").fullCalendar('gotoDate', gotoDate);

    }

    function loadGoogleEvents(update = false) {
        var calendarOptions = {};
        if (update === true) {
            calendarOptions = {
                'initialDate': $('#calendar').fullCalendar('getDate').format()
            };
        }

        evento.enviarEvento('/Agenda/LoadGoogleEvents', calendarOptions, '#nav-tab-calendar', function (respuesta) {
            if (update === true) {
                reloadGoogleEvents(respuesta.events, calendarOptions.initialDate);
            } else {
                initCalendar(respuesta.events);
            }
        });
    }

    function loadPendingServices() {
        evento.enviarEvento('/Agenda/LoadPendingServices', {}, '#nav-tab-program', function (respuesta) {
            $("#pendingServices").empty().append(respuesta.html);
            tabla.generaTablaPersonal('#pendingServicesTable', null, null, true, true, [[0, 'desc']]);
            initPendingServicesTable();
        });
    }

    function initPendingServicesTable() {
        $('#pendingServicesTable tbody').on('click', 'tr', function () {
            var datos = $('#pendingServicesTable').DataTable().row(this).data();
            loadProgramServiceForm(datos[0], '#nav-tab-program');
        });
    }

    function loadProgramServiceForm(serviceId, loadingDiv) {
        evento.enviarEvento('/Agenda/LoadProgramServiceForm', { serviceId: serviceId }, loadingDiv, function (respuesta) {
            evento.iniciarModal("#basicModal", "", respuesta.html);
            $("#saveEventChanges").off("click");
            $("#saveEventChanges").on("click", function () {
                saveEvent();
            });
        });
    }

    function saveEvent() {
        var eventData = {
            'googleEventId': $("#googleEventId").val(),
            'serviceId': $("#eventServiceId").val(),
            'title': $.trim($("#eventTitle").val()),
            'date': $("#eventDate").val(),
            'time': $("#eventTime option:selected").val(),
            'description': $("#eventDescription").val()
        }
        evento.enviarEvento('/Agenda/SaveEvent', eventData, '#basicModal', function (respuesta) {
            evento.terminarModal("#basicModal");
            reloadGoogleEvents(respuesta.events, respuesta.goToDate);
            loadPendingServices();
        });
    }

    function initCalendar(events) {
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
            events: events,
            viewRender: function (view, element) {
                loadGoogleEvents(true);
            },
            eventRender: function (event, element, view) {
                var e = element.find('.fc-title');
                e.attr('style', 'white-space: normal !important; font-size: 1.3em !important; font-weight: 500;');
                e.addClass(event.className[0]);
                e.removeClass('bg-blue');
            },
            eventClick: function (calEvent, jsEvent, view) {
                loadProgramServiceForm(calEvent.id, '#nav-tab-calendar');
            }
        });
    }
});