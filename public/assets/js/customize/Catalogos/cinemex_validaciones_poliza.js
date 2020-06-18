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
    tabla.generaTablaPersonal('#data-table-cinemex-validaciones', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar
    $('#data-table-cinemex-validaciones tbody').on('click', 'tr', function () {
        var datos = $('#data-table-cinemex-validaciones').DataTable().row(this).data();
        if (datos !== undefined) {
            var data = {id: datos[0]};
            enviarEvento(data, datos);
        }
    });

    $('#btnAgregarCinemexValidacion').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {
        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogos/MostrarFormularioCinemexValidacion', data, '#seccionCinemexValidaciones', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaCinemexValidaciones').addClass('hidden');
        $('#formularioCinemexValidacion').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarCinemexValidaciones').removeClass('hidden');

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloCinemexValidacion').empty().html('Actualizar Personal');
        } else {
            $('#estatusCinemexValidacion').addClass('hidden');
            $('#tituloCinemexValidacion').empty().html('Nuevo Personal');
        }
    };

    var llenarCamposFormularioActualizar = function () {
        var datos = arguments[1] || null;
        var nombre = datos[1];
        var correo = datos[2];

        $('#inputNombreCinemexValidacion').val(nombre);
        $('#inputCorreoCinemexValidacion').val(correo);
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

        $('#btnGuardarCinemexValidacion').on('click', function () {
            var nombre = $('#inputNombreCinemexValidacion').val();
            var correo = $('#inputCorreoCinemexValidacion').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusCinemexValidacion').val();
            } else {
                var estatus = '';
            }

            if (evento.validarFormulario('#formCinemexValidacion')) {
                var data = {id: id, nombre: nombre, correo: correo, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarCinemexValidacion', data, '#seccionCinemexValidaciones', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-cinemex-validaciones');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-cinemex-validaciones', [valor.Id, valor.Nombre, valor.Correo, activacion], true);
                        });
                        $('#listaCinemexValidaciones').removeClass('hidden');
                        $('#formularioCinemexValidacion').addClass('hidden');
                        $('#btnRegresarCinemexValidacion').addClass('hidden');
                        evento.mostrarMensaje('.errorCinemexValidaciones', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorCinemexValidacion', false, 'Ya existe el Nombre por lo que ya no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorCinemexValidacion', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }

        });

        $('#btnRegresarCinemexValidaciones').on('click', function () {
            $('#listaCinemexValidaciones').removeClass('hidden');
            $('#formularioCinemexValidacion').addClass('hidden');
            $('#btnRegresarCinemexValidaciones').addClass('hidden');
        });
    };
});