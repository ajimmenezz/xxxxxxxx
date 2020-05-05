$(function () {
    //Objetos
    var evento = new Base();
    var tabla = new Tabla();
    var select = new Select();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-unidad-negocios', null, null, true);

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();


    //Evento que permite actualizar el area
    $('#data-table-unidad-negocios tbody').on('click', 'tr', function () {
        var datos = $('#data-table-unidad-negocios').DataTable().row(this).data();
        let datosEnvio = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvio, '#seccionUnidadesNegocio', function (respuesta) {
            console.log(respuesta);
        });
    });
});