$(function () {
    //Objetos
    evento = new Base();
    websocket = new Socket();
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

    //Globales
    let tablaTipoProyecto = new TablaBasica('data-table-tipo-proyectos');
    let tablaProyectos = new TablaBasica('data-table-proyectos');
    let tablaProyecto = null;
    let tablaServicio = null;
    let tablaSucursal = null;
    let tablaCategoria = null;
    let tablaSubCategoria = null;
    let tablaConcepto = null;
    let graficaPrincipal = new GraficaGoogle('graphDashboard', tablaTipoProyecto.datosTabla());
    let graficaProyecto = null;
    let graficaServicio = null;
    let graficaSucursal = null;
    let graficaCategoria = null;
    let graficaSubCategoria = null;
    let graficaConcepto = null;

    let datosProyectos = null;
    let datosServicios = null;
    let datosSucursales = null;
    let datosCategoria = null;
    let datosSubCategoria = null;
    let datosConceptos = null;

    let selectorProyectos = null;
    let selectorServicios = null;
    let selectorSucursales = null;
    let selectorCategorias = null;

    let datosProyecto = Array();

    graficaPrincipal.inicilizarGrafica();
    setDastosProyectos();
    graficaPrincipal.agregarListener(function(dato){
        let data = {tipoProyecto: dato, moneda: 'MN'};
        evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', data, '#panelDashboardGapsi', function (respuesta) {

            if (respuesta.consulta.length !== 0) {
                $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentDashboardGapsi').addClass('hidden');

                //ocultarElemento('Proyectos');
                incializarDatos(respuesta.consulta);
                incializarObjetos();
                listenerEventosGraficas();

                $('html, body').animate({
                    scrollTop: $("#contentDashboardGapsiFilters").offset().top - 40
                }, 600);


            } else {
                errorFilter = 'esta Consulta';
                setTimeout(modalUndefined, 1000);
            }
        });
    });

    tablaTipoProyecto.evento(function () {
        let datosfila = tablaTipoProyecto.datosFila(this);
        let datosFiltradosProyecto = null;
        tablaProyectos.limpiartabla();
        datosFiltradosProyecto = filtrarDatos(datosProyecto, {condicion: 'tipo', valor: datosfila[0]});
        $.each(datosFiltradosProyecto, function (key, value) {
            tablaProyectos.agregarDatosFila([
                value.tipo,
                value.id,
                value.nombre,
                value.gasto,
                value.fecha
            ]);
        });

        $('html, body').animate({
            scrollTop: $("#titulo-tabla-proyectos").offset().top - 60
        }, 600);
    });

    tablaProyectos.evento(function () {
        let datosfila = tablaProyectos.datosFila(this);
        let data = {tipoProyecto: datosfila[0], moneda: 'MN', proyecto: datosfila[1]};
        evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', data, '#panelDashboardGapsi', function (respuesta) {

            if (respuesta.consulta.length !== 0) {
                $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentDashboardGapsi').addClass('hidden');

                ocultarElemento('Proyectos');
                incializarDatos(respuesta.consulta);
                incializarObjetos();
                listenerEventosGraficas();

                $('html, body').animate({
                    scrollTop: $("#contentDashboardGapsiFilters").offset().top - 40
                }, 600);


            } else {
                errorFilter = 'esta Consulta';
                setTimeout(modalUndefined, 1000);
            }
        });
    });

    function incializarDatos(datos) {
        //console.log(datos);
        datosProyectos = datos.proyectos;
        datosServicios = datos.servicios;
        datosSucursales = datos.sucursales;
        datosCategoria = datos.categorias;
        datosSubCategoria = datos.subcategorias;
        datosConceptos = datos.concepto;
    }


    function incializarObjetos() {
        tablaProyecto = new TablaBasica('data-tipo-proyecto');
        tablaServicio = new TablaBasica('data-tipo-servicio');
        tablaSucursal = new TablaBasica('data-tipo-sucursal');
        tablaCategoria = new TablaBasica('data-tipo-categoria');
        tablaSubCategoria = new TablaBasica('data-tipo-subCategoria');
        tablaConcepto = new TablaBasica('data-tipo-concepto');
        graficaProyecto = new GraficaGoogle('chart_proyecto', filtrarDatosGraficaGoogle(datosProyectos, 'Proyecto', 'Gasto'));
        graficaServicio = new GraficaGoogle('chart_servicios', filtrarDatosGraficaGoogle(datosServicios, 'TipoServicio', 'Gasto'));
        graficaSucursal = new GraficaGoogle('chart_sucursal', filtrarDatosGraficaGoogle(datosSucursales, 'Sucursal', 'Gasto'));
        graficaCategoria = new GraficaGoogle('chart_categoria', filtrarDatosGraficaGoogle(datosCategoria, 'Categoria', 'Gasto'));
        graficaSubCategoria = new GraficaGoogle('chart_subCategoria', filtrarDatosGraficaGoogle(datosSubCategoria, 'SubCategoria', 'Gasto'));
        graficaConcepto = new GraficaGoogle('chart_concepto', filtrarDatosGraficaGoogle(datosConceptos, 'Concepto', 'Gasto'));
        graficaProyecto.inicilizarGrafica();
        graficaServicio.inicilizarGrafica();
        graficaSucursal.inicilizarGrafica();
        graficaCategoria.inicilizarGrafica();
        graficaSubCategoria.inicilizarGrafica();
        graficaConcepto.inicilizarGrafica();
        selectorProyectos = new SelectBasico('selectProyecto');
        selectorServicios = new SelectBasico('selectServicio');
        selectorSucursales = new SelectBasico('selectSucursal');
        selectorCategorias = new SelectBasico('selectCategoria');
        selectorProyectos.iniciarSelect();
        selectorServicios.iniciarSelect();
        selectorSucursales.iniciarSelect();
        selectorCategorias.iniciarSelect();
    };

    function listenerEventosGraficas(){
        graficaProyecto.agregarListener(function (dato){
            console.log(dato);
        });
        graficaServicio.agregarListener(function (dato){
            console.log(dato);
        });
        graficaSucursal.agregarListener(function (dato){
            console.log(dato);
        });
        graficaCategoria.agregarListener(function (dato){
            console.log(dato);
        });
        graficaSubCategoria.agregarListener(function (dato){
            console.log(dato);
        });
        graficaConcepto.agregarListener(function (dato){
            console.log(dato);
        });
    }
    
    function filtrarDatosGraficaGoogle(datos, clave, valor) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            datosFiltrados.push([value[clave], value[valor]]);
        });
        return datosFiltrados;

    }

    function mostrarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.hasClass('hidden')) {
            elemento.removeClass('hidden');
    }
    }

    function ocultarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (!elemento.hasClass('hidden')) {
            elemento.addClass('hidden');
    }
    }

    function setDastosProyectos() {
        let temporal = tablaProyectos.datosTabla();
        $.each(temporal, function (key, value) {
            datosProyecto.push({
                id: value[1],
                nombre: value[2],
                tipo: value[0],
                gasto: value[3],
                fecha: value[4]
            });
        });
    }

    function filtrarDatos(datos, filtros) {
        let datosFiltrados = [];
        $.each(datos, function (key, value) {
            if (filtros.valor === value[filtros.condicion]) {
                datosFiltrados.push(value);
            }
        });
        return datosFiltrados;
    }


