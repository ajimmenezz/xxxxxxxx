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
    //Creando tablas de Fallas Poliza
    tabla.generaTablaPersonal('#data-table-clasificacion-fallas', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-tipos-fallas', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-fallas-equipo', null, null, true, true);
    tabla.generaTablaPersonal('#data-table-fallas-refaccion', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();


    //Clasificacion Fallas

    $('#data-table-clasificacion-fallas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-clasificacion-fallas').DataTable().row(this).data();
        var data = {id: datos[0], nombre: datos[1], descripcion: datos[2]};

        mostrarFormularioClasificacionFallas(data);
    });

    $('#btnAgregarClasificacionFalla').on('click', function () {
        mostrarFormularioClasificacionFallas();
    });

    var mostrarFormularioClasificacionFallas = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioClasificacionFalla', datos, '#seccion-catalogo-fallas-poliza', function (respuesta) {
            iniciarFormularioClasificacionFalla(respuesta, datos);
            cargarEventosFormularioClasificacionFalla(respuesta, datos);
        });
    };

    var iniciarFormularioClasificacionFalla = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-fallas-poliza').addClass('hidden');
        $('#seccionFormulariosFallasPoliza').removeClass('hidden').empty().append(respuesta.formulario);

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioClasificacionFallas(datos);
            $('#tituloClasificacionFalla').empty().html('Actualizar Clasificación de Falla');
            select.crearSelect('select');
        } else {
            $('#estatusClasificacionFalla').addClass('hidden');
            $('#tituloClasificacionFalla').empty().html('Nueva Falla');
        }
    };

    var llenarCamposFormularioClasificacionFallas = function () {
        var datos = arguments[0] || null;

        $('#inputNombreClasificacionFalla').val(datos.nombre);
        $('#inputDescripcionClasificacionFalla').val(datos.descripcion);

    };

    var cargarEventosFormularioClasificacionFalla = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarClasificacionFalla').on('click', function () {
            var nombre = $('#inputNombreClasificacionFalla').val();
            var descripcion = $('#inputDescripcionClasificacionFalla').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusClasificacionFallas').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formClasificacionFalla')) {
                var data = {id: id, nombre: nombre, descripcion: descripcion, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarClasificacionFalla', data, '#panelClasificacionFalla', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-clasificacion-fallas');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-clasificacion-fallas', [valor.Id, valor.Nombre, valor.Descripcion, activacion], true);
                        });
                        ocultarFormulario();
                        $('#btnRegresarListaCatalogoFallas').addClass('hidden');
                        evento.mostrarMensaje('.errorClasificacionFallas', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioClasificacionFalla', false, 'Ya existe el Nombre de la Falla, por lo que no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioClasificacionFalla', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarListaCatalogoFallas').on('click', function () {
            ocultarFormulario();
        });
    };


    //Tipos Fallas

    $('#data-table-tipos-fallas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-tipos-fallas').DataTable().row(this).data();
        var data = {id: datos[0], nombre: datos[1], clasificacion: datos[5], descripcion: datos[2]};

        mostrarFormularioTipoFallas(data);
    });

    $('#btnAgregarTipoFalla').on('click', function () {
        mostrarFormularioTipoFallas();
    });

    var mostrarFormularioTipoFallas = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioTipoFalla', datos, '#seccion-catalogo-fallas-poliza', function (respuesta) {
            iniciarFormularioTipoFalla(respuesta, datos);
            cargarEventosFormularioTipoFalla(respuesta, datos);
        });
    };

    var iniciarFormularioTipoFalla = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;

        $('#seccion-catalogo-fallas-poliza').addClass('hidden');
        $('#seccionFormulariosFallasPoliza').removeClass('hidden').empty().append(respuesta.formulario);
        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioTipoFallas(datos);
            $('#tituloTipoFalla').empty().html('Actualizar Tipo de Falla');
        } else {
            $('#estatusTipoFalla').addClass('hidden');
            $('#tituloTipoFalla').empty().html('Nuevo Tipo de Falla');
        }
    };


    var llenarCamposFormularioTipoFallas = function () {
        var datos = arguments[0] || null;

        select.cambiarOpcion('#selectClasificacionTipoFalla', datos.clasificacion);
        $('#inputNombreTipoFalla').val(datos.nombre);
        $('#inputDescripcionTipoFalla').val(datos.descripcion);

    };

    var cargarEventosFormularioTipoFalla = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarTipoFalla').on('click', function () {
            var clasificacion = $('#selectClasificacionTipoFalla').val();
            var nombre = $('#inputNombreTipoFalla').val();
            var descripcion = $('#inputDescripcionTipoFalla').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusTipoFallas').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formTipoFalla')) {
                var data = {id: id, clasificacion: clasificacion, nombre: nombre, descripcion: descripcion, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarTipoFalla', data, '#panelTipoFalla', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-tipos-fallas');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-tipos-fallas', [valor.Id, valor.Nombre, valor.Clasificacion, valor.Descripcion, activacion, valor.IdClasificacion], true);
                        });
                        ocultarFormulario();
                        $('#btnRegresarListaCatalogoFallas').addClass('hidden');
                        evento.mostrarMensaje('.errorTiposFallas', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioTipoFalla', false, 'Ya existe el Nombre de Tipo de Falla en esa Clasificación, por lo que no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioTipoFalla', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarListaCatalogoFallas').on('click', function () {
            ocultarFormulario();
        });
    };

    //Fallas Equipo

    $('#data-table-fallas-equipo tbody').on('click', 'tr', function () {
        var datos = $('#data-table-fallas-equipo').DataTable().row(this).data();
        var data = {id: datos[0], falla: datos[1]};

        mostrarFormularioFallaEquipo(data);
    });

    $('#btnAgregarFallaEquipo').on('click', function () {
        mostrarFormularioFallaEquipo();
    });

    var mostrarFormularioFallaEquipo = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioFallaEquipo', datos, '#seccion-catalogo-fallas-poliza', function (respuesta) {
            iniciarFormularioFallaEquipo(respuesta, datos);
            cargarEventosFormularioFallaEquipo(respuesta, datos);
        });
    };

    var iniciarFormularioFallaEquipo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        $('#seccion-catalogo-fallas-poliza').addClass('hidden');
        $('#seccionFormulariosFallasPoliza').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioFallaEquipo(datos, respuesta);
            $('#tituloFallaEquipo').empty().html('Actualizar Falla de Equipo');
        } else {
            $('#estatusFallaEquipo').addClass('hidden');
            $('#tituloFallaEquipo').empty().html('Nuevo Falla de Equipo');
        }
    };


    var llenarCamposFormularioFallaEquipo = function () {
        var datos = arguments[0] || null;
        var respuesta = arguments[1] || null;

        select.cambiarOpcion('#selectTiposFallas', respuesta.datos.ids[0].IdTipo);
        select.cambiarOpcion('#selectEquipo', respuesta.datos.ids[0].IdEquipo);
        $('#inputNombreFallaEquipo').val(datos.falla);
    };

    var cargarEventosFormularioFallaEquipo = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#btnGuardarFallaEquipo').on('click', function () {
            var tipoFalla = $('#selectTiposFallas').val();
            var equipo = $('#selectEquipo').val();
            var falla = $('#inputNombreFallaEquipo').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusFallaEquipo').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formFallaEquipo')) {
                var data = {id: id, tipoFalla: tipoFalla, equipo: equipo, falla: falla, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarFallaEquipo', data, '#panelFallaEquipo', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-fallas-equipo');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-fallas-equipo', [valor.Id, valor.Nombre, valor.NombreTipoFalla, valor.NombreEquipo, activacion], true);
                        });
                        ocultarFormulario();
                        $('#btnRegresarListaCatalogoFallas').addClass('hidden');
                        evento.mostrarMensaje('.errorFallasEquipo', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioFallaEquipo', false, 'Ya existe el Nombre de la Falla para ese Equipo, por lo que no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioFallaEquipo', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarListaCatalogoFallas').on('click', function () {
            ocultarFormulario();
        });
    };

    //Fallas Refaccion

    $('#data-table-fallas-refaccion tbody').on('click', 'tr', function () {
        var datos = $('#data-table-fallas-refaccion').DataTable().row(this).data();
        var data = {id: datos[0], falla: datos[1]};

        mostrarFormularioFallaRefaccion(data);
    });

    $('#btnAgregarFallaRefaccion').on('click', function () {
        mostrarFormularioFallaRefaccion();
    });

    var mostrarFormularioFallaRefaccion = function () {
        var datos = arguments[0] || '';

        evento.enviarEvento('EventoCatalogos/MostrarFormularioFallaRefaccion', datos, '#seccion-catalogo-fallas-poliza', function (respuesta) {
            iniciarFormularioFallaRefaccion(respuesta, datos);
            cargarEventosFormularioFallaRefaccion(respuesta, datos);
        });
    };

    var iniciarFormularioFallaRefaccion = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        $('#seccion-catalogo-fallas-poliza').addClass('hidden');
        $('#seccionFormulariosFallasPoliza').removeClass('hidden').empty().append(respuesta.formulario);

        select.crearSelect('select');

        if (respuesta.datos.flag !== null) {
            llenarCamposFormularioFallaRefaccion(datos, respuesta);
            $('#tituloFallaRefaccion').empty().html('Actualizar Falla por Refacción');
        } else {
            $('#estatusFallaRefaccion').addClass('hidden');
            $('#tituloFallaRefaccion').empty().html('Nuevo Falla por Refacción');
        }
    };

    var llenarCamposFormularioFallaRefaccion = function () {
        var datos = arguments[0] || null;
        var respuesta = arguments[1] || null;
        var objetoNuevoComponentesEquipo = {};

        $('#selectRefaccion').removeAttr('disabled');
        select.cambiarOpcion('#selectTiposFallas', respuesta.datos.ids[0].IdTipo);
        select.cambiarOpcion('#selectEquipo', respuesta.datos.ids[0].IdEquipo);
        $.each(respuesta.datos.componentesEquipos, function (key, valor) {
            objetoNuevoComponentesEquipo[key] = {Id: valor.Id, Nombre: valor.Nombre, IdModelo: valor.IdModelo};
        });
        select.setOpcionesSelect('#selectRefaccion', objetoNuevoComponentesEquipo, $('#selectEquipo').val(), 'IdModelo');
        select.cambiarOpcion('#selectRefaccion', respuesta.datos.ids[0].IdRefaccion);
        $('#inputNombreFallaRefaccion').val(datos.falla);
    };

    var cargarEventosFormularioFallaRefaccion = function () {
        var respuesta = arguments[0] || null;
        var datos = arguments[1] || null;
        var id = '';
        var operacion = '1'
        var numeroSelectEquipo = '';

        if (respuesta.datos.flag !== null) {
            id = datos.id;
            operacion = '2';
        }

        $('#selectEquipo').on('change', function () {
            var objetoNuevoComponentesEquipo = {};

            $.each(respuesta.datos.componentesEquipos, function (key, valor) {
                objetoNuevoComponentesEquipo[key] = {Id: valor.Id, Nombre: valor.Nombre, IdModelo: valor.IdModelo};
            });

            select.setOpcionesSelect('#selectRefaccion', objetoNuevoComponentesEquipo, $('#selectEquipo').val(), 'IdModelo');

            $("#selectRefaccion option").each(function ()
            {
                numeroSelectEquipo = $(this).attr('value');
            });

            if (numeroSelectEquipo !== '') {
                $('#selectRefaccion').removeAttr('disabled');
            } else {
                $('#selectRefaccion').attr('disabled', 'disabled');
            }
        });

        $('#btnGuardarFallaRefaccion').on('click', function () {
            var tipoFalla = $('#selectTiposFallas').val();
            var equipo = $('#selectEquipo').val();
            var refaccion = $('#selectRefaccion').val();
            var falla = $('#inputNombreFallaRefaccion').val();
            var activacion;

            if (operacion === '2') {
                var estatus = $('#selectEstatusFallaRefaccion').val();
            } else {
                var estatus = '';
            }
            if (evento.validarFormulario('#formFallaRefaccion')) {
                var data = {id: id, tipoFalla: tipoFalla, equipo: equipo, refaccion: refaccion, falla: falla, estatus: estatus, operacion: operacion};
                evento.enviarEvento('EventoCatalogos/GuardarFallaRefaccion', data, '#panelFallaRefaccion', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-fallas-refaccion');
                        $.each(respuesta, function (key, valor) {
                            if (valor.Flag === '1') {
                                activacion = 'Activo';
                            } else {
                                activacion = 'Inactivo';
                            }
                            tabla.agregarFila('#data-table-fallas-refaccion', [valor.Id, valor.Nombre, valor.NombreEquipo, valor.NombreRefaccion, valor.NombreTipoFalla, activacion], true);
                        });
                        ocultarFormulario();
                        $('#btnRegresarListaCatalogoFallas').addClass('hidden');
                        evento.mostrarMensaje('.errorFallasRefaccion', true, 'Datos Guardados correctamente', 3000);
                    } else if (respuesta === 'Repetido') {
                        evento.mostrarMensaje('.errorFormularioFallaRefaccion', false, 'Ya existe el Nombre de la Falla para esa Refacción, por lo que no puedes repetirlo.', 5000);
                    } else {
                        evento.mostrarMensaje('.errorFormularioFallaRefaccion', false, 'No se pudo insertar los datos, inténtelo de nuevo.', 5000);
                    }
                });
            }
        });

        $('#btnRegresarListaCatalogoFallas').on('click', function () {
            ocultarFormulario();
        });
    };

    var ocultarFormulario = function () {
        $('#seccion-catalogo-fallas-poliza').removeClass('hidden');
        $('#seccionFormulariosFallasPoliza').addClass('hidden');
    }
});