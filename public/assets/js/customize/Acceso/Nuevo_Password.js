$(function () {
    //Objetos
    var eventos = new Base();
    var expresiones = {
        '^(?=.*[A-Z])': 'Te falta agregar una mayuscula',
        '(?=.*[0-9])': 'Te falta agregar al menos un numero',
        '(?=.*[a-z])': 'Te falta agregar al menos una minuscula',
        '(.{8,15})$': 'La longitud minima es 8 y maxima es 15'
    }
    //Inicializa funciones de la plantilla
    App.init();

    //Cancelando tecla Enter
    $('body').keypress(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    //Guarda nuevo password
    $('#btnGuardar').on('click', function (e) {
        e.preventDefault();
        var _this = this;
        if (eventos.validarFormulario('#formNuevoPsw')) {
            $(_this).attr('disabled', 'disabled');
            $('#btnlimpiar').attr('disabled', 'disabled');
            var nuevo = $('#inputNuevoPsw').val();
            var confirmacion = $('#inputConfirmaNuevoPsw').val();

            if (nuevo === confirmacion) {
                
                var mensaje = validarPassword(nuevo);
                
                if (mensaje === null) {
                    var data = {nuevo: nuevo, usuario: $('#usuario').val(), id: $('#pswId').val()};
                    eventos.enviarEvento('Acceso/Modificar_Password', data, this, function (respuesta) {
                        if (respuesta) {
                            $('#inputNuevoPsw').attr('disabled', 'disabled');
                            $('#inputConfirmaNuevoPsw').attr('disabled', 'disabled');
                            $('#formNuevoPsw')[0].reset();
                            $('#mostrar').append();
                            eventos.mostrarMensaje('.login-buttons', true, 'Se cambio la contraseña con exito.', 5000);
                            setTimeout(eventos.enviarPagina('/'), 7000)
                        } else {
                            eventos.mostrarMensaje('.login-buttons', false, 'No se pudo realizar el cambio vuelve a intentarlo.', 5000);
                            $(_this).removeAttr('disabled');
                            $('#btnlimpiar').removeAttr('disabled');
                            $('#formNuevoPsw')[0].reset();
                        }
                    });
                } else {
                    eventos.mostrarMensaje('.login-buttons', false, mensaje, 5000);
                    $('#btnGuardar').removeAttr('disabled');
                }
            } else {
                $(_this).removeAttr('disabled');
                $('#btnlimpiar').removeAttr('disabled');
                $('#formNuevoPsw')[0].reset();
            }
        }
    });

    //Limpia el formulario
    $('#btnlimpiar').on('click', function (e) {
        eventos.limpiarFormulario('#formNuevoPsw');
    });

    var validarPassword = function (password) {
        var mensaje = null;
        $.each(expresiones, function (key, value) {
            var expre = new RegExp(key);
            if (expre.test(password)) {
                expre = undefined;
            } else {
                mensaje = value;
                return false;
            }
        });
        return mensaje;
    };
});


