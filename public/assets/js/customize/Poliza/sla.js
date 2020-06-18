$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var fecha = new Fecha();

    //Creando tabla proyectos sin iniciar
    tabla.generaTablaPersonal('#data-table-SLA', null, null, {details: false});

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    fecha.rangoFechas('#desdeSLA', '#hastaSLA');


    $('#reporteExcel').off('click');
    $('#reporteExcel').on('click', function () {
        var datosTabla = [];
        var datosTablaSLA = $("#data-table-SLA").DataTable().rows().data();
        
        for (var i = 0; i < datosTablaSLA.length; i++) {
            datosTabla.push(datosTablaSLA[i]);
        }
        
        var data = {datosSLA: datosTabla};

        evento.enviarEvento('SLA/ReporteExcel', data, '#panelSLA', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
                evento.terminarModal("#modalEdit");
            }
        });
    });

    $('#btnBuscarSLA').off('click');
    $('#btnBuscarSLA').on('click', function () {
        var desde = $("#txtDesdeSLA").val();
        var hasta = $("#txtHastaSLA").val();

        if (desde !== '') {
            if (hasta !== '') {
                var data = {desde: desde, hasta: hasta};
                evento.enviarEvento('SLA/Filtro', data, '#panelSLA', function (respuesta) {
                    recargandoTablaSLA(respuesta);
                });
            } else {
                evento.mostrarMensaje('#errorFiltroSLA', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('#errorFiltroSLA', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var recargandoTablaSLA = function (sla) {
        tabla.limpiarTabla("#data-table-SLA");
        $.each(sla, function (key, item) {
            tabla.agregarFila("#data-table-SLA", [
                item.Folio,
                item.Sucursal,
                item.AtiendeSolicitud,
                item.Tecnico,
                item.FechaCreacionServicio,
                item.IntervaloSolicitudServicioCreacion,
                item.FechaCreacion,
                item.FechaInicio,
                item.TiempoTranscurrido,
                item.TiempoPrioridad,
                item.Prioridad,
                item.LocalForaneo,
                item.SLA
            ]);
        });
    }
});