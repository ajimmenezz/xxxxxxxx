$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    
    //Evento para cerra la session
    evento.cerrarSesion();
    
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    
    //Inicializa funciones de la plantilla
    App.init();
    
    
});