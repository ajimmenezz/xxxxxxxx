//import Dashboard from './Dashboard';

$(function () {

    var evento = new Base();
    var peticion = new Utileria();
    let factory = new FactoryDashboard();
    let dashboards;

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();
    
    $('#page-container').addClass('page-sidebar-minified');

    peticion.enviar('', 'Dashboard_Generico/Mostrar_Graficas', null, function (respuesta) {
        dashboards = {};
        console.log(respuesta);
        $.each(respuesta, function (key, value) {
            $.each(value, function (llave, datos) {
                if (datos.length > 0) {
                    dashboards[llave] = factory.getInstance(llave, datos);
                    dashboards[llave].setComponentes();
                    dashboards[llave].setEvento();
                }
            });
        });
    });

});
