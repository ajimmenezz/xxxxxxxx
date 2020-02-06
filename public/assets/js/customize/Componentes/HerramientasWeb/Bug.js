class Bug {
    constructor() {
        this.modal = new Modal('modal-dialogo');
    }

    validar(dato) {
        if (!dato) {
            this.modal.mostrarModal('Error', '<h3 class="text-center">Ocurrió un problema en la petición. Intentalo mas tarde</h3>');
            this.modal.ocultarBotonAceptar();
            return false;
        } else if (dato.hasOwnProperty('Error')) {
            this.modal.mostrarModal('Error', `<h3 class="text-center">${dato.Error}</h3>`);
            this.modal.ocultarBotonAceptar();
            return false;
        }
        return true;
    }
}

