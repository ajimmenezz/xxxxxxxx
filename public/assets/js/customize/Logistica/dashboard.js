$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var charts = new Charts();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa la tabla de las solicitudes generadas
    tabla.generaTablaPersonal('#tb-solicitudes-generadas', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $("#btn-exporta-servicios-logistica").on("click", function () {
        var fechas = getFechasFiltro();
        evento.enviarEvento('EventoDashboard/Exporta_Servicios_Logistica', fechas, "#panel-solicitudes-generadas", function (respuesta) {            
            window.open(respuesta.ruta, '_blank');
        });
    });

    evento.enviarEvento('EventoDashboard/Solicitudes_Generadas', {}, "#panel-solicitudes-generadas", function (respuesta) {
        cargaGraficasSolicitudesGeneradas(respuesta);
        initFechas();
        initBotonesFechas();
        cargaServiciosArea();
    });

    var initFechas = function () {
        $('#desde').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#hasta').datetimepicker({
            format: 'DD/MM/YYYY',
            useCurrent: false //Important! See issue #1075
        });
        $("#desde").on("dp.change", function (e) {
            $('#hasta').data("DateTimePicker").minDate(e.date);
        });
        $("#hasta").on("dp.change", function (e) {
            $('#desde').data("DateTimePicker").maxDate(e.date);
        });
    };

    var initBotonesFechas = function () {
        $(".btn-date-filter").off("click");
        $(".btn-date-filter").on("click", function () {
            var data = {id: $(this).prop('id')};
            evento.enviarEvento('EventoDashboard/Filtro_Rapido_Fecha', data, "", function (respuesta) {
                $("#desde").data("DateTimePicker").date(respuesta.Inicio);
                $("#desde").find("input").val(respuesta.Inicio);
                $("#hasta").data("DateTimePicker").date(respuesta.Fin);
                $("#hasta").find("input").val(respuesta.Fin);
            });
        });

        $("#btnFiltrarDashboard").off("click");
        $("#btnFiltrarDashboard").on("click", function () {
            cargaSolicitudesGeneradas();
            cargaServiciosArea();
        });
    };

    var cargaSolicitudesGeneradas = function () {
        var fechas = getFechasFiltro();
        evento.enviarEvento('EventoDashboard/Solicitudes_Generadas', fechas, "#panel-solicitudes-generadas", function (respuesta) {
            cargaGraficasSolicitudesGeneradas(respuesta);
            setTotalesGeneradas(respuesta.totales1, respuesta.totales2);
            tabla.limpiarTabla('#tb-solicitudes-generadas');
            var columnas = [
                {data: 'Id'},
                {data: 'Ticket'},
                {data: 'Departamento'},
                {data: 'Asunto'},
                {data: 'Prioridad'},
                {data: 'FechaCreacion'},
                {data: 'Estatus'}
            ];
            tabla.generaTablaPersonal('#tb-solicitudes-generadas', respuesta.tabla, columnas, true, true, [[0, 'desc']]);
        });
    }

    var setTotalesGeneradas = function (totales1, totales2) {
        $("#total-sol-generadas").empty().append(totales1[0].Total);
        $("#total-dias-sol-generadas").empty().append(totales1[0].Dias);
        $("#total-dptos-sol-generadas").empty().append(totales2[0].Total);
    }

    var getFechasFiltro = function () {
        var fechas = {
            Inicio: $("#desde").find("input").val(),
            Fin: $("#hasta").find("input").val()
        }
        return fechas;
    }

    var cargaGraficasSolicitudesGeneradas = function (respuesta) {
        var dataEstatus = {
            titulo: respuesta.estatus.titulo,
            concepto: respuesta.estatus.Concepto,
            total: respuesta.estatus.tituloTotal,
            datos: respuesta.estatus.datos,
            div: respuesta.estatus.divId
        };
        charts.pintaGraficaPie(dataEstatus);

        var dataPrioridad = {
            titulo: respuesta.prioridad.titulo,
            concepto: respuesta.prioridad.Concepto,
            total: respuesta.prioridad.tituloTotal,
            datos: respuesta.prioridad.datos,
            div: respuesta.prioridad.divId
        };
        charts.pintaGraficaPie(dataPrioridad);

        var dataDpto = {
            titulo: respuesta.dpto.titulo,
            concepto: respuesta.dpto.Concepto,
            total: respuesta.dpto.tituloTotal,
            datos: respuesta.dpto.datos,
            div: respuesta.dpto.divId
        };
        charts.pintaGraficaPie(dataDpto);
    }

    var initEventos = function () {
        $("#servicios-area").on("sho")
    }

    var cargaServiciosArea = function () {
        var fechas = getFechasFiltro();
        evento.enviarEvento('EventoDashboard/Servicios_Logistica', fechas, "#panel-solicitudes-generadas", function (respuesta) {
            cargaGraficasServiciosArea(respuesta);
            setTotalesServiciosArea(respuesta.totales1, respuesta.totales2);
            tabla.limpiarTabla('#tb-servicios-area');
            var columnas = [
                {data: 'Ticket'},
                {data: 'Id'},
                {data: 'TipoServ'},
                {data: 'FechaCreacion'},
                {data: 'Descripcion'},
                {data: 'Estatus'},
                {data: 'Atiende'}
            ];
            tabla.generaTablaPersonal('#tb-servicios-area', respuesta.tabla, columnas, true, true, [[0, 'desc']]);
        });
    }

    var setTotalesServiciosArea = function (totales1, totales2) {
        $("#total-servicios-area").empty().append(totales1[0].Total);
        $("#total-dias-servicios-area").empty().append(totales1[0].Dias);
        $("#total-dptos-servicios-area").empty().append(totales2[0].Total);
    }

    var cargaGraficasServiciosArea = function (respuesta) {
        var dataEstatus = {
            titulo: respuesta.estatus.titulo,
            concepto: respuesta.estatus.Concepto,
            total: respuesta.estatus.tituloTotal,
            datos: respuesta.estatus.datos,
            div: respuesta.estatus.divId
        };
        charts.pintaGraficaPie(dataEstatus);

        var dataAtiende = {
            titulo: respuesta.atiende.titulo,
            concepto: respuesta.atiende.Concepto,
            total: respuesta.atiende.tituloTotal,
            datos: respuesta.atiende.datos,
            div: respuesta.atiende.divId
        };
        charts.pintaGraficaPie(dataAtiende);

        var dataDpto = {
            titulo: respuesta.dpto.titulo,
            concepto: respuesta.dpto.Concepto,
            total: respuesta.dpto.tituloTotal,
            datos: respuesta.dpto.datos,
            div: respuesta.dpto.divId
        };
        charts.pintaGraficaPie(dataDpto);
    }
});