//    selectTypeProyects();
//    selectProyect();
});

var arrayFilters = {};
var errorFilter;
var listaGlobaldeProyectos;
var chartDashboard, dataDashboard, optionsDashboard;


function setGraphDashboard() {

    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        dataDashboard = new google.visualization.DataTable();
        dataDashboard.addColumn('string', 'Topping');
        dataDashboard.addColumn('number', 'Proyectos');
        $("#data-table-tipo-proyectos").ready(function () {
            listaGlobaldeProyectos = $('#data-table-proyectos').DataTable().rows().data();
            var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
            for (var i = 0; i < tableInfoTypeProyects.length; i++) {
                dataDashboard.addRows([
                    [tableInfoTypeProyects[i][0], parseInt(tableInfoTypeProyects[i][1])]
                ]);
            }
        });

        optionsDashboard = {is3D: true};

        chartDashboard = new google.visualization.PieChart(document.getElementById('graphDashboard'));
        google.visualization.events.addListener(chartDashboard, 'select', selectGraphProyect);
        chartDashboard.draw(dataDashboard, optionsDashboard);
    }

    resizeGraph(optionsDashboard);
}

function resizeGraph(options) {
    if (document.addEventListener) {
//        window.addEventListener('resize', resizeChart);
    } else
    if (document.attachEvent) {
//        window.attachEvent('onresize', resizeChart);
    } else {
//        window.resize = resizeChart;
    }

    function resizeChart() {
//        chartDashboard.draw(dataDashboard, options);
    }
}

