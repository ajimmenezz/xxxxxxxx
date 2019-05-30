$(function () {
    //Objetos
    evento = new Base();
    websocket = new Socket();
    select = new Select();
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

    tabla.generaTablaPersonal('#data-table-tipo-proyectos', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-proyectos', null, null, true, true);

    //Inicializa funciones de la plantilla
    App.init();

    setGraph();
    selectTypeProyects();
    selectProyects();
});

var arrayFilters = {};
var listaGlobaldeProyectos;
var chartDashboard, dataDashboard, optionsDashboard;
function setGraph() {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(setGraphDashboard);
}

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
                    [tableInfoTypeProyects[i][1], parseInt(tableInfoTypeProyects[i][2])]
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

function resizeGraph(options){
    if (document.addEventListener) {
        window.addEventListener('resize', resizeChart);
    } else
    if (document.attachEvent) {
        window.attachEvent('onresize', resizeChart);
    } else {
        window.resize = resizeChart;
    }
        
    function resizeChart () {
        chartDashboard.draw(dataDashboard, options);
    }
}

function selectTypeProyects(){
    $('#data-table-tipo-proyectos tbody').on('click', 'tr', function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().row(this).data();
        var idTypeProyect = tableInfoTypeProyects[0];
        tabla.limpiarTabla('#data-table-proyectos')
        for (var i = 0; i < listaGlobaldeProyectos.length; i++) {
            if (listaGlobaldeProyectos[i][0] == idTypeProyect) {
                tabla.agregarFila('#data-table-proyectos', listaGlobaldeProyectos[i]);
            }
        }
        $('html, body').animate({
            scrollTop: $("#data-table-proyectos").offset().top - 60
        }, 600);
    });
}

