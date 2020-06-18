$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    
    //Evento para cerra la session
    evento.cerrarSesion();
    
    //Inicializa funciones de la plantilla
    App.init();
    
    //Geneando la tabla de tareas asignadas
    tabla.generaTablaPersonal('#data-table-sevicios-asignados',null,null,true);
    
    //Geneando la tabla de tareas asignadas
    tabla.generaTablaPersonal('#data-table-sevicios-enproceso',null,null,true);
    
    
});


