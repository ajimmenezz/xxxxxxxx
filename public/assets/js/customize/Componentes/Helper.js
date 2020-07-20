class Helper {

    static enviarPeticionServidor(objeto = null, url, datos = {}, callback = null){

        $.ajax({
            url: url,
            method: 'post',
            data: datos,
            dataType: 'json',
            beforeSend: function () {
                if (objeto !== null) {
                    Helper.empezarPantallaCargando(objeto);
                }
            }
        }).done(function (data) {
            Helper.errorServidor(data);
            Helper.quitarPantallaCargando(objeto);
            if (callback !== null) {
                callback(data);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            try {
                Helper.quitarPantallaCargando(objeto);
                if (jqXHR.status === 0) {
                    throw 'Not connect: Verify Network.';
                } else if (jqXHR.status == 404) {
                    throw 'Pagina no encontrada error [404]';
                } else if (jqXHR.status == 500) {
                    throw 'Error interno del servidor [500].';
                } else if (textStatus === 'parsererror') {
                    throw 'Error no se recibe un JSON.';
                } else if (textStatus === 'timeout') {
                    throw 'Error se termino el tiempo de espera con el servidor.';
                } else if (textStatus === 'abort') {
                    throw 'Ajax solicitud abortada.';
                } else {
                    throw 'Sin atrapar el Error: ' + jqXHR.responseText;
                }
            } catch (exception) {
            }
        });
    }

    static errorServidor(data) {
        if (data.hasOwnProperty('Error')) {
            Helper.enviarPagina(data.Error);
        } else if (data.hasOwnProperty('MensajeError')) {
            console.log('mensaje error de el servidor');
        }
    }

    static empezarPantallaCargando(objeto) {

        let panel = $(`#${objeto}`);
        let cuerpo;

        if (panel.hasClass('panel')) {
            cuerpo = panel.find('.panel-body');
            var spinnerHtml = '<div class="panel-loader"><span class="spinner-small"></span></div>';
            if (!panel.hasClass('panel-loading')) {
                panel.addClass('panel-loading');
                $(cuerpo).prepend(spinnerHtml);
            }
        } else if (panel.hasClass('modal')) {
            var contenido = panel.find('.modal-content');
            cuerpo = $(contenido).find('.modal-body');
            var spinnerHtml = '<div class="modal-loader"><span class="spinner-small"></span></div>';
            if (!$(contenido).hasClass('modal-loading')) {
                $(contenido).addClass('modal-loading');
                $(contenido).prepend(spinnerHtml);
            }
        } else {
            var recargando = '<div class="alert fade in m-b-15 text-center " id="iconCargando">\n\
                                    <i class="fa fa-2x fa-refresh fa-spin"></i></div><div class="hidden-xs">\n\
                                </div>';
            panel.before(recargando);
        }
    }

    static quitarPantallaCargando(objeto) {

        let panel = $(`#${objeto}`);

        if (panel.hasClass('panel')) {
            panel.removeClass('panel-loading');
            panel.find('.panel-loader').remove();
        } else if (panel.hasClass('modal')) {
            var contenido = panel.find('.modal-content');
            $(contenido).removeClass('modal-loading');
            $(contenido).find('.modal-loader').remove();
        } else {
            $('#iconCargando').remove();
        }

    }

    static mostrarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.hasClass('hidden')) {
            elemento.removeClass('hidden');
    }
    }

    static ocultarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (!elemento.hasClass('hidden')) {
            elemento.addClass('hidden');
    }
    }

    static cerrarSesion() {

        let _this = this;
        let datos = {tipoServicio: 'salir'};

        Helper.enviarPeticionServidor(null, '/Api/reportar', datos, function (respuesta) {
            _this.enviarPagina('/Logout');
        });

    }

    static enviarPagina(url = null) {

        if (typeof url === null) {
            window.location.href = "Logout";
        } else {
            window.location.href = url;
    }

    }

    static bloquearBoton(objeto = '') {
        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.attr('disabled') === undefined) {
            elemento.addClass('disabled');
            elemento.attr('disabled', 'disabled');
    }
    }

    static habilitarBoton(objeto = '') {
        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.attr('disabled') !== undefined) {
            elemento.removeAttr('disabled');
            elemento.removeClass('disabled');
    }
    }

    static agregarElemento(objeto = '', html = '') {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        elemento.append(html);
    }
    
    static quitarContenidoElemento(objeto = '', html = '') {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        elemento.empty();
    }
}

