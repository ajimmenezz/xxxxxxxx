$(function () {
    //Objetos
    evento = new Base();
    websocket = new Socket();
    charts = new Charts();
    tabla = new Tabla();

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

    $(window).resize(function () {
        drawEstatusChart();
    });

    initGeneral();
});

var evento, websocket, charts, tabla;

function initGeneral() {
    initFechas();
    initBotonesFechas();
    initDataEstatus();
    initDataPrioridad();
    initDataTipos();
}

function initFechas() {
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
}

function initBotonesFechas() {
    $(".btn-date-filter").off("click");
    $(".btn-date-filter").on("click", function () {
        var data = {id: $(this).prop('id')};
        evento.enviarEvento('/Logistica/EventoDashboard/Filtro_Rapido_Fecha', data, "", function (respuesta) {
            $("#desde").data("DateTimePicker").date(respuesta.Inicio);
            $("#desde").find("input").val(respuesta.Inicio);
            $("#hasta").data("DateTimePicker").date(respuesta.Fin);
            $("#hasta").find("input").val(respuesta.Fin);
        });
    });

    $("#btnFiltrarDashboard").off("click");
    $("#btnFiltrarDashboard").on("click", function () {
        reloadData();
    });
}

function getFechasFiltro() {
    var fechas = {
        Inicio: $("#desde").find("input").val(),
        Fin: $("#hasta").find("input").val()
    }
    return fechas;
}

function initDataEstatus() {
    tabla.generaTablaPersonal('#estatus_table', null, null, true, true, [[2, 'desc']], null, '<frt>', false);

    $('#estatus_table tbody').on('click', 'tr', function () {
        var datos = $('#estatus_table').DataTable().row(this).data();
        StaticCalls.filterByEstatus(datos[0]);
    });

    var tableEstatus = tabla.getTableObject('#estatus_table');

    tableEstatus.on('search.dt', function () {
        drawEstatusChart();
    });

    drawEstatusChart();
}

function initTableSolicitudesSecondary() {
    $("#table-solicitudes-secondary").on('click', 'tr', function () {
        var datos = $("#table-solicitudes-secondary").DataTable().row(this).data();
        var win = window.open('/Detalles/Solicitud/' + datos[0], '_blank');
        win.focus();
    });
}

function initTableSolicitudesThird() {
    $("#table-solicitudes-third").on('click', 'tr', function () {
        var datos = $("#table-solicitudes-third").DataTable().row(this).data();
        var win = window.open('/Detalles/Solicitud/' + datos[0], '_blank');
        win.focus();
    });
}

function initTableServiciosSecondary() {
    $("#table-servicios-secondary").on('click', 'tr', function () {
        var datos = $("#table-servicios-secondary").DataTable().row(this).data();
        var win = window.open('/Detalles/Servicio/' + datos[0], '_blank');
        win.focus();
    });
}

function initTableServiciosSecondaryS() {
    $("#table-servicios-secondary-s").on('click', 'tr', function () {
        var datos = $("#table-servicios-secondary-s").DataTable().row(this).data();
        var win = window.open('/Detalles/Servicio/' + datos[0], '_blank');
        win.focus();
    });
}

function initTableServiciosSecondaryLast() {
    $("#table-servicios-secondary-last").on('click', 'tr', function () {
        var datos = $("#table-servicios-secondary-last").DataTable().row(this).data();
        var win = window.open('/Detalles/Servicio/' + datos[0], '_blank');
        win.focus();
    });
}

function initTableServiciosThird() {
    $("#table-servicios-third").on('click', 'tr', function () {
        var datos = $("#table-servicios-third").DataTable().row(this).data();
        var win = window.open('/Detalles/Servicio/' + datos[0], '_blank');
        win.focus();
    });
}

