$(function () {
    //Objetos
    var recuperar = new Base();

    //Inicializa funciones de la plantilla
    App.init();
    
    //Cancelando tecla Enter
    $('body').keypress(function(e){
        if(e.keyCode ===13){
            e.preventDefault();
        }
    });   

    //Evento para recuperar password
    $('#btnRecuperar').on('click', function (e) {
        e.preventDefault();
        var _this = this;
        if (recuperar.validarFormulario('#formRecuperar')) {
            $(_this).attr('disabled','disabled');
            $('[name=correo]').attr('disabled','disabled');
            var data = {email: $('[name=correo]').val()};
            recuperar.enviarEvento('Acceso/Recuperar_Acceso', data, this, function (respuesta) {
                if (respuesta) {
                     recuperar.mostrarMensaje('.login-buttons', true, 'Se envio un correo para recuperar su contraseña.',2000);
                } else {
                    recuperar.mostrarMensaje('.login-buttons', false, 'El correo que esta ingresando no esta registrado.',1500);
                    $('[name=correo]').removeAttr('disabled');
                    $(_this).removeAttr('disabled');
                }
            });
        }
    });

    //Evento para regresar a login
    $('#btnRegresar').on('click', function (e) {
        e.preventDefault();
        recuperar.enviarPagina();
    });

});


