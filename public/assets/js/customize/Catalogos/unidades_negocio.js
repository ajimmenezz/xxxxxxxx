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

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-unidad-negocios', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarUnidadNegocio').on('click', function () {
        evento.enviarEvento('EventoCatalogoUnidadNegocio/MostrarFormularioUnidadNegocio', '', '#seccionUnidadesNegocio', function (respuesta) {
            $('#listaUnidadesNegocio').addClass('hidden');
            $('#formularioUnidadesNegocio').removeClass('hidden').empty().append(respuesta.formulario);
            //Evento que genera un nueva area
            $('#btnNuevaUnidadNegocio').on('click', function () {
                var nombre = $('#inputNombreUnidadNegocio').val();
                var activacion;
                var data = {nombre: nombre};
                if (nombre !== '') {
                    evento.enviarEvento('EventoCatalogoUnidadNegocio/Nueva_Unidad_Negocio', data, '#seccionUnidadesNegocio', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-unidad-negocios');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-unidad-negocios', [valor.Id, valor.Nombre, activacion]);
                            });
                            $('#formularioUnidadesNegocio').addClass('hidden');
                            $('#listaUnidadesNegocio').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaUnidadesNegocio', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorListaUnidadesNegocio', false, 'Ya existe la unidad de negocio, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorUnidadNegocio', false, 'Falta el campo nombre.', 3000);
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioUnidadesNegocio').empty().addClass('hidden');
                $('#listaUnidadesNegocio').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar el area
    $('#data-table-unidad-negocios tbody').on('click', 'tr', function () {
        var datos = $('#data-table-unidad-negocios').DataTable().row(this).data();
        let datosEnviar = {
            idUnidadNegocio: datos[0]
        }
    });
});


