//import Dashboard from './Dashboard';

$(function () {

    var evento = new Base();
    var peticion = new Utileria();
    let factory = new FactoryDashboard();
    let dashboards;

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
        dashboards = {};

        $.each(respuesta, function (key, datos) {            
            dashboards[key] = factory.getInstance(key, datos);            
            dashboards[key].setComponentes();
            dashboards[key].setEvento();
        });               
    });

});
