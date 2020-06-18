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
    //Creando tabla de regiones cliente
    tabla.generaTablaPersonal('#data-table-regiones-cliente', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar la region de cliente
    $('#data-table-regiones-cliente tbody').on('click', 'tr', function () {
        var datos = $('#data-table-regiones-cliente').DataTable().row(this).data();
        var data = {regionCliente: datos[0]};
        
        mostrarFormulario(data, datos);
    });

    $('#btnAgregarRegionCliente').on('click', function () {
        mostrarFormulario();
    });

    var mostrarFormulario = function () {

        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogoRegionesCliente/MostrarFormularioRegionesCliente', data, '#seccionRegionesClente', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        
        $('#listaRegionesCliente').addClass('hidden');
        $('#formularioRegionesCliente').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarRegionesCliente').removeClass('hidden');

        select.crearSelect('select');

        if (respuesta.datos.operacion === 'Actualizar') {
            llenarCamposFormularioActualizar(datos);
            $('#tituloRegionCliente').empty().html('Actualizar Región Cliente');
        } else {
            $('#estatusRegionCliente').addClass('hidden');
            $('#tituloRegionCliente').empty().html('Nueva Región Cliente');
        }
    };

    var llenarCamposFormularioActualizar = function () {
        var datos = arguments[0] || null;

        $('#selectClienteRegion').val(datos[7]).trigger('change');
        $('#inputNombreRegion').val(datos[2]);
        $('#inputResposableCliente').val(datos[3]);
        $('#inputEmailResponsable').val(datos[4]);
        $('#selectResposableInterno').val(datos[8]).trigger('change');
    };

    var cargarEventosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.operacion === 'Actualizar') {
            id = datos[0];
            operacion = '2';
        }

        $('#btnGuardarRegionCliente').on('click', function () {
            var cliente = $('#selectClienteRegion').val();
            var nombre = $('#inputNombreRegion').val();
            var responsableCliente = $('#inputResposableCliente').val();
            var emailResposableCliente = $('#inputEmailResponsable').val();
            var responsableInterno = $('#selectResposableInterno').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusRegionCliente').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formRegionCliente')) {
                var data = {id: id, nombre: nombre, cliente: cliente, responsableCliente: responsableCliente, emailResposableCliente: emailResposableCliente, responsableInterno: responsableInterno, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogoRegionesCliente/GuardarRegionCliente', data, '#seccionRegionesClente', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-regiones-cliente');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-regiones-cliente', [valor.Id, valor.Cliente, valor.Nombre, valor.ResponsableCliente, valor.Email, valor.ResponsableInterno, activacion, valor.IdCliente, valor.IdResponsableInterno], true);
                        });
                        $('#listaRegionesCliente').removeClass('hidden');
                        $('#formularioRegionesCliente').addClass('hidden');
                        $('#btnRegresarRegionesCliente').addClass('hidden');
                        evento.mostrarMensaje('.errorRegionesCliente', true, 'Datos Actualizados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorRegionCliente', false, 'Ya existe el Nombre de la Región de Cliente, por lo que ya no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorRegionCliente', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarRegionesCliente').on('click', function () {
            $('#listaRegionesCliente').removeClass('hidden');
            $('#formularioRegionesCliente').addClass('hidden');
            $(this).addClass('hidden');
        });
    };
});