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
    
    tabla.generaTablaPersonal('#data-table-tipo-proyectos', null, null, true, true, [[1, 'desc']]);
    tabla.generaTablaPersonal('#data-table-proyectos', null, null, true, true);

    //Inicializa funciones de la plantilla
    App.init();
   
    setGraph();
    selectTypeProyects();
    selectProyects();
/***********************************************************************/
//    setChartA();
//    setChartB();
//    setChartC();
//    setChartD();
//    setChartE();
//    setChartF();
});

var chart, data, options;
function setGraph(){
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(setGraphDashboard);
}

function setGraphDashboard() {
        
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Proyectos');
        $("#data-table-tipo-proyectos").ready(function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
            for (var i = 0; i < tableInfoTypeProyects.length; i++) {
                data.addRows([
                    [tableInfoTypeProyects[i][0], parseInt(tableInfoTypeProyects[i][1])]
                ]);
            }
        });

        options = {is3D: true};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('graphDashboard'));
        chart.draw(data, options);
    }
//    data = new google.visualization.DataTable();
//    data.addColumn('string', 'Topping');
//    data.addColumn('number', 'Proyectos');
//    $("#data-table-tipo-proyectos").ready(function () {
//        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().rows().data();
//        for (var i = 0; i < tableInfoTypeProyects.length; i++) {
//            data.addRows([
//                [tableInfoTypeProyects[i][0], parseInt(tableInfoTypeProyects[i][1])]
//            ]);
//        }
//    });
//
//    var options = {
//        hAxis: {
//            title: 'Tipo de Proyecto'
//        },
//        legend: {position: 'none'}
//    };
//    chart = new google.visualization.PieChart(document.getElementById('graphDashboard'));
//    google.visualization.events.addListener(chart, 'select', selectGraphProyect);
//    chart.draw(data, options);
    
    resizeGraph(options);
}

function selectGraphProyect() {
    var selectedItem = chart.getSelection()[0];
    var value = data.getValue(selectedItem.row, 0);
    //alert('The user selected ' + value);
    console.log(data.getValue(selectedItem.row, 0));
}

function selectTypeProyects(){
    $('#data-table-tipo-proyectos tbody').on('click', 'tr', function () {
        var tableInfoTypeProyects = $('#data-table-tipo-proyectos').DataTable().row(this).data();
        console.log(tableInfoTypeProyects);
    });
}

function selectProyects(){
    $('#data-table-proyectos tbody').on('click', 'tr', function () {
        var tableInfoProyects = $('#data-table-proyectos').DataTable().row(this).data();
        console.log(tableInfoProyects);
    });
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
        chart.draw(data, options);
    }
}
/****************************************************************************************/
//function setChartA(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_proyecto'));
//        chart.draw(data, options);
//      }
//}
//function setChartB(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_B'));
//        chart.draw(data, options);
//      }
//}
//function setChartC(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_C'));
//        chart.draw(data, options);
//      }
//}
//function setChartD(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_D'));
//        chart.draw(data, options);
//      }
//}
//function setChartE(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_E'));
//        chart.draw(data, options);
//      }
//}
//function setChartF(){
//    // Load the Visualization API and the corechart package.
//      google.charts.load('current', {'packages':['corechart']});
//
//      // Set a callback to run when the Google Visualization API is loaded.
//      google.charts.setOnLoadCallback(drawChart);
//
//      // Callback that creates and populates a data table,
//      // instantiates the pie chart, passes in the data and
//      // draws it.
//      function drawChart() {
//
//        // Create the data table.
//        var data = new google.visualization.DataTable();
//        data.addColumn('string', 'Topping');
//        data.addColumn('number', 'Slices');
//        data.addRows([
//          ['Mushrooms', 3],
//          ['Onions', 1],
//          ['Olives', 1],
//          ['Zucchini', 1],
//          ['Pepperoni', 2]
//        ]);
//
//        // Set chart options
//        var options = {is3D: true};
//
//        // Instantiate and draw our chart, passing in some options.
//        var chart = new google.visualization.PieChart(document.getElementById('chart_F'));
//        chart.draw(data, options);
//      }
//}