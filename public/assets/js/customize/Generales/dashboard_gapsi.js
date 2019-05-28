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
var chartFilter, dataFilter, optionsFilter;
function setGraph(){
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(setGraphDashboard);
}

function setGraphDashboard() {
        
    google.charts.load('current', {'packages':['corechart']});
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
    }
    else 
        if (document.attachEvent) {
            window.attachEvent('onresize', resizeChart);
        }else {
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
    });
}

function selectProyects(){
    $('#data-table-proyectos tbody').on('click', 'tr', function () {
        var tableInfoProyects = $('#data-table-proyectos').DataTable().row(this).data();
        //sendEventViewFilters(tableInfoProyects);
    });
}

function selectGraphProyect() {
    var selectedItem = chartDashboard.getSelection()[0];
    var nameProyect = dataDashboard.getValue(selectedItem.row, 0);
    sendEventViewFilters(nameProyect);
}

function sendEventViewFilters(search){
    var dataSearch = {tipoProyecto: search}
    evento.enviarEvento('Dashboard_Gapsi/tipoProyecto', dataSearch, '#panelDashboardGapsi', function (respuesta) {
        if (respuesta) {
            $('#dashboardGapsiFilters').removeClass('hidden').empty().append(respuesta.formulario);
            $('#contentDashboardGapsi').addClass('hidden');
            eventsDashboardFilters();
        } else {
            alert('Hubo un problema con la solicitud de permiso.');
        }
    });
}

function eventsDashboardFilters(){
    createTables();
    $('#btnReturnDashboardGapsi').on('click', function () {
        $('#dashboardGapsiFilters').empty().addClass('hidden');
        $('#contentDashboardGapsi').removeClass('hidden');
    });
    
    $("#data-tipo-proyecto").ready(function () {
        var infoTypeProyects = $('#data-tipo-proyecto').DataTable().rows().data();
        createDataViewGraph(infoTypeProyects, "chart_proyecto");
    });
    $("#data-tipo-serivicio").ready(function () {
        var infoTypeProyects = $('#data-tipo-serivicio').DataTable().rows().data();
        createDataViewGraph(infoTypeProyects, "chart_servicios");
    });
    $("#data-tipo-sucursal").ready(function () {
        var infoTypeProyects = $('#data-tipo-sucursal').DataTable().rows().data();
        createDataViewGraph(infoTypeProyects, "chart_sucursal");
    });
}

function createTables(){
    tabla.generaTablaPersonal('#data-tipo-proyecto', null, null, true, true);
    tabla.generaTablaPersonal('#data-tipo-serivicio', null, null, true, true, [], null, '<frt>', false);
    tabla.generaTablaPersonal('#data-tipo-sucursal', null, null, true, true, [], null, '<frt>', false);
    tabla.generaTablaPersonal('#data-tipo-categoria', null, null, true, true, [], null, '<frt>', false);
    tabla.generaTablaPersonal('#data-tipo-SubCategoria', null, null, true, true, [], null, '<frt>', false);
    tabla.generaTablaPersonal('#data-tipo-concepto', null, null, true, true, [], null, '<frt>', false);
}
/****************************************************************************************/
function createDataViewGraph(infoChart, panel){
    
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
    resizeGraphFilter(optionsFilter)
}

function resizeGraphFilter(options){
    if (document.addEventListener) {
        window.addEventListener('resize', resizeChart);
    }
    else 
        if (document.attachEvent) {
            window.attachEvent('onresize', resizeChart);
        }else {
            window.resize = resizeChart;
        }
        
    function resizeChart () {
        chartFilter.draw(dataFilter, options);
    }
}