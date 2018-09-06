//Constructor del la clase Tabla
function Botones() {

}
//Herencia del objeto Base
Botones.prototype = new Base();
Botones.prototype.constructor = Botones;

Botones.prototype.iniciarBotonesGenerales = function () {    
    var servicio = arguments[0];
    var datos = arguments[1];
    var nota = new Nota();    
    var evento = new Base();
    var servicios = new Servicio();
    var data = {servicio: servicio, ticket: datos.Ticket};
    //evento para mostrar los detalles de las descripciones
    $('#detallesServicio').off('click');
    $('#detallesServicio').on('click', function (e) {        
        if ($('#masDetalles').hasClass('hidden')) {
            $('#masDetalles').removeClass('hidden');
            $('#detallesServicio').empty().html('<a>- Detalles</a>');
        } else {
            $('#masDetalles').addClass('hidden');
            $('#detallesServicio').empty().html('<a>+ Detalles</a>');
        }
    });

    //Evento que vuelve a mostrar la lista de los servicios
    $('#btnRegresarSeguimiento').off('click');
    $('#btnRegresarSeguimiento').on('click', function () {        
        $('#seccionSeguimientoServicio').empty().addClass('hidden');
        $('#listaServicio').removeClass('hidden');
    });        

    //Encargado de crear un nuevo servicio
    $('#btnNuevoServicioSeguimiento').off('click');
    $('#btnNuevoServicioSeguimiento').on('click', function () {                
        servicios.nuevoServicio(
                data,
                datos.Ticket,
                datos.IdSolicitud,
                'Seguimiento/Servicio_Nuevo_Modal',
                '#seccion-datos-logistica',
                'Seguimiento/Servicio_Nuevo'
                );
    });

    //Encargado de crear un nuevo servicio
    $('#btnCancelarServicioSeguimiento').off('click');
    $('#btnCancelarServicioSeguimiento').on('click', function () {        
        servicios.cancelarServicio(
                data,
                'Seguimiento/Servicio_Cancelar_Modal',
                '#seccion-datos-logistica',
                'Seguimiento/Servicio_Cancelar'
                );
    });

    $("#btnGeneraPdfServicio").off("click");
    $("#btnGeneraPdfServicio").on("click", function () {                  
        evento.enviarEvento('/Servicio/Servicio_ToPdf', data, '#seccion-datos-seguimiento', function (respuesta) {
            window.open('/' + respuesta.link);
        });
    });

    $("#divNotasServicio").slimScroll({height: '400px'});
    nota.initButtons({servicio: servicio}, 'Seguimiento');    
}