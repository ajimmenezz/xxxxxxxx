$(function () {
   //Objetos
    evento = new Base();
    websocket = new Socket();
    charts = new Charts();
    tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    tabla.generaTablaPersonal('#data-table-proyectos', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipo-proyectos', null, null, false, false);

    //Inicializa funciones de la plantilla
    App.init();
    
});