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

    //Creando tabla de departamentos
    tabla.generaTablaPersonal('#data-table-departamentos', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que actualizar al personal
    $('#btnAgregarDepartamento').on('click', function () {
        evento.enviarEvento('EventoCatalogoDepartamento/MostrarFormularioDepartamentos', '', '#seccionDepartamentos', function (respuesta) {
            $('#formularioDepartamento').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaDepartamentos').addClass('hidden');
            select.crearSelect('select');
            $('#tituloDepartamento').empty().html('Nuevo Departamento');
            //Evento que genera un nuevo departamento
            $('#btnNuevoDepartamento').on('click', function () {
                var nombre = $('#inputNombreDepartamento').val();
                var area = $('#selectAreaDepartamento').val();
                var descripcion = $('#inputDescripcionDespartamento').val();
                var activacion;
                var data = {nombre: nombre, area: area, descripcion: descripcion};
                if (evento.validarFormulario('#formDepartamentos')) {
                    evento.enviarEvento('EventoCatalogoDepartamento/Nuevo_Departamento', data, '#seccionDepartamento', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-departamentos');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                $('#formularioDepartamento').addClass('hidden');
                                $('#listaDepartamentos').removeClass('hidden');
                                tabla.agregarFila('#data-table-departamentos', [valor.Id, valor.Nombre, valor.Area, valor.Descripcion, activacion], true);
                            });
                            evento.limpiarFormulario('#formDepartamentos');
                            evento.mostrarMensaje('.errorListaDepartamentos', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorDepartamento', false, 'Ya existe el Nombre de Departamento, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelarDepartamento').on('click', function () {
                $('#formularioDepartamento').empty().addClass('hidden');
                $('#listaDepartamentos').removeClass('hidden');
            });
        });
    });

    //Evento que permite actualizar el departamento
    $('#data-table-departamentos tbody').on('click', 'tr', function () {
        var datos = $('#data-table-departamentos').DataTable().row(this).data();
        var data = {Departamento: datos[0]};
        evento.enviarEvento('EventoCatalogoDepartamento/MostrarFormularioDepartamentos', data, '#seccionDepartamento', function (respuesta) {
            $('#formularioDepartamento').removeClass('hidden').empty().append(respuesta.formulario);
            $('#listaDepartamentos').addClass('hidden');
            $('#nuevoDepartamento').addClass('hidden');
            $('#actualizarDepartamento').removeClass('hidden');
            var area = respuesta.datos.idArea[0].IdArea;
            var activacion;
            select.crearSelect('select');
            $('#tituloDepartamento').empty().html('Actualizar Departamento');
            $('#inputNombreDepartamento').val(datos[1]);
            $('#selectAreaDepartamento').val(area).trigger('change');
            $('#inputDescripcionDespartamento').val(datos[3]);
            $('#btnActualizarDepartamento').on('click', function () {
                if (evento.validarFormulario('#formDepartamentos')) {
                    var data = {id: datos[0], nombre: $('#inputNombreDepartamento').val(), area: $('#selectAreaDepartamento').val(), descripcion: $('#inputDescripcionDespartamento').val(), estatus: $('#selectActualizarEstatus').val()};
                    evento.enviarEvento('EventoCatalogoDepartamento/Actualizar_Departamento', data, '#seccionDepartamentos', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-departamentos');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-departamentos', [valor.Id, valor.Nombre, valor.Area, valor.Descripcion, activacion], true);
                            });
                            $('#formularioDepartamento').addClass('hidden');
                            $('#listaDepartamentos').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaDepartamentos', true, 'Datos Actualizados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorDepartamento', false, 'Ya existe el Nombre de Departamento, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelarActualizarDepartamento').on('click', function () {
                $('#formularioDepartamento').addClass('hidden');
                $('#listaDepartamentos').removeClass('hidden');
            });
        });
    });
});