function selectTypeProyects() {
    $('#data-table-tipo-proyectos tbody').on('click', 'tr', function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().row(this).data();
        var typeProyect = tableInfoTypeProyects[0];
        tabla.limpiarTabla('#data-table-proyectos')
        for (var i = 0; i < listaGlobaldeProyectos.length; i++) {
            if (listaGlobaldeProyectos[i][0] == typeProyect) {
                tabla.agregarFila('#data-table-proyectos', listaGlobaldeProyectos[i]);
            }
        }
        $('html, body').animate({
            scrollTop: $("#data-table-proyectos").offset().top - 60
        }, 600);
    });
}

function selectProyect() {
    $('#data-table-proyectos tbody').on('click', 'tr', function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
        var tableInfoProyects = $('#data-table-proyectos').DataTable().row(this).data();
        for (var i = 0; i < tableInfoTypeProyects.length; i++) {
            if (tableInfoTypeProyects[i][0] == tableInfoProyects[0]) {
                var tipoProyecto = tableInfoTypeProyects[i][0];
            }
        }
        arrayFilters.tipoProyecto = tipoProyecto;
        var dataSearch = {
            tipoProyecto: tipoProyecto,
            moneda: 'MN',
            proyecto: tableInfoProyects[1]
        }
        sendEventViewFilters(dataSearch);
    });
}

function selectGraphProyect() {
    var selectedItem = chartDashboard.getSelection()[0];
    var nameProyect = dataDashboard.getValue(selectedItem.row, 0);
    var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
    for (var i = 0; i < tableInfoTypeProyects.length; i++) {
        if (tableInfoTypeProyects[i][0] == nameProyect) {
            var tipoProyecto = tableInfoTypeProyects[i][0];
        }
    }
    arrayFilters.tipoProyecto = tipoProyecto;
    var dataSearch = {
        tipoProyecto: tipoProyecto,
        moneda: 'MN'
    }
    sendEventViewFilters(dataSearch);
}

function sendEventViewFilters(data) {
    arrayFilters.moneda = 'MN';
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', data, '#panelDashboardGapsi', function (respuesta) {
        if (respuesta.consulta.proyectos.length !== 0) {
            if (respuesta) {
                $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentDashboardGapsi').addClass('hidden');
                createDataView();
                eventsViewFilters();
                filterDate();
                $('html, body').animate({
                    scrollTop: $("#panelDashboardGapsiFilters").offset().top - 60
                }, 600);
            } else {
                alert('Hubo un problema con la solicitud de permiso.');
            }
        } else {
            errorFilter = 'esta Consulta';
            setTimeout(modalUndefined, 1000);
        }
    });
}

function createDataView() {
    createElements();
    getDataGraphs();
    $('#btnReturnDashboardGapsi').on('click', function () {
//        $('#dashboardGapsiFilters').empty().addClass('hidden');
//        $('#contentDashboardGapsi').removeClass('hidden');
        arrayFilters = {};
        location.reload();
    });

}

