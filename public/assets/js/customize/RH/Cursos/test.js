
$(function () {

    var event = new Base();
    var eventoPagina = new Pagina();
    //Muestra la hora en el sistema
    event.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    event.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();
    
    
    console.debug("Capacitacion test loaded");
    $('#btn-demo-smartresponse').on('click', function (e) {
        var data = {}
        event.enviarEvento('Administracion_Cursos/SmartResponse', data, '#sandbox-result', function(response){
            console.debug("SmartResponse:", response);
            
            var jsonString = JSON.stringify(response, null, 2);
            $('#sandbox-result').empty().append(jsonString);
        })
    });


    $('#btn-demo-smartresponse-error').on('click', function (e) {
        var data = {}
        event.enviarEvento('Administracion_Cursos/SmartResponseError', data, '#sandbox-result', function(response){
            console.error("SmartResponse:", response);

            var jsonString = JSON.stringify(response, null, 2);
            $('#sandbox-result').empty().append(jsonString);
        })
    });
    
});