class Pagina {

    constructor(objetos = new Map) {

        this.tablas = new Map();
        this.formularios = new Map();
        this.gantts = new Map();
        this.socket = new Socket();
        this.modal = new Modal('modal-dialogo');
        this.alerta = new Alertas('modal-alerta-error');
        this.select = new Select('select');

        this.crearTablas(objetos);
        this.crearFormularios(objetos);
        this.crearGantt(objetos);
    }

    crearTablas(elementos) {

        let elementosTablas = elementos.get('tablas');
        let mapTabla = this.tablas;
        let tabla;

        if (elementosTablas !== undefined) {
            $.each(elementosTablas, function (key, value) {
                if (value.tipoTabla === 'basica') {
                    tabla = new TablaBasica(key, value.datos);
                    mapTabla.set(key, tabla);
                } else if (value.tipoTabla === 'columnasOcultas') {
                    tabla = new TablaColumnaOculta(key, value.datos);
                    mapTabla.set(key, tabla);
                }
            });
        }
    }

    crearFormularios(elementos) {
        let _this = this;
        let elementosFormularios = elementos.get('formularios');
        let formularios = this.formularios;

        if (elementosFormularios !== undefined) {
            $.each(elementosFormularios, function (key, value) {
                let formulario = new Formulario(key, value, _this);
                formularios.set(key, formulario);
            });
        }
    }

    crearGantt(elementos) {
        let elementosGantt = elementos.get('gantt');
        let gantts = this.gantts;

        if (elementosGantt !== undefined) {
            $.each(elementosGantt, function (key, value) {
                let graficaGantt = new Gantt(key, value);
                gantts.set(key, graficaGantt);
            });
        }
    }

    enviarPeticionServidor(objeto = null, url, datos = {}, callback = null){

        let _this = this;

        $.ajax({
            url: url,
            method: 'post',
            data: datos,
            dataType: 'json',
            beforeSend: function () {
                if (objeto !== null) {
                    _this.empezarPantallaCargando(objeto);
                }
            }
        }).done(function (data) {
            _this.errorServidor(data);
            _this.quitarPantallaCargando(objeto);
            if (callback !== null) {
                callback(data);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            try {
                _this.quitarPantallaCargando(objeto);
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
                _this.alerta.mostrarAlerta('Error', `Surgio un problema de comunicación con el servidor`);
            }
        });
    }

    errorServidor(data) {
        if (data.hasOwnProperty('Error')) {
            this.enviarPagina(data.Error);
        } else if (data.hasOwnProperty('MensajeError')) {
            console.log('mensaje error de el servidor');
        }
    }

    empezarPantallaCargando(objeto) {

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

    quitarPantallaCargando(objeto) {

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

    mostrarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.hasClass('hidden')) {
            elemento.removeClass('hidden');
    }
    }

    ocultarElemento(objeto = null) {

        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (!elemento.hasClass('hidden')) {
            elemento.addClass('hidden');
    }
    }

    limpiarFormulario(objeto = null) {
        let formulario = this.formularios.get(objeto);
        formulario.limpiarElementos();
    }

    asignarValorElementoPagina(elemento = '', valor = '') {

        let _this = this;
        let elementoHtml = false;
        if (elemento !== '') {

            _this.formularios.forEach((value, key, map) => {

                let formulario = _this.formularios.get(key);

                if (formulario.validarExistenciaElemento(elemento)) {
                    formulario.asignarValorElemento(elemento, valor);
                } else {
                    elementoHtml = true;
                }
            });

            if (elementoHtml) {
                $(elemento).empty().append(valor);
            }
    }
    }

    cerrarSesion() {

        let _this = this;
        let datos = {tipoServicio: 'salir'};

        _this.enviarPeticionServidor(null, '/Api/reportar', datos, function (respuesta) {
            _this.enviarPagina('/Logout');
        });

    }

    enviarPagina(url = null) {

        if (typeof url === null) {
            window.location.href = "Logout";
        } else {
            window.location.href = url;
    }

    }

    obtenerDatoFilaTabla(tabla = '', callback = null) {

        let _this = this;

        $(`#${tabla} tbody`).on('click', 'tr', function () {
            let objetoTabla = _this.tablas.get(tabla);
            callback(objetoTabla.datosFila(this));
        });
    }

    bloquearBoton(objeto = '') {
        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.attr('disabled') === undefined) {
            elemento.addClass('disabled');
            elemento.attr('disabled', 'disabled');
    }
    }

    habilitarBoton(objeto = '') {
        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }

        if (elemento.attr('disabled') !== undefined) {
            elemento.removeAttr('disabled');
            elemento.removeClass('disabled');
    }
    }

    agregarElemento(objeto = '', html = '') {
        
        let elemento = $(`#${objeto}`);

        if (!elemento.length) {
            elemento = $(`.${objeto}`);
        }
        
        elemento.append(html);
    }
}