function createElements() {
    tabla.generaTablaPersonal('#data-tipo-filtros', null, null, true, true, [], null, '', false);
    tabla.generaTablaPersonal('#data-tipo-proyecto', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-servicio', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-sucursal', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-categoria', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-subCategoria', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-concepto', null, null, true, true);

    select.crearSelect("#selectProyecto");
    select.crearSelect("#selectServicio");
    select.crearSelect("#selectSucursal");
    select.crearSelect("#selectCategoria");
    select.crearSelect("#selectSubCategoria");
    select.crearSelect("#selectConcepto");
    select.crearSelect("#selectMoneda");
}

function getDataGraphs() {
    $("#data-tipo-proyecto").ready(function () {
        var arrayTableFilterProyect = [];
        var infoTypeProyects = $('#data-tipo-proyecto').DataTable().rows().data();
        if (infoTypeProyects.length > 1) {
            createDataGraph(infoTypeProyects, "chart_proyecto");
        } else {
            arrayFilters.proyecto = infoTypeProyects[0][0];
            arrayTableFilterProyect[0] = infoTypeProyects[0][0];
            arrayTableFilterProyect[1] = 'proyecto'
            arrayTableFilterProyect[2] = infoTypeProyects[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterProyect);
            $("#hideProyecto").css("display", "none");
            $("#cardProyectos").css("display", "none");
        }
    });
    $("#data-tipo-servicio").ready(function () {
        var arrayTableFilterService = [];
        var infoTypeService = $('#data-tipo-servicio').DataTable().rows().data();
        if (infoTypeService.length > 1) {
            createDataGraph(infoTypeService, "chart_servicios");
        } else {
            arrayFilters.servicio = infoTypeService[0][1];
            arrayTableFilterService[0] = infoTypeService[0][1];
            arrayTableFilterService[1] = 'servicio';
            arrayTableFilterService[2] = infoTypeService[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterService);
            $("#hideServicio").css("display", "none");
            $("#cardServicios").css("display", "none");
        }
    });
    $("#data-tipo-sucursal").ready(function () {
        var arrayTableFilterSucursal = [];
        var infoTypeSucursal = $('#data-tipo-sucursal').DataTable().rows().data();
        if (infoTypeSucursal.length > 1) {
            createDataGraph(infoTypeSucursal, "chart_sucursal");
        } else {
            arrayFilters.sucursal = infoTypeSucursal[0][0];
            arrayTableFilterSucursal[0] = infoTypeSucursal[0][0];
            arrayTableFilterSucursal[1] = 'sucursal';
            arrayTableFilterSucursal[2] = infoTypeSucursal[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterSucursal);
            $("#hideSucursal").css("display", "none");
            $("#cardSucursal").css("display", "none");
        }
    });
    $("#data-tipo-categoria").ready(function () {
        var arrayTableFilterCategory = [];
        var infoTypeCategory = $('#data-tipo-categoria').DataTable().rows().data();
        if (infoTypeCategory.length > 1) {
            createDataGraph(infoTypeCategory, "chart_categoria");
        } else {
            arrayFilters.categoria = infoTypeCategory[0][1];
            arrayTableFilterCategory[0] = infoTypeCategory[0][1];
            arrayTableFilterCategory[1] = 'categoria';
            arrayTableFilterCategory[2] = infoTypeCategory[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterCategory);
            $("#hideCategoria").css("display", "none");
            $("#cardCategoria").css("display", "none");
        }
    });
    $("#data-tipo-subCategoria").ready(function () {
        var infoTypeCategory = $('#data-tipo-subCategoria').DataTable().rows().data();
        createDataGraph(infoTypeCategory, "chart_subCategoria");
    });
    $("#data-tipo-concepto").ready(function () {
        var infoTypeCategory = $('#data-tipo-concepto').DataTable().rows().data();
        createDataGraph(infoTypeCategory, "chart_concepto");
    });
}

function createDataGraph(infoChart, panel) {
    var chartFilter, dataFilter, optionsFilter;
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        dataFilter = new google.visualization.DataTable();
        dataFilter.addColumn('string', 'Topping');
        dataFilter.addColumn('number', 'Slices');
        for (var i = 0; i < infoChart.length; i++) {
            var gastos = infoChart[i][2].split(' ');
            var x = gastos[1].split(',');
            var y
            if (x.length == 1) {
                y = x[0];
            } else {
                if (x.length == 2) {
                    y = x[0] + x[1]
                } else {
                    if (x.length == 3) {
                        y = x[0] + x[1] + x[2]
                    } else {
                        if (x.length == 4) {
                            y = x[0] + x[1] + x[2] + x[3]
                        } else {
                            if (x.length == 5) {
                                y = x[0] + x[1] + x[2] + x[3] + x[4]
                            } else {
                                if (x.length == 6) {
                                    y = x[0] + x[1] + x[2] + x[3] + x[4] + x[5]
                                } else {
                                    y = x[0] + x[1] + x[2] + x[3] + x[4] + x[5] + x[6]
                                }
                            }
                        }
                    }
                }
            }
            //console.log(x+'==>'+y)
            dataFilter.addRows([
                [infoChart[i][1], parseInt(y)]
            ]);
        }

        optionsFilter = {is3D: true};

        chartFilter = new google.visualization.PieChart(document.getElementById(panel));
        chartFilter.draw(dataFilter, optionsFilter);
    }
    //resizeGraphFilter(optionsFilter)
}

