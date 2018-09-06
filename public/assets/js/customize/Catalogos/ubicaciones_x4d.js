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
    //Creando tabla de sucursales
    tabla.generaTablaPersonal('#data-table-ubicaciones', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar la solucion equipo
    $('#data-table-ubicaciones tbody').on('click', 'tr', function () {
        var datos = $('#data-table-ubicaciones').DataTable().row(this).data();
        if (datos !== undefined) {
            var data = {id: datos[0]};
            enviarEvento(data, datos);
        }
    });

    $('#btnAgregarUbicacion').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {
        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogos/MostrarFormularioUbicacion', data, '#seccionUbicaciones', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaUbicaciones').addClass('hidden');
        $('#formularioUbicacion').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarUbicaciones').removeClass('hidden');

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloUbicacion').empty().html('Actualizar Ubicacion');
        } else {
            $('#estatusUbicacion').addClass('hidden');
            $('#tituloUbicacion').empty().html('Nueva Ubicacion');
        }
    };

    var llenarCamposFormularioActualizar = function () {
        var datos = arguments[1] || null;
        var nombre = datos[1];

        $('#inputNombreUbicacion').val(nombre);
    };

    var cargarEventosFormulario = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.flag !== null) {
            id = datos[0];
            operacion = '2';
        }

        $('#btnGuardarUbicacion').on('click', function () {
            var nombre = $('#inputNombreUbicacion').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusUbicacion').val();
            } else {
                var estatus = '';
            }

            if (evento.validarFormulario('#formUbicacion')) {
                var data = {id: id, nombre: nombre, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarUbicacion', data, '#seccionUbicaciones', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-ubicaciones');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-ubicaciones', [valor.Id, valor.Nombre, activacion], true);
                        });
                        $('#listaUbicaciones').removeClass('hidden');
                        $('#formularioUbicacion').addClass('hidden');
                        $('#btnRegresarUbicaciones').addClass('hidden');
                        evento.mostrarMensaje('.errorUbicaciones', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorUbicacion', false, 'Ya existe el Nombre de la Ubicacion por lo que ya no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorUbicacion', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }

        });

        $('#btnRegresarUbicaciones').on('click', function () {
            $('#listaUbicaciones').removeClass('hidden');
            $('#formularioUbicacion').addClass('hidden');
            $('#btnRegresarUbicaciones').addClass('hidden');
        });
    };
});