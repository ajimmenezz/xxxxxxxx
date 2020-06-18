$(function () {
//Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
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

    //Creando tabla de sistemas especiales
    tabla.generaTablaPersonal('#table-dispositivos', null, null, true);    

    //Evento que genera un nuevo concepto de fondo fijo
    $('#btnAddConcepto').off('click');
    $('#btnAddConcepto').on('click', function () {
        cargaFormularioAgregarConcepto(0);
    });

    $('#table-dispositivos tbody').on('click', 'tr', function () {
        var datos = $('#table-dispositivos').DataTable().row(this).data();
        cargaInformacionDispositivo(datos[0], this);
    });

    function cargaInformacionDispositivo() {
        var datos = {
            'imei': arguments[0] || 0
        }
        var _fila = arguments[1] || '';
        evento.enviarEvento('Seguimiento/DetallesDispositivo', datos, '#panelDispositivos', function (respuesta) {
            $("#divDetallesDispositivo").empty().append(respuesta.html);
            evento.cambiarDiv("#divDispositivos", "#divDetallesDispositivo");                                    
        });

    }

    function initBtnDeleteAlternativas() {
        $(".btnDeleteAlternativas").off("click");
        $(".btnDeleteAlternativas").on("click", function () {
            tabla.eliminarFila("#table-alternativas", $(this).closest("tr"));
        });
    }
});


