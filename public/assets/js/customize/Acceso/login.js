$(function () {
    //Objetos
    var acceso = new Base();

    //Inicializa funciones de la plantilla
    App.init();

    //Cancelando tecla Enter
    $('body').keypress(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    //Solicita recuperar el acceso
    $('#resetearPws').on('click', function (e) {
        e.preventDefault();
        window.location.href = "Recuperar_Acceso";
    });


    //Evento enter en el campo usuario
    $('#inputUsuario').keypress(function (e) {
        ingresar(this, e);
    });

    //Evento enter en el campo usuario
    $('#inputPassword').keypress(function (e) {
        ingresar(this, e);
    });


    //Evento para ingresar al sistema
    $('#btnIngresar').keypress(function (e) {
        ingresar(this, e);
    });

    //Evento para ingresar al sistema
    $('#btnIngresar').on('click', function (e) {
        e.preventDefault();
        var _this = this;
        if (acceso.validarFormulario('#ingreso')) {
            $(_this).attr('disabled', 'disabled');
            var data = {usuario: $('#inputUsuario').val(), password: $('#inputPassword').val()};
            acceso.enviarEvento('Acceso/Ingresar', data, this, function (respuesta) {
                var url = respuesta.url;
                
                if (respuesta.acceso) {
                    acceso.mostrarModal('Definir nuevo Password',
                            '<form class="margin-bottom-0" id="formNuevoPsw" data-parsley-validate="true">\n\
                                <div class="row">\n\
                                    <div class="col-md-12">\n\
                                        <input type="password" class="form-control" placeholder="Nuevo Password" id="inputNuevoPsw" data-parsley-required="true" data-parsley-minlength="8" data-parsley-maxlength="12"/>\n\
                                    </div>\n\
                                </div>\n\
                                <br>\n\
                                <div class="row">\n\
                                    <div class="col-md-12">\n\
                                        <input type="password" class="form-control" placeholder="Confirmar Password" id="inputConfirmaNuevoPsw" data-parsley-required="true" data-parsley-equalto="#inputNuevoPsw"/>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <div class="form-inline muestraCarga"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-md-12">\n\
                                    <div class="errorNuevoPassword"></div>\n\
                                </div>\n\
                                <!--Finalizando Error-->\n\
                            </form');
                    $('#btnModalAbortar').addClass('hidden');
                    $('#btnModalConfirmar').empty().append('Guardar');
                    $('#btnModalConfirmar').off('click');
                    $('#btnModalConfirmar').on('click', function () {
                        var nuevo = $('#inputNuevoPsw').val();
                        var expre = new RegExp('^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,12}$');
                        if (acceso.validarFormulario('#formNuevoPsw')) {
                            var data = {id: respuesta.id, nuevo: nuevo, usuario: respuesta.usuario};
                            if (expre.test(nuevo)) {
                                acceso.enviarEvento('Acceso/Modificar_Password', data, '#modal-dialogo', function (respuesta) {
                                    acceso.mostrarModal('Correcto',
                                            '<div class="row">\n\
                                        <div class="col-md-12 text-center">\n\
                                            <h5>Se actualizo tu constraseña correctamente</h5>\n\
                                        </div>\n\
                                    </div>');
                                    $('#btnModalConfirmar').addClass('hidden');
                                    $('#btnModalAbortar').empty().append('Cerrar');
                                    acceso.enviarPagina(url);
                                });
                            } else {
                                acceso.mostrarMensaje('.errorNuevoPassword', false, 'El password debe contener al menos una mayuscula, una minuscula y un número', 5000);
                            }
                        }
                    });
                } else {
                    console.log(respuesta.logueo);
                    if (respuesta.logueo !== null) {
                        data = {tipoServicio: 'acceso', respuestaRegistroLogueo: respuesta.resultado, logueo: respuesta.logueo};
                        acceso.enviarEvento('Api/reportar', data, _this, function (respuesta) {
                            acceso.enviarPagina(url);
                        });
                    } else {
                        $('#inputPassword').val('');
                        acceso.mostrarMensaje('.login-buttons', false, ' Usuario y Password incorrectos.', 2000);
                        $(_this).removeAttr('disabled');
                    }
                }
            });
        }
    });

    //Funcion que capturar el evento enter y ejecutar el botn ingresar
    var ingresar = function (Objeto, elemento) {
        if (elemento.keyCode === 13) {
            elemento.preventDefault();
            $('#btnIngresar').trigger('click');
            $(Objeto).blur();
        }
    };

});


