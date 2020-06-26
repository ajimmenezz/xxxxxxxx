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
    //Creando tabla de areas de atencion
    tabla.generaTablaPersonal('#data-table-areasAtencion', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar el area de atencion
    $('#data-table-areasAtencion tbody').on('click', 'tr', function () {
        var datos = $('#data-table-areasAtencion').DataTable().row(this).data();
        if (datos !== undefined) {
            var data = {areaAtencion: datos[0]};
            enviarEvento(data, datos);
        }
    });

    $('#btnAgregarAreaAtencion').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {

        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogoAreasAtencion/MostrarFormularioAreaAtencion', data, '#seccionAreasAtencion', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        $('#listaAreasAtencion').addClass('hidden');
        $('#formularioAreasAtencion').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarAreasAtencion').removeClass('hidden');

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloAreaAtencion').empty().html('Actualizar Área de Atención');
        } else {
            $('#estatus').addClass('hidden');
            $('#tituloAreaAtencion').empty().html('Nueva Área de Atención');
        }
    };

    var llenarCamposFormularioActualizar = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var cliente = respuesta.datos.ids[0].IdCliente;

        $('#inputActualizarNombreAreasAtencion').val(datos[1]);
        $('#selectActualizarClienteAreasAtencion').val(cliente).trigger('change');
        $('#inputActualizarDescripcionAreasAtencion').val(datos[3]);
        $('#inputActualizarClave').val(datos[5]);
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

        $('#btnGuardarAreasAtencion').on('click', function () {

            var nombre = $('#inputActualizarNombreAreasAtencion').val();
            var cliente = $('#selectActualizarClienteAreasAtencion').val();
            var descripcion = $('#inputActualizarDescripcionAreasAtencion').val();
            var clave = $('#inputActualizarClave').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectActualizarEstatusAreasAtencion').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formActualizarAreaAtencion')) {
                var data = {id: id, nombre: nombre, cliente: cliente, descripcion: descripcion, estatus: estatus, operacion: operacion, clave: clave};
                evento.enviarEvento('EventoCatalogoAreasAtencion/Actualizar_AreaAtencion', data, '#seccionArasAtencion', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-areasAtencion');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-areasAtencion', [valor.Id, valor.Nombre, valor.Cliente, valor.Descripcion, activacion, valor.ClaveCorta], true);
                        });
                        $('#listaAreasAtencion').removeClass('hidden');
                        $('#formularioAreasAtencion').addClass('hidden');
                        $('#btnRegresarAreasAtencion').addClass('hidden');
                        evento.mostrarMensaje('.errorAreasAtencion', true, 'Datos Actualizados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorActualizarAreasAtencion', false, 'Ya existe el Nombre del Área de Atención con ese Cliente, por lo que ya no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorActualizarAreasAtencion', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarAreasAtencion').on('click', function () {
            $('#listaAreasAtencion').removeClass('hidden');
            $('#formularioAreasAtencion').addClass('hidden');
            $('#btnRegresarAreasAtencion').addClass('hidden');
        });
    };
});


