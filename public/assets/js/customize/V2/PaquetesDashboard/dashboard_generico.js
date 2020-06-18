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

    peticion.enviar('initialPage', 'Dashboard_Generico/Mostrar_Graficas', null, function (respuesta) {
        dashboards = {};
        
        $.each(respuesta, function (key, value) {
            $.each(value, function (llave, datos) {
                    dashboards[llave] = factory.getInstance(llave, datos);
                    dashboards[llave].setComponentes();
                    dashboards[llave].setEvento();
            });
        });
    });

});
