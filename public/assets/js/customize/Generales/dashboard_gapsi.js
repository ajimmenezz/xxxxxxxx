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
    tabla.generaTablaPersonal('#data-table-proyectos', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipo-proyectos', null, null, true, true);

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

var chart, data;
function setGraph(){
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(setGraphDashboard);
}

function setGraphDashboard() {
    data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', '');
    data.addRows([
        ['Mushrooms', 3],
        ['Onions', 1],
        ['Olives', 1],
        ['Zucchini', 1],
        ['Pepperoni', 2],
        ['a', 3],
        ['b', 1],
        ['c', 1],
        ['d', 1],
        ['e', 2]
    ]);

    var options = {
        hAxis: {
            title: 'Tipo de Proyecto'
        },
        vAxis: {
            title: 'Proyectos'
        },
        legend: {position: 'none'}
    };
    chart = new google.visualization.ColumnChart(
        document.getElementById('graphDashboard')
    );
    google.visualization.events.addListener(chart, 'select', selectGraphProyect);
    chart.draw(data, options);

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

function selectGraphProyect() {
    var selectedItem = chart.getSelection()[0];
    var value = data.getValue(selectedItem.row, 0);
    //alert('The user selected ' + value);
    console.log(data.getValue(selectedItem.row, 0));
}

function selectTypeProyects(){
    $('#data-table-tipo-proyectos tbody').on('click', 'tr', function () {
        var informacionPermisoAusencia = $('#data-table-tipo-proyectos').DataTable().row(this).data();
        console.log(informacionPermisoAusencia);
    });
}

function selectProyects(){
    $('#data-table-proyectos tbody').on('click', 'tr', function () {
        var informacionPermisoAusencia = $('#data-table-proyectos').DataTable().row(this).data();
        console.log(informacionPermisoAusencia);
    });
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