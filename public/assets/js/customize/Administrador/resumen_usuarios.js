$(function () {
    //objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();

    //Creando tabla usuarios 
    tabla.generaTablaPersonal('#data-table-usuarios', null, null, true, true);

    //Evento quemaneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora del sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerrar la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();


    //Evento que permite actualizar a los usuarios
    $('#data-table-usuarios').on('click', 'tr', function () {
        var datos = $('#data-table-usuarios').DataTable().row(this).data();
        var data = {Usuario: datos[0]};
        evento.enviarEvento('EventoUsuario/MostrarUsuarioActualizar', data, '#seccionUsuario', function (respuesta) {
            var perfil = respuesta.datos.idPerfil[0].IdPerfil;
            var permiso = (respuesta.datos.permiso[0].PermisosAdicionales);
            evento.mostrarModal('Actualizar Usuario', respuesta.formulario);
            select.crearSelect('select');
            var array = JSON.parse("[" + permiso + "]");
            $('#selectActualizarPerfil').val(perfil).trigger('change');
            $('#selectActualizarPermisos').val(array).trigger('change');
            $('#inputActualizarEmail').val(datos[6]);
            $('#inputActualizarSDKey').val(datos[8]);
            $('#btnModalConfirmar').empty().append('Guardar');
            $('#btnModalConfirmar').off('click');
            $('#btnModalConfirmar').on('click', function () {
                var permisos = $('#selectActualizarPermisos').val();
                var perfil = $('#selectActualizarPerfil').val();
                var email = $('#inputActualizarEmail').val();
                var estatus = $('#selectActualizarEstatus').val();
                var SDKey = $('#inputActualizarSDKey').val();
                var activacion;
                if (evento.validarFormulario('#formActualizarUsuarios')) {
                    var data = {id: datos[0], perfil: perfil, email: email, permisos: permisos, estatus: estatus, SDKey: SDKey};
                    evento.enviarEvento('EventoUsuario/Actualizar_Usuario', data, '#actualizarUsuario', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-usuarios');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-usuarios', [valor.Id, valor.Usuario, valor.Perfil, valor.PermisosAdicionales, valor.Nombre, valor.Email, valor.EmailCorporativo, activacion, valor.SDKey], true);
                            });
                            $('#modal-dialogo').modal('hide');
                            evento.mostrarMensaje('.errorResumenUsuario', true, 'Datos Actualizados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorActualizarUsuario', false, 'Ya se dio de alta ese correo, por lo que ya no puedes repetirlo.', 3000);
                        }
                    });
                }
            });

        });
    });
});