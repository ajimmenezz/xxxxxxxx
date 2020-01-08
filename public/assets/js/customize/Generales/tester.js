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

    $('#btn-solicitud-sd').on('click', function () {
        var key = $('#keySD').val();
        var folio = $('#folioSD').val();
        var data = {key: key, folio: folio};
        evento.enviarEvento('Tester/informacionSD', data, '#paneltester', function (respuesta) {
            $('#respuesta').empty().append(respuesta);
        });
    });

    $('#btn-pdfVuelta').on('click', function () {
        var idVuelta = $('#idVueltaTester').val();
        var servicio = $('#servicioTester').val();
        var folio = $('#folioTester').val();
        var data = {servicio: servicio, idFacturacionOutSourcing: idVuelta, folio: folio};
        evento.enviarEvento('Tester/generarPdfVuelta', data, '#panelCrearPdfVuelta', function (respuesta) {
            if (respuesta === 1) {
                $('#respuestaPdfVuelta').empty().append('Correcto');
            } else {
                $('#respuestaPdfVuelta').empty().append('No se modifico');
            }
        });
    });

    $('#btn-SolicitudesAbiertas').on('click', function () {
        evento.enviarEvento('Tester/concluirSolicitudesAbiertas', {}, '#paneltesterSolicitudesAbiertas', function (respuesta) {
            $('#respuesta').empty().append('Correcto');
        });
    });

    $('#solicitarFolios').on('click', function () {
        evento.enviarEvento('Tester/solicitarFolios',  {}, '#panelComparacionTickets', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
    
    $('#solicitarFoliosAnterior').on('click', function () {
        evento.enviarEvento('Tester/solicitarFoliosAnterior',  {}, '#panelComparacionTickets', function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
            }
        });
    });
});