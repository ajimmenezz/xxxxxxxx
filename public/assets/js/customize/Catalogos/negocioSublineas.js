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
    tabla.generaTablaPersonal('#data-table-sublineas', null, null, true);

    evento.mostrarAyuda('Ayuda_Proyectos');

    App.init();
    let vista = 0;

    $('#data-table-unidad-negocios tbody').on('click', 'tr', function () {
        var datos = $('#data-table-unidad-negocios').DataTable().row(this).data();
        let datosEnvio = {
            IdUnidadNegocio: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvio, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta) {
                $('#btnEvent').removeClass('hidden');
                vista = 1;
                cargaTablaSublineas();
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
        });
    });

    function cargaTablaSublineas() {
        $('#tablaSublineas').removeClass('hidden');
        $('#tablaUnidades').addClass('hidden');
        
    }

    $('#data-table-sublineas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-sublineas').DataTable().row(this).data();
        let datosEnvio = {
            IdArea: datos[0]
        }
        evento.enviarEvento('EventoCatalogoSublineasArea/GetSublienasArea', datosEnvio, '#seccionUnidadesNegocio', function (respuesta) {
            if (respuesta) {
                vista = 2;
                cargaTablaInfoSublinea();
            } else {
                evento.mostrarMensaje('.errorUnidadesNegocio', false, 'No se pude cargar la información, intentalo mas tarde.', 3000);
            }
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