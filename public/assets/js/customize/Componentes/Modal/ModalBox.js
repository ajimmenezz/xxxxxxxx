class ModalBox extends Modal {

    constructor(nombre) {
        super(nombre);
        this.nombre = nombre;
        this.contendorBotones = $(`#${nombre} .modal-footer`);
        this.btnAceptar = $('#btnModalBoxConfirmar');
        this.btnCancelar = $('#btnModalBoxAbortar');
    }

    ocultarBotonAceptar() {
        this.btnAceptar.addClass('hidden');
    }

    mostrarBotonAceptar(valor = 'Aceptar') {
        this.btnAceptar.empty().append(valor).removeClass('hidden');
    }

    cambiarValorBotonAceptar(valor = 'Aceptar') {
        this.btnAceptar.empty().append(valor);
    }

    funcionalidadBotonAceptar(valor = 'Aceptar', callback) {
        (valor !== null) ? this.mostrarBotonAceptar(valor) : this.mostrarBotonAceptar();
        this.btnAceptar.off('click');
        this.btnAceptar.on('click', callback);
    }

    ocultarBotonCanelar() {
        this.btnCancelar.addClass('hidden');
    }

    mostrarBotonCancelar(valor = 'Cancelar') {
        this.btnCancelar.empty().append(valor).removeClass('hidden');
    }

    cambiarValorBotonCanelar(valor = 'Cancelar') {
        this.btnCancelar.empty().append(valor);
    }

    funcionalidadBotonCancelar(valor = 'Cancelar', callback) {
        (valor !== null) ? this.mostrarBotonCancelar(valor) : this.mostrarBotonCancelar();
        this.btnCancelar.off('click');
        this.btnCancelar.on('click', callback);
    }

    borrarContenido() {
        this.cuerpo.empty();
    }

    agregarContenido(contenido = 'Sin contenido') {
        this.cuerpo.empty().append(contenido);
    }

    agregarBotonAModal(boton = '') {
        this.contendorBotones.append(boton);
    }

    eliminarBotonAModal(elemento = '') {
        $(`${elemento}`).remove();
    }

    colorFondoTitulo(elemento = '') {
        $(`#${this.nombre} .modal-header`).attr('style', elemento);
    }

    colorTitulo(elemento = '') {
        $(`#${this.nombre} .modal-title`).addClass(elemento);
    }

    colorBotonAceptar(elemento = '') {
        this.btnAceptar.removeClass('btn-primary');
        this.btnAceptar.addClass(elemento);
    }
}



