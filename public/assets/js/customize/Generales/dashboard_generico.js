$(function () {

    var evento = new Base();
    var peticion = new Utileria();

//    websocket = new Socket();
//
//    //Evento que maneja las peticiones del socket
//    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    peticion.enviar('', 'Dashboard_Generico/Mostrar_Graficas', {prueba: 'algo'}, function (respuesta) {
        console.log(respuesta);
    });

//    var blue = '#348fe2',
//            blueLight = '#5da5e8',
//            blueDark = '#1993E4',
//            aqua = '#49b6d6',
//            aquaLight = '#6dc5de',
//            aquaDark = '#3a92ab',
//            green = '#00acac',
//            greenLight = '#33bdbd',
//            greenDark = '#008a8a',
//            orange = '#f59c1a',
//            orangeLight = '#f7b048',
//            orangeDark = '#c47d15',
//            dark = '#2d353c',
//            grey = '#b6c2c9',
//            purple = '#727cb6',
//            purpleLight = '#8e96c5',
//            purpleDark = '#5b6392',
//            red = '#ff5b57';
//
//
//    var handleInteractiveChart = function () {
//        "use strict";
//        function showTooltip(x, y, contents) {
//            $('<div id="tooltip" class="flot-tooltip">' + contents + '</div>').css({
//                top: y - 45,
//                left: x - 55
//            }).appendTo("body").fadeIn(200);
//        }
//        if ($('#interactive-chart').length !== 0) {
//            var d1 = [[0, 42], [1, 53], [2, 66], [3, 60], [4, 68], [5, 66], [6, 71], [7, 75], [8, 69], [9, 70], [10, 68], [11, 72], [12, 78]];
//            var d2 = [[0, 12], [1, 26], [2, 13], [3, 18], [4, 35], [5, 23], [6, 18], [7, 35], [8, 24], [9, 14], [10, 14], [11, 29], [12, 30]];
//            var d3 = [[0, 20], [1, 45], [2, 38], [3, 14], [4, 89], [5, 75], [6, 1], [7, 39], [8, 12], [9, 6], [10, 5], [11, 32], [12, 30]];
//
//            $.plot($("#interactive-chart"), [
//                {
//                    data: d1,
//                    label: "Cinemex",
//                    color: purple,
//                    lines: {show: true, fill: false, lineWidth: 2},
//                    points: {show: false, radius: 5, fillColor: '#fff'},
//                    shadowSize: 0
//                }, 
//                {
//                    data: d2,
//                    label: 'Siccob',
//                    color: green,
//                    lines: {show: true, fill: false, lineWidth: 2, fillColor: ''},
//                    points: {show: false, radius: 3, fillColor: '#fff'},
//                    shadowSize: 0
//                },
//                {
//                    data: d3,
//                    label: 'A&AT',
//                    color: red,
//                    lines: {show: true, fill: false, lineWidth: 2, fillColor: ''},
//                    points: {show: false, radius: 3, fillColor: '#fff'},
//                    shadowSize: 0
//                }
//            ],
//                    {
//                        xaxis: {tickColor: '#ddd', tickSize: 2},
//                        yaxis: {tickColor: '#ddd', tickSize: 20},
//                        grid: {
//                            hoverable: true,
//                            clickable: true,
//                            tickColor: "#ccc",
//                            borderWidth: 1,
//                            borderColor: '#ddd'
//                        },
//                        legend: {
//                            labelBoxBorderColor: '#ddd',
//                            margin: 0,
//                            noColumns: 1,
//                            show: true
//                        }
//                    }
//            );
//            var previousPoint = null;
//            $("#interactive-chart").bind("plothover", function (event, pos, item) {
//                $("#x").text(pos.x.toFixed(2));
//                $("#y").text(pos.y.toFixed(2));
//                if (item) {
//                    if (previousPoint !== item.dataIndex) {
//                        previousPoint = item.dataIndex;
//                        $("#tooltip").remove();
//                        var y = item.datapoint[1].toFixed(2);
//
//                        var content = item.series.label + " " + y;
//                        showTooltip(item.pageX, item.pageY, content);
//                    }
//                } else {
//                    $("#tooltip").remove();
//                    previousPoint = null;
//                }
//                event.preventDefault();
//            });
//        }
//    };
//    
//    handleInteractiveChart();



});