function drawEstatusChart() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-estatus").html();
    $.each(tabla.getTableData('#estatus_table', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Estatus de Solicitudes',
        concepto: '',
        total: '10',
        datos: data,
        div: 'estatus_chart',
        handler: 'StaticCalls.filterByEstatus('
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawEstatusTiposChart() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-estatus_tipos_filtered").html();
    $.each(tabla.getTableData('#estatus_tipos_table_filtered', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Estatus de Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'estatus_tipos_chart_filtered'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawChartSucursalesServicios() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-sucursales-servicios").html();
    $.each(tabla.getTableData('#table-sucursales-servicios', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Sucursales de Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-sucursales-servicios'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawChartSucursalesServiciosS() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-sucursales-servicios-s").html();
    $.each(tabla.getTableData('#table-sucursales-servicios-s', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Sucursales de Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-sucursales-servicios-s'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawChartEstatusServiciosS() {
    console.log("algoo");
    var data = [];
    var suma = 0;
    var total = $("#total-cell-estatus-servicios-s").html();
    $.each(tabla.getTableData('#table-estatus-servicios-s', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Estatus de Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-estatus-servicios-s'
    };

    console.log(dataForChart);

    charts.pintaGraficaPie(dataForChart);
}

function drawChartAtiendeServicios() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-atiende-servicios").html();
    $.each(tabla.getTableData('#table-atiende-servicios', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Atiende el Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-atiende-servicios'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawChartAtiendeServiciosS() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-atiende-servicios-s").html();
    $.each(tabla.getTableData('#table-atiende-servicios-s', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Atiende el Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-atiende-servicios-s'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawEstatusServiciosChart() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-estatus-servicios-third").html();
    $.each(tabla.getTableData('#table-estatus-servicios-third', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Estatus de Servicios',
        concepto: '',
        total: '10',
        datos: data,
        div: 'chart-estatus-servicios-third'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawEstatusChartSecondary() {
    var prioridad = arguments[0] || '';
    var data = [];
    var suma = 0;
    var total = $("#total-cell-estatus-filtered").html();
    $.each(tabla.getTableData('#estatus_table_filtered', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Estatus de Solicitudes',
        concepto: '',
        total: '10',
        datos: data,
        div: 'estatus_chart_filtered',
        handler: 'StaticCalls.filterByPrioridadEstatus(' + prioridad + ','
    };

    charts.pintaGraficaPie(dataForChart);
}

function initDataPrioridad() {
    tabla.generaTablaPersonal('#prioridad_table', null, null, true, true, [[2, 'desc']], null, '<frt>', false);

    $('#prioridad_table tbody').on('click', 'tr', function () {
        var datos = $('#prioridad_table').DataTable().row(this).data();
        StaticCalls.filterByPrioridad(datos[0]);
    });

    var tableEstatus = tabla.getTableObject('#prioridad_table');

    tableEstatus.on('search.dt', function () {
        drawPrioridadChart();
    });

    drawPrioridadChart();
}

function drawPrioridadChart() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-prioridad").html();
    $.each(tabla.getTableData('#prioridad_table', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Prioridades de Solicitudes',
        concepto: '',
        total: '10',
        datos: data,
        div: 'prioridades_chart',
        handler: 'StaticCalls.filterByPrioridad('
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawPrioridadChartSecondary() {
    var estatus = arguments[0] || '';
    var data = [];
    var suma = 0;
    var total = $("#total-cell-prioridad-filtered").html();
    $.each(tabla.getTableData('#prioridad_table_filtered', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Prioridades de Solicitudes',
        concepto: '',
        total: '10',
        datos: data,
        div: 'prioridades_chart_filtered',
        handler: 'StaticCalls.filterByEstatusPrioridad(' + estatus + ','
    };

    charts.pintaGraficaPie(dataForChart);
}

function initDataTipos() {
    tabla.generaTablaPersonal('#tipos_table', null, null, true, true, [[2, 'desc']], null, '<frt>', false);

    $('#tipos_table tbody').on('click', 'tr', function () {
        var datos = $('#tipos_table').DataTable().row(this).data();
        StaticCalls.filterByTipo(datos[0]);
    });

    var tableEstatus = tabla.getTableObject('#tipos_table');

    tableEstatus.on('search.dt', function () {
        drawTiposChart();
    });

    drawTiposChart();
}

function drawTiposChart() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-tipos").html();
    $.each(tabla.getTableData('#tipos_table', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Tipos de Servicio',
        concepto: '',
        total: '10',
        datos: data,
        div: 'tipos_chart',
        handler: 'StaticCalls.filterByTipo('
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawTiposChartSecondary() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-tipos-filtered").html();
    $.each(tabla.getTableData('#tipos_table_filtered', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Tipos de Servicio',
        concepto: '',
        total: '10',
        datos: data,
        div: 'tipos_chart_filtered'
    };

    charts.pintaGraficaPie(dataForChart);
}

function drawTiposChartThird() {
    var data = [];
    var suma = 0;
    var total = $("#total-cell-tipos-filtered-third").html();
    $.each(tabla.getTableData('#tipos_table_filtered_third', true), function (k, v) {
        if ($.isNumeric(k)) {
            data.push({Concepto: v[1], Total: v[2], IdGen: v[0]});
            suma += parseInt(v[2]);
        }
    });

    if (suma < total) {
        data.push({Concepto: 'Diferencia del total', Total: (total - suma), IdGen: 0});
    }

    var dataForChart = {
        titulo: 'Tipos de Servicio',
        concepto: '',
        total: '10',
        datos: data,
        div: 'tipos_chart_filtered_third'
    };

    charts.pintaGraficaPie(dataForChart);
}

function reloadData() {
    var fechas = getFechasFiltro();
    evento.enviarEvento('Dashboard/InfoInicial', fechas, "#initialPanel", function (respuesta) {
        tabla.limpiarTabla('#estatus_table');
        tabla.limpiarTabla('#prioridad_table');
        tabla.limpiarTabla('#tipos_table');

        var total = 0;
        $.each(respuesta.estatus, function (k, v) {
            tabla.agregarFila('#estatus_table', [v.Id, v.Nombre, v.Total]);
            total += parseInt(v.Total);
        });
        $("#total-cell-estatus").empty().append(total);

        drawEstatusChart();

        total = 0;
        $.each(respuesta.prioridades, function (k, v) {
            tabla.agregarFila('#prioridad_table', [v.Id, v.Nombre, v.Total]);
            total += parseInt(v.Total);
        });
        $("#total-cell-prioridad").empty().append(total);

        drawPrioridadChart();


        total = 0;
        $.each(respuesta.tipos, function (k, v) {
            tabla.agregarFila('#tipos_table', [v.Id, v.Nombre, v.Total]);
            total += parseInt(v.Total);
        });
        $("#total-cell-tipos").empty().append(total);

        drawTiposChart();


        $("#estatus_table td, #prioridad_table td, #tipos_table td").addClass('text-center');
    });
}

class StaticCalls {
    static filterByEstatus() {
        var id = arguments[0];
        var fechas = getFechasFiltro();
        if (parseInt(id) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByEstatus', {id: id, fechas: fechas}, '#initialPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#secondaryPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#prioridad_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#tipos_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-solicitudes-secondary', null, null, true, true, [[0, 'desc']]);

                    initTableSolicitudesSecondary();

                    $('#prioridad_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#prioridad_table_filtered').DataTable().row(this).data();
                        StaticCalls.filterByEstatusPrioridad(id, datos[0]);
                    });

                    $('#tipos_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#tipos_table_filtered').DataTable().row(this).data();
                        StaticCalls.filterByEstatusTipo(id, datos[0]);
                    });

                    $("#initialPage").fadeOut(400, function () {
                        $("#secondaryPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToInitial").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                        })
                    });

                    $("#btnBackToInitial").off("click");
                    $("#btnBackToInitial").on("click", function () {
                        $("#secondaryPage").fadeOut(400, function () {
                            $("#initialPage").fadeIn(400);
                            $("#secondaryPage").empty();
                        });
                    });

                    $("#btnShowPrioridadesChart").off("click");
                    $("#btnShowPrioridadesChart").on("click", function () {
                        $("#div_table_prioridades_filtered").fadeOut(400, function () {
                            $("#div_chart_prioridades_filtered").fadeIn(400);
                            drawPrioridadChartSecondary(id);
                        });
                    });

                    $("#btnShowPrioridadesTable").off("click");
                    $("#btnShowPrioridadesTable").on("click", function () {
                        $("#div_chart_prioridades_filtered").fadeOut(400, function () {
                            $("#div_table_prioridades_filtered").fadeIn(400);
                        });
                    });

                    $("#btnShowTiposChart").off("click");
                    $("#btnShowTiposChart").on("click", function () {
                        $("#div_table_tipos_filtered").fadeOut(400, function () {
                            $("#div_chart_tipos_filtered").fadeIn(400);
                            drawTiposChartSecondary();
                        });
                    });

                    $("#btnShowTiposTable").off("click");
                    $("#btnShowTiposTable").on("click", function () {
                        $("#div_chart_tipos_filtered").fadeOut(400, function () {
                            $("#div_table_tipos_filtered").fadeIn(400);
                        });
                    });

                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByPrioridad() {
        var id = arguments[0];
        var fechas = getFechasFiltro();
        if (parseInt(id) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByPrioridad', {id: id, fechas: fechas}, '#initialPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#secondaryPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#estatus_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#tipos_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-solicitudes-secondary', null, null, true, true, [[0, 'desc']]);

                    initTableSolicitudesSecondary();

                    $('#estatus_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#estatus_table_filtered').DataTable().row(this).data();
                        StaticCalls.filterByPrioridadEstatus(id, datos[0]);
                    });

                    $('#tipos_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#tipos_table_filtered').DataTable().row(this).data();
                        StaticCalls.filterByPrioridadTipo(id, datos[0]);
                    });

                    $("#initialPage").fadeOut(400, function () {
                        $("#secondaryPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToInitial").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                        })
                    });

                    $("#btnBackToInitial").off("click");
                    $("#btnBackToInitial").on("click", function () {
                        $("#secondaryPage").fadeOut(400, function () {
                            $("#initialPage").fadeIn(400);
                            $("#secondaryPage").empty();
                        });
                    });

                    $("#btnShowEstatusChart").off("click");
                    $("#btnShowEstatusChart").on("click", function () {
                        $("#div_table_estatus_filtered").fadeOut(400, function () {
                            $("#div_chart_estatus_filtered").fadeIn(400, function () {
                                drawEstatusChartSecondary(id);
                            });
                        });
                    });


                    $("#btnShowEstatusTable").off("click");
                    $("#btnShowEstatusTable").on("click", function () {
                        $("#div_chart_estatus_filtered").fadeOut(400, function () {
                            $("#div_table_estatus_filtered").fadeIn(400);
                        });
                    });


                    $("#btnShowTiposChart").off("click");
                    $("#btnShowTiposChart").on("click", function () {
                        $("#div_table_tipos_filtered").fadeOut(400, function () {
                            $("#div_chart_tipos_filtered").fadeIn(400);
                            drawTiposChartSecondary();
                        });
                    });


                    $("#btnShowTiposTable").off("click");
                    $("#btnShowTiposTable").on("click", function () {
                        $("#div_chart_tipos_filtered").fadeOut(400, function () {
                            $("#div_table_tipos_filtered").fadeIn(400);
                        });
                    });

                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByTipo() {
        var id = arguments[0];
        var fechas = getFechasFiltro();
        if (parseInt(id) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByTipo', {id: id, fechas: fechas}, '#initialPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#secondaryPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#estatus_tipos_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-sucursales-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-atiende-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-servicios-secondary', null, null, true, true, [[0, 'desc']]);

                    $('#table-sucursales-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-sucursales-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'sucursal': datos[0],
                            'prevDiv': '#secondaryPage'
                        };
                        StaticCalls.filterBySucursal(data);
                    });

                    $('#table-atiende-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-atiende-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'atiende': datos[0],
                            'prevDiv': '#secondaryPage'
                        };
                        StaticCalls.filterByAtiende(data);
                    });

                    $('#estatus_tipos_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#estatus_tipos_table_filtered').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'estatus': datos[0],
                            'prevDiv': '#secondaryPage'
                        };
                        StaticCalls.filterByEstatusS(data);
                    });


                    initTableServiciosSecondary();

                    var tableEstatusTiposFiltered = tabla.getTableObject('#estatus_tipos_table_filtered');

                    tableEstatusTiposFiltered.on('search.dt', function () {
                        drawEstatusTiposChart();
                    });

                    $("#initialPage").fadeOut(400, function () {
                        $("#secondaryPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToInitial").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawEstatusTiposChart();
                        })
                    });

                    $("#btnBackToInitial").off("click");
                    $("#btnBackToInitial").on("click", function () {
                        $("#secondaryPage").fadeOut(400, function () {
                            $("#initialPage").fadeIn(400);
                            $("#secondaryPage").empty();
                        });
                    });

                    $("#btnShowChartSucursalesServicios").off("click");
                    $("#btnShowChartSucursalesServicios").on("click", function () {
                        $("#div-table-sucursales-servicios").fadeOut(400, function () {
                            $("#div-chart-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-sucursales-servicios").offset().top - 60
                                }, 600);
                                drawChartSucursalesServicios();
                            });
                        });
                    });

                    $("#btnShowTableSucursalesServicios").off("click");
                    $("#btnShowTableSucursalesServicios").on("click", function () {
                        $("#div-chart-sucursales-servicios").fadeOut(400, function () {
                            $("#div-table-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-sucursales-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                    $("#btnShowChartAtiendeServicios").off("click");
                    $("#btnShowChartAtiendeServicios").on("click", function () {
                        $("#div-table-atiende-servicios").fadeOut(400, function () {
                            $("#div-chart-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-atiende-servicios").offset().top - 60
                                }, 600);
                                drawChartAtiendeServicios();
                            });
                        });
                    });

                    $("#btnShowTableAtiendeServicios").off("click");
                    $("#btnShowTableAtiendeServicios").on("click", function () {
                        $("#div-chart-atiende-servicios").fadeOut(400, function () {
                            $("#div-table-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-atiende-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByTipoS() {
        var id = arguments[0]['tipo'];
        var data = arguments[0];
        var _data = data;
        var fechas = getFechasFiltro();
        if (parseInt(id) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByTipo', {id: id, fechas: fechas, data: data}, data.prevDiv, function (respuesta) {
                if (respuesta.code == 200) {
                    $("#auxiliarPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#estatus_tipos_table_filtered', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-sucursales-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-atiende-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-servicios-secondary', null, null, true, true, [[0, 'desc']]);

                    $('#table-sucursales-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-sucursales-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'sucursal': datos[0],
                            'estatusSolicitud': _data.estatusSolicitud || '',
                            'prioridad': _data.prioridad || '',
                            'prevDiv': '#auxiliarPage'
                        };
                        StaticCalls.filterBySucursal(data);
                    });

                    $('#table-atiende-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-atiende-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'atiende': datos[0],
                            'estatusSolicitud': _data.estatusSolicitud || '',
                            'prioridad': _data.prioridad || '',
                            'prevDiv': '#auxiliarPage'
                        };
                        StaticCalls.filterByAtiende(data);
                    });

                    $('#estatus_tipos_table_filtered tbody').on('click', 'tr', function () {
                        var datos = $('#estatus_tipos_table_filtered').DataTable().row(this).data();
                        var data = {
                            'tipo': id,
                            'estatus': datos[0],
                            'estatusSolicitud': _data.estatusSolicitud || '',
                            'prioridad': _data.prioridad || '',
                            'prevDiv': '#auxiliarPage'
                        };
                        StaticCalls.filterByEstatusS(data);
                    });


                    initTableServiciosSecondary();

                    var tableEstatusTiposFiltered = tabla.getTableObject('#estatus_tipos_table_filtered');

                    tableEstatusTiposFiltered.on('search.dt', function () {
                        drawEstatusTiposChart();
                    });

                    $(data.prevDiv).fadeOut(400, function () {
                        $("#auxiliarPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToInitial").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawEstatusTiposChart();
                        })
                    });

                    $("#auxiliarPage #btnBackToInitial").off("click");
                    $("#auxiliarPage #btnBackToInitial").on("click", function () {
                        $("#auxiliarPage").fadeOut(400, function () {
                            $(data.prevDiv).fadeIn(400);
                            $("#auxiliarPage").empty();
                        });
                    });

                    $("#btnShowChartSucursalesServicios").off("click");
                    $("#btnShowChartSucursalesServicios").on("click", function () {
                        $("#div-table-sucursales-servicios").fadeOut(400, function () {
                            $("#div-chart-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-sucursales-servicios").offset().top - 60
                                }, 600);
                                drawChartSucursalesServicios();
                            });
                        });
                    });

                    $("#btnShowTableSucursalesServicios").off("click");
                    $("#btnShowTableSucursalesServicios").on("click", function () {
                        $("#div-chart-sucursales-servicios").fadeOut(400, function () {
                            $("#div-table-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-sucursales-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                    $("#btnShowChartAtiendeServicios").off("click");
                    $("#btnShowChartAtiendeServicios").on("click", function () {
                        $("#div-table-atiende-servicios").fadeOut(400, function () {
                            $("#div-chart-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-atiende-servicios").offset().top - 60
                                }, 600);
                                drawChartAtiendeServicios();
                            });
                        });
                    });

                    $("#btnShowTableAtiendeServicios").off("click");
                    $("#btnShowTableAtiendeServicios").on("click", function () {
                        $("#div-chart-atiende-servicios").fadeOut(400, function () {
                            $("#div-table-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-atiende-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterBySucursal() {
        var data = arguments[0];
        var _data = data;
        var fechas = getFechasFiltro();
        evento.enviarEvento('Dashboard/CargaServiciosBySucursal', {data: data, fechas: fechas}, data.prevDiv, function (respuesta) {
            if (respuesta.code == 200) {
                $("#sucursalPage").empty().append(respuesta.html);

                tabla.generaTablaPersonal('#table-estatus-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-atiende-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-servicios-secondary-s', null, null, true, true, [[0, 'desc']]);

                initTableServiciosSecondaryS();

                $('#table-estatus-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-estatus-servicios-s').DataTable().row(this).data();
                    var data = {
                        'tipo': _data.tipo,
                        'sucursal': _data.sucursal,
                        'estatus': datos[0],
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#sucursalPage'
                    };
                    StaticCalls.lastFilter(data);
                });

                $('#table-atiende-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-atiende-servicios-s').DataTable().row(this).data();
                    var data = {
                        'tipo': _data.tipo,
                        'sucursal': _data.sucursal,
                        'estatus': '',
                        'atiende': datos[0],
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#sucursalPage'
                    };
                    StaticCalls.lastFilter(data);
                });

                $(data.prevDiv).fadeOut(400, function () {
                    $("#sucursalPage").fadeIn(400, function () {
                        var positionButton = $("#btnBackTo").position();
                        $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
//                        drawEstatusTiposChart();
                    })
                });

                $("#btnBackTo").off("click");
                $("#btnBackTo").on("click", function () {
                    $("#sucursalPage").fadeOut(400, function () {
                        $(data.prevDiv).fadeIn(400);
                        $("#sucursalPage").empty();
                    });
                });

                $("#btnShowChartEstatusServiciosS").off("click");
                $("#btnShowChartEstatusServiciosS").on("click", function () {
                    $("#div-table-estatus-servicios-s").fadeOut(400, function () {
                        $("#div-chart-estatus-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-estatus-servicios-s").offset().top - 60
                            }, 600);
                            drawChartEstatusServiciosS();
                        });
                    });
                });

                $("#btnShowTableEstatusServiciosS").off("click");
                $("#btnShowTableEstatusServiciosS").on("click", function () {
                    $("#div-chart-estatus-servicios-s").fadeOut(400, function () {
                        $("#div-table-estatus-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-estatus-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

                $("#btnShowChartAtiendeServiciosS").off("click");
                $("#btnShowChartAtiendeServiciosS").on("click", function () {
                    $("#div-table-atiende-servicios-s").fadeOut(400, function () {
                        $("#div-chart-atiende-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-atiende-servicios-s").offset().top - 60
                            }, 600);
                            drawChartAtiendeServiciosS();
                        });
                    });
                });

                $("#btnShowTableAtiendeServiciosS").off("click");
                $("#btnShowTableAtiendeServiciosS").on("click", function () {
                    $("#div-chart-atiende-servicios-s").fadeOut(400, function () {
                        $("#div-table-atiende-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-atiende-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

            } else {
                evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
            }
        });
    }

    static filterByAtiende() {
        var data = arguments[0];
        var _data = data;
        var fechas = getFechasFiltro();
        evento.enviarEvento('Dashboard/CargaServiciosByAtiende', {data: data, fechas: fechas}, data.prevDiv, function (respuesta) {
            if (respuesta.code == 200) {
                $("#atiendePage").empty().append(respuesta.html);

                tabla.generaTablaPersonal('#table-estatus-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-sucursales-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-servicios-secondary-s', null, null, true, true, [[0, 'desc']]);

                initTableServiciosSecondaryS();


                $('#table-estatus-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-estatus-servicios-s').DataTable().row(this).data();
                    var data = {
                        'tipo': _data.tipo,
                        'estatus': datos[0],
                        'atiende': _data.atiende,
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#atiendePage'
                    };
                    StaticCalls.lastFilter(data);
                });

                $('#table-sucursales-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-sucursales-servicios-s').DataTable().row(this).data();
                    var data = {
                        'tipo': _data.tipo,
                        'sucursal': datos[0],
                        'atiende': _data.atiende,
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#atiendePage'
                    };
                    StaticCalls.lastFilter(data);
                });

                $(data.prevDiv).fadeOut(400, function () {
                    $("#atiendePage").fadeIn(400, function () {
                        var positionButton = $("#btnBackTo").position();
                        $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                    })
                });

                $("#btnBackTo").off("click");
                $("#btnBackTo").on("click", function () {
                    $("#atiendePage").fadeOut(400, function () {
                        $(data.prevDiv).fadeIn(400);
                        $("#atiendePage").empty();
                    });
                });

                $("#btnShowChartEstatusServiciosS").off("click");
                $("#btnShowChartEstatusServiciosS").on("click", function () {
                    $("#div-table-estatus-servicios-s").fadeOut(400, function () {
                        $("#div-chart-estatus-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-estatus-servicios-s").offset().top - 60
                            }, 600);
                            drawChartEstatusServiciosS();
                        });
                    });
                });

                $("#btnShowTableEstatusServiciosS").off("click");
                $("#btnShowTableEstatusServiciosS").on("click", function () {
                    $("#div-chart-estatus-servicios-s").fadeOut(400, function () {
                        $("#div-table-estatus-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-estatus-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

                $("#btnShowChartSucursalesServiciosS").off("click");
                $("#btnShowChartSucursalesServiciosS").on("click", function () {
                    $("#div-table-sucursales-servicios-s").fadeOut(400, function () {
                        $("#div-chart-sucursales-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-sucursales-servicios-s").offset().top - 60
                            }, 600);
                            drawChartSucursalesServiciosS();
                        });
                    });
                });

                $("#btnShowTableSucursalesServiciosS").off("click");
                $("#btnShowTableSucursalesServiciosS").on("click", function () {
                    $("#div-chart-sucursales-servicios-s").fadeOut(400, function () {
                        $("#div-table-sucursales-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-sucursales-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

            } else {
                evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
            }
        });
    }

    static filterByEstatusS() {
        var data = arguments[0];
        var _data = data;
        var fechas = getFechasFiltro();
        evento.enviarEvento('Dashboard/CargaServiciosByEstatusS', {data: data, fechas: fechas}, data.prevDiv, function (respuesta) {
            if (respuesta.code == 200) {
                $("#estatusPage").empty().append(respuesta.html);

                tabla.generaTablaPersonal('#table-atiende-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-sucursales-servicios-s', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                tabla.generaTablaPersonal('#table-servicios-secondary-s', null, null, true, true, [[0, 'desc']]);

                initTableServiciosSecondaryS();

                $('#table-atiende-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-atiende-servicios-s').DataTable().row(this).data();
                    console.log(_data);
                    var data = {
                        'tipo': _data.tipo,
                        'estatus': _data.estatus,
                        'atiende': datos[0],
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#estatusPage'
                    };
                    console.log(data);
                    StaticCalls.lastFilter(data);
                });

                $('#table-sucursales-servicios-s tbody').on('click', 'tr', function () {
                    var datos = $('#table-sucursales-servicios-s').DataTable().row(this).data();
                    var data = {
                        'tipo': _data.tipo,
                        'estatus': _data.estatus,
                        'sucursal': datos[0],
                        'estatusSolicitud': _data.estatusSolicitud || '',
                        'prioridad': _data.prioridad || '',
                        'prevDiv': '#estatusPage'
                    };
                    StaticCalls.lastFilter(data);
                });

                $(data.prevDiv).fadeOut(400, function () {
                    $("#estatusPage").fadeIn(400, function () {
                        var positionButton = $("#btnBackTo").position();
                        $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                    })
                });

                $("#btnBackTo").off("click");
                $("#btnBackTo").on("click", function () {
                    $("#estatusPage").fadeOut(400, function () {
                        $(data.prevDiv).fadeIn(400);
                        $("#estatusPage").empty();
                    });
                });

                $("#btnShowChartSucursalesServiciosS").off("click");
                $("#btnShowChartSucursalesServiciosS").on("click", function () {
                    $("#div-table-sucursales-servicios-s").fadeOut(400, function () {
                        $("#div-chart-sucursales-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-sucursales-servicios-s").offset().top - 60
                            }, 600);
                            drawChartSucursalesServiciosS();
                        });
                    });
                });

                $("#btnShowTableSucursalesServiciosS").off("click");
                $("#btnShowTableSucursalesServiciosS").on("click", function () {
                    $("#div-chart-sucursales-servicios-s").fadeOut(400, function () {
                        $("#div-table-sucursales-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-sucursales-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

                $("#btnShowChartAtiendeServiciosS").off("click");
                $("#btnShowChartAtiendeServiciosS").on("click", function () {
                    $("#div-table-atiende-servicios-s").fadeOut(400, function () {
                        $("#div-chart-atiende-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-chart-atiende-servicios-s").offset().top - 60
                            }, 600);
                            drawChartAtiendeServiciosS();
                        });
                    });
                });

                $("#btnShowTableAtiendeServiciosS").off("click");
                $("#btnShowTableAtiendeServiciosS").on("click", function () {
                    $("#div-chart-atiende-servicios-s").fadeOut(400, function () {
                        $("#div-table-atiende-servicios-s").fadeIn(400, function () {
                            $('html, body').animate({
                                scrollTop: $("#div-table-atiende-servicios-s").offset().top - 60
                            }, 600);
                        });
                    });
                });

            } else {
                evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
            }
        });
    }

    static lastFilter() {
        var data = arguments[0];
        var fechas = getFechasFiltro();
        evento.enviarEvento('Dashboard/CargaUltimofiltro', {data: data, fechas: fechas}, data.prevDiv, function (respuesta) {
            if (respuesta.code == 200) {
                $("#lastPage").empty().append(respuesta.html);

                tabla.generaTablaPersonal('#table-servicios-secondary-last', null, null, true, true, [[0, 'desc']]);

                initTableServiciosSecondaryLast();


                $(data.prevDiv).fadeOut(400, function () {
                    $("#lastPage").fadeIn(400, function () {
                        var positionButton = $("#btnBackTo").position();
                        $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
//                        drawEstatusTiposChart();
                    })
                });

                $("#btnBackToLast").off("click");
                $("#btnBackToLast").on("click", function () {
                    $("#lastPage").fadeOut(400, function () {
                        $(data.prevDiv).fadeIn(400);
                        $("#lastPage").empty();
                    });
                });

            } else {
                evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
            }
        });
    }

    static filterByEstatusPrioridad() {
        var idEstatus = arguments[0];
        var idPrioridad = arguments[1];
        var fechas = getFechasFiltro();
        if (parseInt(idEstatus) !== 0 && parseInt(idPrioridad)) {
            evento.enviarEvento('Dashboard/CargaPanelByEstatusPrioridad', {ids: {'estatus': idEstatus, 'prioridad': idPrioridad}, fechas: fechas}, '#secondaryPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#thirdPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#tipos_table_filtered_third', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-solicitudes-third', null, null, true, true, [[0, 'desc']]);

                    $('#tipos_table_filtered_third tbody').on('click', 'tr', function () {
                        var datos = $('#tipos_table_filtered_third').DataTable().row(this).data();
                        var data = {
                            'tipo': datos[0],
                            'sucursal': '',
                            'estatus': '',
                            'estatusSolicitud': idEstatus,
                            'prioridad': idPrioridad,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByTipoS(data);
                    });

                    initTableSolicitudesThird();

                    var table = tabla.getTableObject('#tipos_table_filtered_third');

                    table.on('search.dt', function () {
                        drawTiposChartThird();
                    });

                    $("#secondaryPage").fadeOut(400, function () {
                        $("#thirdPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToSecondary").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawTiposChartThird();
                        })
                    });

                    $("#btnBackToSecondary").off("click");
                    $("#btnBackToSecondary").on("click", function () {
                        $("#thirdPage").fadeOut(400, function () {
                            $("#secondaryPage").fadeIn(400);
                            $("#thirdPage").empty();
                        });
                    });
                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByPrioridadEstatus() {
        var idEstatus = arguments[1];
        var idPrioridad = arguments[0];
        var fechas = getFechasFiltro();
        if (parseInt(idEstatus) !== 0 && parseInt(idPrioridad)) {
            evento.enviarEvento('Dashboard/CargaPanelByPrioridadEstatus', {ids: {'estatus': idEstatus, 'prioridad': idPrioridad}, fechas: fechas}, '#secondaryPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#thirdPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#tipos_table_filtered_third', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-solicitudes-third', null, null, true, true, [[0, 'desc']]);

                    $('#tipos_table_filtered_third tbody').on('click', 'tr', function () {
                        var datos = $('#tipos_table_filtered_third').DataTable().row(this).data();
                        var data = {
                            'tipo': datos[0],
                            'sucursal': '',
                            'estatus': '',
                            'estatusSolicitud': idEstatus,
                            'prioridad': idPrioridad,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByTipoS(data);
                    });

                    initTableSolicitudesThird();

                    var table = tabla.getTableObject('#tipos_table_filtered_third');

                    table.on('search.dt', function () {
                        drawTiposChartThird();
                    });

                    $("#secondaryPage").fadeOut(400, function () {
                        $("#thirdPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToSecondary").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawTiposChartThird();
                        })
                    });

                    $("#btnBackToSecondary").off("click");
                    $("#btnBackToSecondary").on("click", function () {
                        $("#thirdPage").fadeOut(400, function () {
                            $("#secondaryPage").fadeIn(400);
                            $("#thirdPage").empty();
                        });
                    });
                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByEstatusTipo() {
        var idEstatus = arguments[0];
        var idTipo = arguments[1];
        var fechas = getFechasFiltro();
        if (parseInt(idEstatus) !== 0 && parseInt(idTipo) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByEstatusTipo', {ids: {'estatus': idEstatus, 'tipo': idTipo}, fechas: fechas}, '#initialPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#thirdPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#table-estatus-servicios-third', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-sucursales-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-atiende-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-servicios-third', null, null, true, true, [[0, 'desc']]);

                    $('#table-sucursales-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-sucursales-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'sucursal': datos[0],
                            'estatusSolicitud': idEstatus,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterBySucursal(data);
                    });

                    $('#table-atiende-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-atiende-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'atiende': datos[0],
                            'estatusSolicitud': idEstatus,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByAtiende(data);
                    });

                    $('#table-estatus-servicios-third tbody').on('click', 'tr', function () {
                        var datos = $('#table-estatus-servicios-third').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'estatus': datos[0],
                            'estatusSolicitud': idEstatus,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByEstatusS(data);
                    });


                    initTableServiciosThird();

                    var tableEstatusServicios = tabla.getTableObject('#table-estatus-servicios-third');

                    tableEstatusServicios.on('search.dt', function () {
                        drawEstatusServiciosChart();
                    });

                    $("#secondaryPage").fadeOut(400, function () {
                        $("#thirdPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToSecondary").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawEstatusServiciosChart();
                        })
                    });

                    $("#btnBackToSecondary").off("click");
                    $("#btnBackToSecondary").on("click", function () {
                        $("#thirdPage").fadeOut(400, function () {
                            $("#secondaryPage").fadeIn(400);
                            $("#thirdPage").empty();
                        });
                    });

                    $("#btnShowChartSucursalesServicios").off("click");
                    $("#btnShowChartSucursalesServicios").on("click", function () {
                        $("#div-table-sucursales-servicios").fadeOut(400, function () {
                            $("#div-chart-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-sucursales-servicios").offset().top - 60
                                }, 600);
                                drawChartSucursalesServicios();
                            });
                        });
                    });

                    $("#btnShowTableSucursalesServicios").off("click");
                    $("#btnShowTableSucursalesServicios").on("click", function () {
                        $("#div-chart-sucursales-servicios").fadeOut(400, function () {
                            $("#div-table-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-sucursales-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                    $("#btnShowChartAtiendeServicios").off("click");
                    $("#btnShowChartAtiendeServicios").on("click", function () {
                        $("#div-table-atiende-servicios").fadeOut(400, function () {
                            $("#div-chart-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-atiende-servicios").offset().top - 60
                                }, 600);
                                drawChartAtiendeServicios();
                            });
                        });
                    });

                    $("#btnShowTableAtiendeServicios").off("click");
                    $("#btnShowTableAtiendeServicios").on("click", function () {
                        $("#div-chart-atiende-servicios").fadeOut(400, function () {
                            $("#div-table-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-atiende-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });



                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }

    static filterByPrioridadTipo() {
        var idPrioridad = arguments[0];
        var idTipo = arguments[1];
        var fechas = getFechasFiltro();
        if (parseInt(idPrioridad) !== 0 && parseInt(idTipo) !== 0) {
            evento.enviarEvento('Dashboard/CargaPanelByPrioridadTipo', {ids: {'prioridad': idPrioridad, 'tipo': idTipo}, fechas: fechas}, '#initialPanel', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#thirdPage").empty().append(respuesta.html);

                    tabla.generaTablaPersonal('#table-estatus-servicios-third', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-sucursales-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-atiende-servicios', null, null, true, true, [[2, 'desc']], null, '<frt>', false);
                    tabla.generaTablaPersonal('#table-servicios-third', null, null, true, true, [[0, 'desc']]);
                    
                    $('#table-sucursales-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-sucursales-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'sucursal': datos[0],
                            'prioridad': idPrioridad,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterBySucursal(data);
                    });

                    $('#table-atiende-servicios tbody').on('click', 'tr', function () {
                        var datos = $('#table-atiende-servicios').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'atiende': datos[0],
                            'prioridad': idPrioridad,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByAtiende(data);
                    });
                    
                    $('#table-estatus-servicios-third tbody').on('click', 'tr', function () {
                        var datos = $('#table-estatus-servicios-third').DataTable().row(this).data();
                        var data = {
                            'tipo': idTipo,
                            'estatus': datos[0],
                            'prioridad': idPrioridad,
                            'prevDiv': '#thirdPage'
                        };
                        StaticCalls.filterByEstatusS(data);
                    });

                    initTableServiciosThird();

                    var tableEstatusServicios = tabla.getTableObject('#table-estatus-servicios-third');

                    tableEstatusServicios.on('search.dt', function () {
                        drawEstatusServiciosChart();
                    });

                    $("#secondaryPage").fadeOut(400, function () {
                        $("#thirdPage").fadeIn(400, function () {
                            var positionButton = $("#btnBackToSecondary").position();
                            $('html, body').animate({scrollTop: (positionButton.top + 5) + 'px'}, 800);
                            drawEstatusServiciosChart();
                        })
                    });

                    $("#btnBackToSecondary").off("click");
                    $("#btnBackToSecondary").on("click", function () {
                        $("#thirdPage").fadeOut(400, function () {
                            $("#secondaryPage").fadeIn(400);
                            $("#thirdPage").empty();
                        });
                    });

                    $("#btnShowChartSucursalesServicios").off("click");
                    $("#btnShowChartSucursalesServicios").on("click", function () {
                        $("#div-table-sucursales-servicios").fadeOut(400, function () {
                            $("#div-chart-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-sucursales-servicios").offset().top - 60
                                }, 600);
                                drawChartSucursalesServicios();
                            });
                        });
                    });

                    $("#btnShowTableSucursalesServicios").off("click");
                    $("#btnShowTableSucursalesServicios").on("click", function () {
                        $("#div-chart-sucursales-servicios").fadeOut(400, function () {
                            $("#div-table-sucursales-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-sucursales-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });

                    $("#btnShowChartAtiendeServicios").off("click");
                    $("#btnShowChartAtiendeServicios").on("click", function () {
                        $("#div-table-atiende-servicios").fadeOut(400, function () {
                            $("#div-chart-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-chart-atiende-servicios").offset().top - 60
                                }, 600);
                                drawChartAtiendeServicios();
                            });
                        });
                    });

                    $("#btnShowTableAtiendeServicios").off("click");
                    $("#btnShowTableAtiendeServicios").on("click", function () {
                        $("#div-chart-atiende-servicios").fadeOut(400, function () {
                            $("#div-table-atiende-servicios").fadeIn(400, function () {
                                $('html, body').animate({
                                    scrollTop: $("#div-table-atiende-servicios").offset().top - 60
                                }, 600);
                            });
                        });
                    });
                } else {
                    evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        }
    }
}

