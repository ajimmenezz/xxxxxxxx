
//Constructor del la clase Tabla
function Charts() {
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart']});
}

Charts.prototype.drawPieChart = function (arreglo) {
    var datos = new Array();
    var suma = 0;
    $.each(arreglo.datos, function (k, v) {
        datos.push([v.Concepto, parseInt(v.Total), parseInt(v.IdGen)]);
        suma += parseInt(v.Total);
    });

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', arreglo.concepto);
    data.addColumn('number', arreglo.total);
    data.addColumn('number', 'Id');
    data.addRows(datos);

    // Set chart options
    var options = {
        title: arreglo.titulo,
        is3D: true,
        titleTextStyle: {
            color: 'black',
            fontSize: 17,
            bold: true
        }
    };
    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById(arreglo.div));
    chart.draw(data, options);

    google.visualization.events.addListener(chart, 'select', function () {
        var idSelected = data.getValue(chart.getSelection()[0].row, 2);
        eval(arreglo.handler + idSelected + ')');
    });
}

Charts.prototype.pintaGraficaPie = function (data = {}) {
    google.charts.setOnLoadCallback(function () {
        Charts.prototype.drawPieChart(data);
    });
}
