$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    
    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    
    //Evento para cerra la session
    evento.cerrarSesion();
    
    //Inicializa funciones de la plantilla
    App.init();
    
    
    //Evento que cambia la lista de reproducción de los videos.
    $('#selectCapacitaciones').on('change', function (e) {
        if ($(this).val() !== '') {          
            var data = {'id' : $(this).val()};
            evento.enviarEvento('EventoCargaVideos/CargaVideos', data, '#panelVideosCapacitacion', function (respuesta) {
                console.log(respuesta);
                $('#divListaReproduccion').empty().append(respuesta.listaVideos);
            });
        } else {
            $('#divListaReproduccion').empty();
        }
    });   
    
});