function selectProyects(){
    $('#data-table-proyectos tbody').on('click', 'tr', function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
        var tableInfoProyects = $('#data-table-proyectos').DataTable().row(this).data();
        for (var i = 0; i < tableInfoTypeProyects.length; i++) {
            if(tableInfoTypeProyects[i][0] == tableInfoProyects[0]){
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
        if(tableInfoTypeProyects[i][1] == nameProyect){
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

function sendEventViewFilters(data){
    arrayFilters.moneda = 'MN';
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', data, '#panelDashboardGapsi', function (respuesta) {
        if (respuesta) {
            $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
            $('#contentDashboardGapsi').addClass('hidden');
            createDataView();
            eventsViewFilters();
            $('html, body').animate({
                scrollTop: $("#panelDashboardGapsiFilters").offset().top - 60
            }, 600);
        } else {
            alert('Hubo un problema con la solicitud de permiso.');
        }
    });
}

function createDataView(){
    createElements();
    createGraphs();
    $('#btnReturnDashboardGapsi').on('click', function () {
//        $('#dashboardGapsiFilters').empty().addClass('hidden');
//        $('#contentDashboardGapsi').removeClass('hidden');
        arrayFilters = {};
        location.reload();
    });
    
}

function createElements(){
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

function createGraphs(){
    $("#data-tipo-proyecto").ready(function () {
        var arrayTableFilterProyect = [];
        var infoTypeProyects = $('#data-tipo-proyecto').DataTable().rows().data();
        if(infoTypeProyects.length > 1){
            createDataGraph(infoTypeProyects, "chart_proyecto");
        }else{
            arrayFilters.proyecto = infoTypeProyects[0][0];
            arrayTableFilterProyect[0] = infoTypeProyects[0][0];
            arrayTableFilterProyect[1] = 'proyecto'
            arrayTableFilterProyect[2] = infoTypeProyects[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterProyect);
            $("#hideProyecto").css("display","none");
            $("#cardProyectos").css("display","none");
        }
    });
    $("#data-tipo-servicio").ready(function () {
        var arrayTableFilterService = [];
        var infoTypeService = $('#data-tipo-servicio').DataTable().rows().data();
        if(infoTypeService.length >1){
            createDataGraph(infoTypeService, "chart_servicios");
        }else{
            arrayFilters.servicio = infoTypeService[0][0];
            arrayTableFilterService[0] = infoTypeService[0][0];
            arrayTableFilterService[1] = 'servicio'
            arrayTableFilterService[2] = infoTypeService[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterService);
            $("#hideServicio").css("display","none");
            $("#cardServicios").css("display","none");
        }
    });
    $("#data-tipo-sucursal").ready(function () {
        var arrayTableFilterSucursal = [];
        var infoTypeSucursal = $('#data-tipo-sucursal').DataTable().rows().data();
        if(infoTypeSucursal.length > 1){
            createDataGraph(infoTypeSucursal, "chart_sucursal");
        }else{
            arrayFilters.sucursal = infoTypeSucursal[0][0];
            arrayTableFilterSucursal[0] = infoTypeSucursal[0][0];
            arrayTableFilterSucursal[1] = 'sucursal'
            arrayTableFilterSucursal[2] = infoTypeSucursal[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterSucursal);
            $("#hideSucursal").css("display","none");
            $("#cardSucursal").css("display","none");
        }
    });
    $("#data-tipo-categoria").ready(function () {
        var arrayTableFilterCategory = [];
        var infoTypeCategory = $('#data-tipo-categoria').DataTable().rows().data();
        if(infoTypeCategory.length >1){
            createDataGraph(infoTypeCategory, "chart_categoria");
        }else{
            arrayFilters.categoria = infoTypeCategory[0][0];
            arrayTableFilterCategory[0] = infoTypeCategory[0][0];
            arrayTableFilterCategory[1] = 'categoria'
            arrayTableFilterCategory[2] = infoTypeCategory[0][1];
            tabla.agregarFila('#data-tipo-filtros', arrayTableFilterCategory);
            $("#hideCategoria").css("display","none");
            $("#cardCategoria").css("display","none");
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

function createDataGraph(infoChart, panel){
    var chartFilter, dataFilter, optionsFilter;
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        
        dataFilter = new google.visualization.DataTable();
        dataFilter.addColumn('string', 'Topping');
        dataFilter.addColumn('number', 'Slices');
        for (var i = 0; i < infoChart.length; i++) {
            dataFilter.addRows([
                [infoChart[i][1], parseInt(infoChart[i][2])]
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

function eventsViewFilters(){
    addFilterBySelect();
    addFilterByTable();
    removeTableFilter();
}

function addFilterBySelect(){
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
}

function addFilterByTable(){
    var filtro = [];
    $('#data-tipo-proyecto tbody').on('click', 'tr', function(){
        var tableProyect = $('#data-tipo-proyecto').DataTable().row(this).data();
        filtro[0] = tableProyect[0];
        filtro[1] = 'Proyecto';
        elementsFilter(filtro);
    });
    $('#data-tipo-servicio').on('click', 'tr', function(){
        var tableService = $('#data-tipo-servicio').DataTable().row(this).data();
        filtro[0] = tableService[0];
        filtro[1] = 'Servicio';
        elementsFilter(filtro);
    });
    $('#data-tipo-sucursal').on('click', 'tr', function(){
        var tableSucursal = $('#data-tipo-sucursal').DataTable().row(this).data();
        filtro[0] = tableSucursal[0];
        filtro[1] = 'Sucursal';
        elementsFilter(filtro);
    });
    $('#data-tipo-categoria').on('click', 'tr', function(){
        var tableCategory = $('#data-tipo-categoria').DataTable().row(this).data();
        filtro[0] = tableCategory[0];
        filtro[1] = 'Categoria';
        elementsFilter(filtro);
    });
}

function elementsFilter(element){
    switch (element[1]){
        case 'Proyecto':
            arrayFilters.proyecto = element[0]
            break;
        case 'Servicio':
            arrayFilters.servicio = element[0]
            break;
        case 'Sucursal':
            arrayFilters.sucursal = element[0]
            break;
        case 'Categoria':
            arrayFilters.categoria = element[0]
            break;
    }
    sendFilters();
}

function removeTableFilter(){
    $('#data-tipo-filtros tbody').on('click', 'tr', function(){
        var tableFilter = $('#data-tipo-filtros').DataTable().row(this).data();
        for (var key in arrayFilters) {
            if(arrayFilters[key] == tableFilter[0] && key == tableFilter[1]){
                delete arrayFilters[key];
            }
        }
        sendFilters();
    });
}

function sendFilters(){
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', arrayFilters, '#panelDashboardGapsi', function (respuesta) {
        if (respuesta) {
            $('#dashboardGapsiFilters').empty().append(respuesta.formulario);
            createDataView();
            eventsViewFilters();
            $('html, body').animate({
                scrollTop: $("#panelDashboardGapsiFilters").offset().top - 60
            }, 600);
        } else {
            alert('Hubo un problema con la solicitud de permiso.');
        }
    });
}