//function resizeGraphFilter(options){
//    if (document.addEventListener) {
//        window.addEventListener('resize', resizeChart);
//    }
//    else 
//        if (document.attachEvent) {
//            window.attachEvent('onresize', resizeChart);
//        }else {
//            window.resize = resizeChart;
//        }
//        
//    function resizeChart () {
//        chartFilter.draw(dataFilter, options);
//    }
//}

function eventsViewFilters() {
    addFilterBySelect();
    addFilterByTable();
    removeTableFilter();
}

function addFilterBySelect() {
    var filtro = [];
    $("#selectProyecto").on('change', function () {
        var proyecto = $('#selectProyecto').val();
        filtro[0] = proyecto;
        filtro[1] = 'Proyecto';
        elementsFilter(filtro);
    });
    $("#selectServicio").on('change', function () {
        var servicio = $('#selectServicio').val();
        filtro[0] = servicio;
        filtro[1] = 'Servicio';
        elementsFilter(filtro);
    });
    $("#selectSucursal").on('change', function () {
        var sucursal = $('#selectSucursal').val();
        filtro[0] = sucursal;
        filtro[1] = 'Sucursal';
        elementsFilter(filtro);
    });
    $("#selectCategoria").on('change', function () {
        var categoria = $('#selectCategoria').val();
        filtro[0] = categoria;
        filtro[1] = 'Categoria';
        elementsFilter(filtro);
    });
    $("#selectMoneda").on('change', function () {
        var moneda = $('#selectMoneda').val();
        filtro[0] = moneda;
        filtro[1] = 'Moneda';
        elementsFilter(filtro);
    });
}

function addFilterByTable() {
    var filtro = [];
    $('#data-tipo-proyecto tbody').on('click', 'tr', function () {
        var tableProyect = $('#data-tipo-proyecto').DataTable().row(this).data();
        filtro[0] = tableProyect[0];
        filtro[1] = 'Proyecto';
        elementsFilter(filtro);
    });
    $('#data-tipo-servicio').on('click', 'tr', function () {
        var tableService = $('#data-tipo-servicio').DataTable().row(this).data();
        filtro[0] = tableService[1];
        filtro[1] = 'Servicio';
        elementsFilter(filtro);
    });
    $('#data-tipo-sucursal').on('click', 'tr', function () {
        var tableSucursal = $('#data-tipo-sucursal').DataTable().row(this).data();
        filtro[0] = tableSucursal[0];
        filtro[1] = 'Sucursal';
        elementsFilter(filtro);
    });
    $('#data-tipo-categoria').on('click', 'tr', function () {
        var tableCategory = $('#data-tipo-categoria').DataTable().row(this).data();
        filtro[0] = tableCategory[1];
        filtro[1] = 'Categoria';
        elementsFilter(filtro);
    });
}

