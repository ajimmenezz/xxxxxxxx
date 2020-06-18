$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();

    //Crea select multiple permiso
    select.crearSelectMultiple('#selectActualizarSucursalesLogistica', 'Define los Permisos');

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de perfiles
    tabla.generaTablaPersonal('#data-table-regiones', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que genera un nueva region de logistica
    $('#btnNuevaRegion').on('click', function () {
        var nombre = $('#inputNombreRegion').val();
        var descripcion = $('#inputDescripcionRegion').val();
        var sucursales = $('#selectSucursalesRegion').val();
        var activacion;
        if (evento.validarFormulario('#formRegionesLogistica')) {
            var data = {nombre: nombre, descripcion: descripcion, sucursales: sucursales};
            evento.enviarEvento('EventoLogistica/Nueva_Region', data, '#seccionRegionesLogistica', function (respuesta) {
                if (respuesta instanceof Array) {
                    tabla.limpiarTabla('#data-table-regiones');
                    $.each(respuesta, function (key, valor) {
                        if (valor.Flag === '1') {
                            activacion = 'Activo';
                        } else {
                            activacion = 'Inactivo';
                        }
                        tabla.agregarFila('#data-table-regiones', [valor.Id, valor.Nombre, valor.Descripcion, valor.Sucursales, activacion], true);
                    });
                    evento.limpiarFormulario('#formRegionesLogistica');
                    evento.mostrarMensaje('.errorRegion', true, 'Datos insertados correctamente', 3000);
                } else {
                    evento.mostrarMensaje('.errorRegion', false, 'Ya existe el Nombre de la Región, por lo que ya no puedes repetirlo.', 3000);
                }
            });
        }
    });

    //Evento que permite actualizar la region de logistica
    $('#data-table-regiones tbody').on('click', 'tr', function () {
        var datos = $('#data-table-regiones').DataTable().row(this).data();
        var data = {Region: datos[0]};
        evento.enviarEvento('EventoLogistica/MostrarRegionActualizar', data, '#seccionRegionesLogistica', function (respuesta) {
            var idSucursales = (respuesta.datos.idSucursales[0].Sucursales);
            evento.mostrarModal('Actualizar Región', respuesta.formulario);
            select.crearSelect('select');
            var arraySucursales = JSON.parse("[" + idSucursales + "]");
            $('#inputActualizarNombreRegion').val(datos[1]);
            $('#inputActualizarDescripcionRegion').val(datos[2]);
            $('#selectActualizarSucursalesRegion').val(arraySucursales).trigger('change');

            $('#btnModalConfirmar').empty().append('Guardar');
            $('#btnModalConfirmar').off('click');
            $('#btnModalConfirmar').on('click', function () {
                var nombre = $('#inputActualizarNombreRegion').val();
                var descripcion = $('#inputActualizarDescripcionRegion').val();
                var sucursales = $('#selectActualizarSucursalesRegion').val();
                var estatus = $('#selectActualizarEstatusRegion').val();
                if (evento.validarFormulario('#formActualizarRegiones')) {
                    var data = {id: datos[0], nombre: nombre, descripcion: descripcion, sucursales: sucursales, estatus: estatus};
                    evento.enviarEvento('EventoLogistica/Actualizar_Region', data, '#seccionRegionesLogistica', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-regiones');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-regiones', [valor.Id, valor.Nombre, valor.Descripcion, valor.Sucursales, activacion], true);
                            });
                            $('#modal-dialogo').modal('hide');
                            evento.mostrarMensaje('.errorRegion', true, 'Datos Actualizados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorActualizarRegion', false, 'Ya existe el Nombre de la Región, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });
        });
    });
});


