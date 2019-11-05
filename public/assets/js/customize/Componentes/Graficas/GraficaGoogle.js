class GraficaGoogle {

    /*
     * nombre: div donde mostrara la grafica
     * datos: informacion a mostrar en la grafica
     * grafica: tipo de grafica Pie, Line, Column, etc
     * dataArray: false si la informacion es del estilo ['Work', 0], true si es compleja ["Copper", 8.94, "#b87333"] o incluye encabezado
     * slices: valor de la linea
     */
    constructor(nombre, datos = [], grafica = null, dataArray = false, slices = null) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
        this.datos = datos;
        this.google = null;
        this.chart = null;
        this.data = null;
        this.tipo = grafica;
        this.slices = slices;
        this.complejidad = dataArray;
    }

    inicilizarGrafica(opciones = null) {
        let _this = this;
        let nombre = this.nombre;
        let tipoGrafica = this.tipo
        let informacion = this.datos;
        let esCompleja = this.complejidad;

        if (tipoGrafica === null) {
            tipoGrafica = 'PieChart';
        }

        _this.google = google;

        _this.establecerDatos();
        // Load the Visualization API and the corechart package.
        _this.google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        _this.google.charts.setOnLoadCallback(function () {
            // Create the data table.

            if (esCompleja) {
                _this.data = new _this.google.visualization.arrayToDataTable(informacion);
            } else {
                _this.data = new _this.google.visualization.DataTable();
                _this.data.addColumn('string', 'Topping');
                _this.data.addColumn('number', _this.slices);
                _this.data.addRows(_this.datos);
            }
            // Set chart options
            if (opciones !== null) {
                var options = opciones;
            } else {
                var options = {
                    is3D: true
                };
            }

            // Instantiate and draw our chart, passing in some options.
            _this.chart = new _this.google.visualization[tipoGrafica](document.getElementById(nombre));
            _this.chart.draw(_this.data, options);
        });

    }

    establecerDatos() {
        let _this = this;
        let temporal = [];
        $.each(_this.datos, function (key, value) {
            temporal.push([value[0], parseInt(value[1])]);
        });
        _this.datos = temporal;
    }

    agregarListener(callback) {
        let _this = this;
        try {
            setTimeout(function () {
                _this.google.visualization.events.addListener(_this.chart, 'select', function () {
                    let dato = _this.chart.getSelection();
                    if (dato.length > 0) {
                        callback(_this.data.getValue(dato[0].row, 0));
                    }
                });

            }, 1000);
        } catch (e) {
            console.log('error de grafica');
            _this.agregarListener(callback);
        }
    }
}
