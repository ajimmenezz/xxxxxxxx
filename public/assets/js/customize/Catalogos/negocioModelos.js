$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();

    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');

    let datosEnvioPrincipal = null;
    let vista = 0;

    tablaPrincipal.evento(function () {
        var datos = tablaPrincipal.datosFila(this);
        datosEnvioPrincipal = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoModelosArea/GetModelosArea', datosEnvioPrincipal, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                console.log(respuesta);
                $('#btnEvent').removeClass('hidden');
                $('#nombreSubtitulo').text("Unidad: " + datos[2]);
                vista = 1;
                cargaTablaModelos(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });
    
    function cargaTablaModelos(sublienasArea) {
        $('#subtitulo').removeClass('hidden');
        $('#titulo').addClass('hidden');
        if (typeof sublienasArea.tabla !== 'undefined') {
            $('#tablaModelos').removeClass('hidden');
            $('#tablaUnidades').addClass('hidden');

        } else {
            console.log("...");
        }
    }

    $('#btnRegresar').on('click', function () {
        switch (vista) {
            case 1:
                $('#tablaUnidades').removeClass('hidden');
                $('#titulo').removeClass('hidden');
                $('#tablaModelo').addClass('hidden');
                $('#subtitulo').addClass('hidden');
                $('#btnEvent').addClass('hidden');
                vista = 0;
                break;

            case 2:
                $('#tablaModelo').removeClass('hidden');
                $('#tablaInfoSublineas').addClass('hidden');
                $('#addAreaAtencion').addClass('hidden');
//                tablaInfoSublineas.limpiartabla();
                vista = 1;
                break;
            case 3:
                $('#tablaUnidades').removeClass('hidden');
                $('#tablaInfoModelo').addClass('hidden');
//                tablaInfoSublineas.limpiartabla();
                vista = 1;
                break;
        }
    });

}); 