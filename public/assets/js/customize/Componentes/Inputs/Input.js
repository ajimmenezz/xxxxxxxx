class Input {

    constructor(nombreInput) {
        this.input = nombreInput;
        this.objetoInput = $(`#${this.input}`);
    }

    limpiarElemento() {
        this.objetoInput.val(null);
    }

    obtenerValor() {

        if (this.objetoInput.attr('type') === 'checkbox') {
            if (this.objetoInput.is(':checked')) {
                return this.objetoInput.val();
            }else{
                return null;                
            }
        }
        return this.objetoInput.val();
    }
    
    bloquearElemento() {
        this.objetoInput.attr('disabled', 'disabled');
    }

    habilitarElemento() {
        this.objetoInput.removeAttr('disabled');
    }

    definirValor(valor = '') {
        this.objetoInput.val(valor);
    }
    
    insertarContenido(valor = ''){
        this.objetoInput.append(valor);
    }

    iniciarPlugin() {
        this.objetoInput = $(`#${this.input}`);
    }
    
    evento(evento = '', callback) {
        this.objetoInput.on(evento, callback);
    }
}

