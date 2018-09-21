$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('.editarPerfil').off("click");
    $('.editarPerfil').on('click', function () {
        var campo = $(this).attr('data-campo');
        var input = $(this).attr('data-input');
        var nombreInput = $(this).attr('data-nombreInput');
        var tabla = $(this).attr('data-tabla');
        var data = {'campo': campo, 'input': input, 'nombreInput': nombreInput};
        var validarInput = '';
        var validarExpresion = true;
        var mensajeError = '';

        evento.enviarEvento('PerfilUsuario/MostrarFormularioPerfilUsuario', data, '#configuracionPerfilUsuario', function (respuesta) {
            evento.iniciarModal('#modalEdit', 'Editar Perfil Usuario', respuesta.modal);
            if (campo === 'IdSexo') {
                if (input === 'Femenino') {
                    $('#selectPerfilGenero').val('1').trigger('change');
                } else if (input === 'Masculino') {
                    $('#selectPerfilGenero').val('2').trigger('change');
                }
            }

            $('#btnGuardarCambios').off('click');
            $('#btnGuardarCambios').on('click', function () {
                if (campo === 'IdSexo') {
                    validarInput = $('#selectPerfilGenero').val();
                } else {
                    validarInput = $('#select' + campo).val();
                }

                switch (nombreInput) {
                    case 'Email':
                        var expresion = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'Incluye un "@" en tu dirección de correo electrónico.';
                        break;
                    case 'Tel.1':
                        var expresion = /^([0-9]{3})+(-)+([0-9]{10})$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'El formato del número que escribio es incorrecto.';
                        break;
                    case 'Tel.2':
                        var expresion = /^([0-9]{2})+(-)+([0-9]{3})+(-)+([0-9]{7})$/;
                        validarExpresion = expresion.test(validarInput);
                        mensajeError = 'El formato del número que escribio es incorrecto.';
                        break;
                    default:
                        expresion = /^$/;
                }

                if (validarInput !== '') {
                    if (validarExpresion) {
                        var datos = {'inputNuevo': validarInput, 'campo': campo, 'tabla': tabla};
                        evento.enviarEvento('PerfilUsuario/ActualizarPerfilUsuario', datos, '#modalEdit', function (resultado) {
                            if (resultado) {
                                evento.terminarModal('#modalEdit');
                                evento.mensajeConfirmacion('Se actualizo la información correctamente.', 'Correcto');
                            } else {
                                evento.mostrarMensaje(".errorPerfilUsuario", false, "El campo " + nombreInput + " es el mismo que el anterior.", 4000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje(".errorPerfilUsuario", false, mensajeError, 4000);
                    }
                } else {
                    evento.mostrarMensaje(".errorPerfilUsuario", false, "El campo " + nombreInput + " esta vacío.", 4000);
                }
            });
        });
    });
});