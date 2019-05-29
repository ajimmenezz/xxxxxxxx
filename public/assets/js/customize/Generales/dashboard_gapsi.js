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
                var tipoProyecto = tableInfoTypeProyects[i][1];
            }
        }
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
    var dataSearch = {
        tipoProyecto: nameProyect,
        moneda: 'MN'
    }
    sendEventViewFilters(dataSearch);
}

function sendEventViewFilters(data){
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', data, '#panelDashboardGapsi', function (respuesta) {
        if (respuesta) {
            $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
            $('#contentDashboardGapsi').addClass('hidden');
            createDataView();
            eventsViewFilters();
        } else {
            alert('Hubo un problema con la solicitud de permiso.');
        }
    });
}

function createDataView(){
    createElements();
    createGraphs();
    $('#btnReturnDashboardGapsi').on('click', function () {
        $('#dashboardGapsiFilters').empty().addClass('hidden');
        $('#contentDashboardGapsi').removeClass('hidden');
    });
    
}

function createElements(){
    tabla.generaTablaPersonal('#data-tipo-filtros', null, null, true, true, [], null, '', false);
    tabla.generaTablaPersonal('#data-tipo-proyecto', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-serivicio', null, null, true, true);
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
        var infoTypeProyects = $('#data-tipo-proyecto').DataTable().rows().data();
        if(infoTypeProyects.length > 1){
            createDataGraph(infoTypeProyects, "chart_proyecto");
        }else{
            $("#cardProyectos").css("display","none");
        }
    });
    $("#data-tipo-serivicio").ready(function () {
        var infoTypeService = $('#data-tipo-serivicio').DataTable().rows().data();
        createDataGraph(infoTypeService, "chart_servicios");
    });
    $("#data-tipo-sucursal").ready(function () {
        var infoTypeSucursal = $('#data-tipo-sucursal').DataTable().rows().data();
        createDataGraph(infoTypeSucursal, "chart_sucursal");
    });
    $("#data-tipo-categoria").ready(function () {
        var infoTypeCategory = $('#data-tipo-categoria').DataTable().rows().data();
        createDataGraph(infoTypeCategory, "chart_categoria");
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
    addTableFilterHideSelect();
}

function addTableFilterHideSelect(){
    var filtro = [];
    filtro[2] = '<button type="button" class="close"><span aria-hidden="true">&times;</span>';
    $("#selectProyecto").on('change', function () {
        var proyecto = $('#selectProyecto').val();
        filtro[0] = 'Proyecto';
        filtro[1] = proyecto;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideProyecto").css("display","none");
    });
    $("#selectServicio").on('change', function () {
        var servicio = $('#selectServicio').val();
        filtro[0] = 'Servicio';
        filtro[1] = servicio;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideServicio").css("display","none");
    });
    $("#selectSucursal").on('change', function () {
        var sucursal = $('#selectSucursal').val();
        filtro[0] = 'Sucursal';
        filtro[1] = sucursal;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideSucursal").css("display","none");
    });
    $("#selectCategoria").on('change', function () {
        var categoria = $('#selectCategoria').val();
        filtro[0] = 'Categoria';
        filtro[1] = categoria;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideCategoria").css("display","none");
    });
    $("#selectSubCategoria").on('change', function () {
        var subCategoria = $('#selectSubCategoria').val();
        filtro[0] = 'SubCategoria';
        filtro[1] = subCategoria;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideSubCategoria").css("display","none");
    });
    $("#selectConcepto").on('change', function () {
        var concepto = $('#selectConcepto').val();
        filtro[0] = 'Concepto';
        filtro[1] = concepto;
        tabla.agregarFila('#data-tipo-filtros', filtro);
        $("#hideConcepto").css("display","none");
    });
    
}
