$(function () {

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

    $("#btnBuscarContratos").off("click");
    $("#btnBuscarContratos").on("click", function () {
        $("#divResult").hide();
        var id = $.trim($("#txtID").val());
        if (id !== '') {
            evento.enviarEvento('/Cimos/Reportes/SearchByID', {id: id}, '#panelContratosCIMOS', function (respuesta) {
                $("#divContratos").empty();
                $.each(respuesta['contratos'], function (k, v) {
                    $("#divContratos").append("<p><a target='_blank' href='" + v + "'>Link de Contrato</a></p>")
                });
                $("#divSuscripciones").empty();
                $.each(respuesta['suscripciones'], function (k, v) {
                    $("#divSuscripciones").append("<p><a target='_blank' href='" + v.Link + "'>" + v.Name + "</a></p>")
                });
                $("#divResult").show();
            });
        } else {
            evento.mostrarMensaje(".divError", false, "El ID de cliente es necesario para la búsqueda de contratos", 4000);
        }
    });


    function makeContractPdf() {
        var datos = arguments[0];
        evento.enviarEvento('Reportes/MakeContractPdf', {data: datos}, '#panelReporte', function (respuesta) {
            window.open(respuesta, '_blank');
        });
    }
});