function elementsFilter(element) {
    switch (element[1]) {
        case 'Proyecto':
            if (element[0] !== '' && element[0] !== "SIN CATEGORIA") {
                arrayFilters.proyecto = element[0];
            } else {
                errorFilter = 'este ' + element[1];
                setTimeout(modalUndefined, 1000);
            }
            break;
        case 'Servicio':
            if (element[0] !== '' && element[0] !== "SIN CATEGORIA") {
                arrayFilters.servicio = element[0];
            } else {
                errorFilter = 'este ' + element[1];
                setTimeout(modalUndefined, 1000);
            }
            break;
        case 'Sucursal':
            if (element[0] !== '' && element[0] !== "SIN CATEGORIA") {
                arrayFilters.sucursal = element[0];
            } else {
                errorFilter = 'esta ' + element[1];
                setTimeout(modalUndefined, 1000);
            }
            break;
        case 'Categoria':
            if (element[0] !== '' && element[0] !== "SIN CATEGORIA") {
                arrayFilters.categoria = element[0];
            } else {
                errorFilter = 'esta ' + element[1];
                setTimeout(modalUndefined, 1000);
            }
            break;
        case 'Moneda':
            arrayFilters.moneda = element[0];
            break;
    }
    sendFilters();
}

function removeTableFilter() {
    $('#data-tipo-filtros tbody').on('click', 'tr', function () {
        var tableFilter = $('#data-tipo-filtros').DataTable().row(this).data();
        for (var key in arrayFilters) {
            if (arrayFilters[key] == tableFilter[0] && key == tableFilter[1]) {
                delete arrayFilters[key];
            }
        }
        sendFilters();
    });
}

function sendFilters() {
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', arrayFilters, '#panelDashboardGapsiFilters', function (respuesta) {
        if (respuesta.consulta.proyectos.length !== 0) {
            if (respuesta) {
                $('#dashboardGapsiFilters').empty().append(respuesta.formulario);
                createDataView();
                eventsViewFilters();
                filterDate();
                $('html, body').animate({
                    scrollTop: $("#panelDashboardGapsiFilters").offset().top - 60
                }, 600);
            } else {
                alert('Hubo un problema con la solicitud de permiso.');
            }
        } else {
            errorFilter = 'esta Consulta';
            setTimeout(modalUndefined, 1000);
            if (arrayFilters.moneda == 'USD') {
                arrayFilters.moneda = 'MN';
            } else
                arrayFilters.moneda = 'USD';
        }
    });
}

function modalUndefined() {
    var html = '<div class="row m-t-20">\n\
        <form id="idUndefined" class="margin-bottom-0" enctype="multipart/form-data">\n\
            <div id="modal-dialogo" class="col-md-12 text-center">\n\
                <h4>No hay información para ' + errorFilter + '</h4><br>\n\
                <button id="btnAceptar" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
            </div>\n\
        </form>\n\
        </div>';
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    evento.mostrarModal('Sin Datos', html);
    $('#btnAceptar').on('click', function () {
        evento.cerrarModal();
    });
}

function filterDate() {
    $('#desde').datetimepicker({
        format: 'YYYY/DD/MM'
    });
    $('#hasta').datetimepicker({
        format: 'YYYY/DD/MM',
        useCurrent: false //Important! See issue #1075
    });
    $("#desde").on("dp.change", function (e) {
        $('#hasta').data("DateTimePicker").minDate(e.date);
    });
    $("#hasta").on("dp.change", function (e) {
        $('#desde').data("DateTimePicker").maxDate(e.date);
    });
    $("#btnFiltrarDashboard").on('click', function () {
        arrayFilters.fechaInicio = $("#fechaComienzo").val();
        arrayFilters.fechaFinal = $("#fechaFin").val();
        sendFilters()
    });
}