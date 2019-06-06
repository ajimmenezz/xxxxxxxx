class GraficaGoogle {

    constructor(nombre, datos = []) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
        this.datos = datos;
    }

    inicilizarGrafica() {
        let _this = this;
        let nombre = this.nombre;

        _this.establecerDatos();
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(function () {            
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows(_this.datos);
            // Set chart options
            var options = {'title': 'How Much Pizza I Ate Last Night'};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById(nombre));
            chart.draw(data, options);
        });

    }

    establecerDatos() {
        let _this = this;
        let temporal = [];
        console.log(_this.datos);
        $.each(_this.datos, function(key, value){
            temporal.push([value[0], parseInt(value[1])]);
        });               
        _this.datos = temporal;        
    }
}
