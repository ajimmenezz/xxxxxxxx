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

    //Inicializa funciones de la plantilla
    App.init();
    
    $('#btn-solicitud-sd').on('click', function(){
        var key = $('#keySD').val();
        var folio = $('#folioSD').val();
        var data = {key : key, folio : folio };
        evento.enviarEvento('Tester/informacionSD',data,'#paneltester', function(respuesta){
            $('#respuesta').empty().append(respuesta.SD.operation.result.message);
        });
    });

});
