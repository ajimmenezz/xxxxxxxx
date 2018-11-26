$(function () {
    //Objetos
    var evento = new Base();
    var tabla = new Tabla();
    
    
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    
    //Evento para cerra la session
    evento.cerrarSesion();
    
    //Inicializa funciones de la plantilla
    App.init();
    
    
});