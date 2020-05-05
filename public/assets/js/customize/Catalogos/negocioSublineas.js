$(function () {
    //Objetos
    var evento = new Base();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    let tablaPrincipal = new TablaBasica('data-table-unidad-negocios');
    let tablaSublineas = new TablaBasica('data-table-sublineas');

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();
    let vista = 0;

    tablaPrincipal.evento(function () {
        var datos = tablaPrincipal.datosFila(this);
        let datosEnvio = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvio, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta.code == 200) {
                $('#btnEvent').removeClass('hidden');
                vista = 1;
                cargaTablaSublineas(respuesta.data);
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaSublineas(sublienasArea) {
        $('#tablaSublineas').removeClass('hidden');
        $('#tablaUnidades').addClass('hidden');
        tablaSublineas.limpiartabla();
        tablaSublineas.agregarDatosFila([
            sublienasArea.IdArea,
            sublienasArea.Area,
            sublienasArea.Sublineas,
            sublienasArea.Cantidad
        ]);
    }

    tablaSublineas.evento(function () {
        var datos = tablaSublineas.datosFila(this);
        let datosEnvio = {
            IdArea: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvio, '#seccionUnidadesNegocio', function (respuesta) {
            console.log(respuesta);
//            if (respuesta) {
//                vista = 2;
//                cargaTablaInfoSublinea();
//            } else {
//                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
//            }
        });
    });

    function cargaTablaInfoSublinea() {
        $('#tablaInfoSublineas').removeClass('hidden');
        $('#tablaSublineas').addClass('hidden');

    }

    $('#guardarSublinea').on('click', function () {

    });

    $('#btnRegresar').on('click', function () {
        switch (vista) {
            case 1:
                $('#tablaUnidades').removeClass('hidden');
                $('#tablaSublineas').addClass('hidden');
                $('#btnEvent').addClass('hidden');
                vista = 0;
                break;

            case 2:
                $('#tablaSublineas').removeClass('hidden');
                $('#tablaInfoSublineas').addClass('hidden');
                vista = 1;
                break;
        }
    });
});