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
    tabla.generaTablaPersonal('#data-table-soluciones-equipo', null, null, true, true);
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar la solucion equipo
    $('#data-table-soluciones-equipo tbody').on('click', 'tr', function () {
        var datos = $('#data-table-soluciones-equipo').DataTable().row(this).data();
        if (datos !== undefined) {
            var data = {id: datos[0]};
            enviarEvento(data, datos);
        }
    });

    $('#btnAgregarSolucionEquipo').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {
        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogos/MostrarFormularioSolucionesEquipo', data, '#seccionSolucionesEquipo', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaSolicionesEquipo').addClass('hidden');
        $('#formularioSolcionEquipo').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarSolucionesEquipo').removeClass('hidden');

        select.crearSelect('select');

        if (respuesta.datos.idEquipo !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloSolucionEquipo').empty().html('Actualizar Solución de Equipo');
        } else {
            $('#estatusSolucionesEquipo').addClass('hidden');
            $('#tituloSolucionEquipo').empty().html('Nueva Solución de Equipo');
        }
    };

    var llenarCamposFormularioActualizar = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var equipo = respuesta.datos.idEquipo[0].IdModelo;
        var nombre = datos[1];
        var descripcion = datos[3];

        select.cambiarOpcion('#selectEquipoSolucionesEquipo', equipo);
        $('#inputNombreSolucionesEquipo').val(nombre);
        $('#inputDescripcionSolucionesEquipo').val(descripcion);
    };

    var cargarEventosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.idEquipo !== null) {
            id = datos[0];
            operacion = '2';
        }

        $('#btnGuardarSolucionEquipo').on('click', function () {
            var equipo = $('#selectEquipoSolucionesEquipo').val();
            var nombre = $('#inputNombreSolucionesEquipo').val();
            var descripcion = $('#inputDescripcionSolucionesEquipo').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusSolucionesEquipo').val();
            } else {
                var estatus = '';
            }

            if (equipo !== '') {
                if (nombre !== '') {
                    var data = {id: id, nombre: nombre, equipo: equipo, descripcion: descripcion, estatus: estatus, operacion: operacion};
                    evento.enviarEvento('EventoCatalogos/GuardarSolucionEquipo', data, '#seccionSolucionesEquipo', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-soluciones-equipo');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-soluciones-equipo', [valor.Id, valor.Nombre, valor.Equipo, valor.Descripcion, activacion], true);
                            });
                            $('#listaSolicionesEquipo').removeClass('hidden');
                            $('#formularioSolcionEquipo').addClass('hidden');
                            $('#btnRegresarSolucionesEquipo').addClass('hidden');
                            evento.mostrarMensaje('.errorSolucionesEquipo', true, 'Datos Guardados correctamente', 3000);
                        } else if (respuesta === 'Repetido') {
                            evento.mostrarMensaje('.errorSolucionEquipo', false, 'Ya existe el Nombre de Solución con ese Equipo, por lo que ya no puedes repetirlo.', 5000);
                        } else {
                            evento.mostrarMensaje('.errorSolucionEquipo', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorSolucionEquipo', false, 'Debe llenar el campo Nombre.', 5000);
                }
            } else {
                evento.mostrarMensaje('.errorSolucionEquipo', false, 'Debe seleccionar el campo Equipo.', 5000);
            }
        });

        $('#btnRegresarSolucionesEquipo').on('click', function () {
            $('#listaSolicionesEquipo').removeClass('hidden');
            $('#formularioSolcionEquipo').addClass('hidden');
            $('#btnRegresarSolucionesEquipo').addClass('hidden');
        });
    };
});