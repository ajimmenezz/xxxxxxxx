$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();


    //Crea select multiple permiso
    select.crearSelectMultiple('#selectActualizarPermisos', 'Define los Permisos');

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de perfiles
    tabla.generaTablaPersonal('#data-table-perfiles', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar el perfil
    $('#data-table-perfiles tbody').on('click', 'tr', function () {
        var datos = $('#data-table-perfiles').DataTable().row(this).data();
        var data = {Perfil: datos[0]};
        enviarEvento(data, datos);
    });


    $('#btnAgregarPerfil').on('click', function () {
        enviarEvento();
    });

    var enviarEvento = function () {
        var data = arguments[0] || '';
        var datos = arguments[1] || null;

        evento.enviarEvento('EventoCatalogo/MostrarPerfilActualizar', data, '#seccionPerfiles', function (respuesta) {
            iniciarElementosFormulario(respuesta, datos);
            cargarEventosFormulario(respuesta, datos);
        });
    };

    var iniciarElementosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#listaPerfiles').addClass('hidden');
        $('#formularioPerfil').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarPerfiles').removeClass('hidden');

        select.crearSelect('#selectActualizarArea');
        select.crearSelect('#selectActualizarDepartamento');
        select.crearSelect('select');

        //evento para mostrar eventos de los selectPais, selectEstado, selectMunucipio
        eventosSelectLocalidades(respuesta);

        if (respuesta.datos.idArea !== null) {
            llenarCamposFormularioActualizar(respuesta, datos);
            $('#tituloPerfil').empty().html('Actualizar Perfil');
        } else {
            $('#estatus').addClass('hidden');
            $('#tituloPerfil').empty().html('Nueva Perfil');
        }


    };

    var eventosSelectLocalidades = function () {

        var respuesta = arguments[0];

        select.setOpcionesSelect('#selectActualizarDepartamento', respuesta.datos.departamentos, $('#selectActualizarArea').val(), 'IdArea');
        $('#selectActualizarArea').on('change', function () {
            select.setOpcionesSelect('#selectActualizarDepartamento', respuesta.datos.departamentos, $('#selectActualizarArea').val(), 'IdArea');
            if ($('#selectActualizarArea').val() !== '') {
                $('#selectActualizarDepartamento').removeAttr('disabled');
            } else {
                $('#selectActualizarDepartamento').attr('disabled', 'disabled');
            }
        });
    };

    var llenarCamposFormularioActualizar = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var area = respuesta.datos.idArea[0].IdArea;
        var departamento = respuesta.datos.idArea[0].IdDepartamento;
        var permiso = (respuesta.datos.permiso[0].Permisos);
        var arrayPermiso = JSON.parse("[" + permiso + "]");

        $('#inputActualizarNombrePerfil').val(datos[1]);
        $('#selectActualizarArea').val(area).trigger('change');
        $('#selectActualizarDepartamento').val(departamento).trigger('change');
        $('#selectActualizarPermisos').val(arrayPermiso).trigger('change');
        $('#inputActualizarDescripcionPerfil').val(datos[5]);
        $('#selectActualizarNivel').val(datos[6]).trigger('change');
        $('#inputActualizarClave').val(datos[7]);
        $('#inputActualizarCantidad').val(datos[8]);
        $('#btnModalConfirmar').empty().append('Guardar');
        $('#btnModalConfirmar').off('click');

    };

    var cargarEventosFormulario = function () {

        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.idArea !== null) {
            id = datos[0];
            operacion = '2';
        }

        $('#btnGuardarPerfil').on('click', function () {

            var nombre = $('#inputActualizarNombrePerfil').val();
            var departamento = $('#selectActualizarDepartamento').val();
            var permisos = $('#selectActualizarPermisos').val();
            var descripcion = $('#inputActualizarDescripcionPerfil').val();
            var nivel = $('#selectActualizarNivel').val();
            var clave = $('#inputActualizarClave').val();
            var cantidad = $('#inputActualizarCantidad').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectActualizarEstatus').val();
            } else {
                var estatus = '';
            }

            if (evento.validarFormulario('#formActualizarPerfiles')) {
                if (cantidad > 0) {
                    var data = {id: id, nombre: nombre, departamento: departamento, permisos: permisos, descripcion: descripcion, clave: clave, cantidad: cantidad, nivel: nivel, estatus: estatus, operacion: operacion};
                    evento.enviarEvento('EventoCatalogo/Actualizar_Perfil', data, '#seccionPerfiles', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-perfiles');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-perfiles', [valor.Id, valor.Nombre, valor.Area, valor.Departamento, valor.Permisos, valor.Descripcion, valor.Nivel, valor.Clave, valor.Cantidad, activacion], true);
                            });
                            $('#listaPerfiles').removeClass('hidden');
                            $('#formularioPerfil').addClass('hidden');
                            $('#btnRegresarPerfiles').addClass('hidden');
                            evento.mostrarMensaje('.errorPerfiles', true, 'Datos Actualizados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorActualizarPerfil', false, 'Ya existe el Nombre del Perfil, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('.errorActualizarPerfil', false, 'Debes llenar el campo cantidad con un numero positivo', 3000);
                }
            }
        });
        
        $("#checkboxPerfilesActualizar").click(function () {
            select.seleccionarTodos(this, $('#selectActualizarPermisos'));
        });

        $('#btnRegresarPerfiles').on('click', function () {
            $('#listaPerfiles').removeClass('hidden');
            $('#formularioPerfil').addClass('hidden');
            $('#btnRegresarPerfiles').addClass('hidden');
        });
    };
});