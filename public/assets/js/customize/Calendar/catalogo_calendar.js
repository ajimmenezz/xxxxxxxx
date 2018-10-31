$(function () {
//Objetos
    var evento = new Base();
    var tabla = new Tabla();
    var select = new Select();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Inicializa funciones de la plantilla
    App.init();
    evento.enviarEvento('EventoCatalogoCalendar/Mostrar', {}, '', function (respuesta) {
        console.log(respuesta);
    });
});