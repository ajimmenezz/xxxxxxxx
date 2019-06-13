class GraficaGoogle {

    constructor(nombre, datos = []) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
        this.datos = datos;
        this.google = null;
        this.chart = null;
        this.data = null;
    }

    inicilizarGrafica() {
        let _this = this;
        let nombre = this.nombre;
        _this.google = google;

        _this.establecerDatos();
        // Load the Visualization API and the corechart package.
        _this.google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        _this.google.charts.setOnLoadCallback(function () {            
            // Create the data table.
            _this.data = new _this.google.visualization.DataTable();
            _this.data.addColumn('string', 'Topping');
            _this.data.addColumn('number', 'Slices');
            _this.data.addRows(_this.datos);
            // Set chart options
            var options = {is3D: true};

            // Instantiate and draw our chart, passing in some options.
            _this.chart = new _this.google.visualization.PieChart(document.getElementById(nombre));
            _this.chart.draw(_this.data, options);
        });

    }

    establecerDatos() {
        let _this = this;
        let temporal = [];
        $.each(_this.datos, function(key, value){
            temporal.push([value[0], parseInt(value[1])]);
        });               
        _this.datos = temporal;        
    }
    
    agregarListener(callback){
        let _this = this;
        setTimeout(function(){
            _this.google.visualization.events.addListener(_this.chart, 'select', function(){
            let dato = _this.chart.getSelection();
            if(dato.length > 0){
                callback(_this.data.getValue(dato[0].row, 0));
            }
        });
        }, 1000);
    }
}
