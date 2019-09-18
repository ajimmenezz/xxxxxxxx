class Calendario {

    constructor(nombre) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
        this.callback = null;
        this.calendar;
        this.iniciarCalendario();
    }

    iniciarCalendario() {
        let _this = this;
        _this.calendar = this.objeto.fullCalendar({
            header: {
                left: 'title',
                center: 'prev,today,next',
                right: ''
            },
            buttonText: {
                today: 'Hoy'
            },
            titleFormat: {
                month: 'MMMM yyyy',
                week: "MMMM yyyy"
            },
            columnFormat: {
                month: 'dddd',
                week: 'ddd d'
            },
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            allDaySlot: false,
//            slotEventOverlap: true,
//            minTime: "08:00:00",            
//            nowIndicator: true,
//            navLinks: true,
//            navLinkDayClick: function (date, jsEvent) {
//                date.format();
//            }
            eventRender: function (eventObj, $el) {
//                $el.popover({
//                    title: eventObj.nombreUsuario,
//                    content: eventObj.description,
//                    trigger: 'hover',
//                    placement: 'top',
//                    container: 'body'
//                });
            },

            eventClick: function (evento, jsEvento, objetoVista) {                
//                if (_this.callback !== null) {
                    _this.callback(evento, jsEvento, objetoVista);
//                }
//                $("<div>").dialog({modal: true, title: evento.nombreUsuario, width: 350});
//                $('#modalDatosPermiso').modal();
            }
        });
    }

    setEventoClick(callback) {
        this.callback = callback;        
    }

    cargarInformacionCalendario(informacionAgenda) {
        let _this = this.calendar;
        $.each(informacionAgenda, function (key, value) {
            _this.fullCalendar('renderEvent', value, true);
        })
    }
}