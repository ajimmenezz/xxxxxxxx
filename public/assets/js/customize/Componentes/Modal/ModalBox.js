class ModalBox extends Modal {

    constructor(nombre) {
        super(nombre);
        this.nombre = nombre;
        this.cabecera = $(`#${this.nombre} .modal-header`);
        this.btnAceptar = $('#btnModalBoxConfirmar');
        this.btnCancelar = $('#btnModalBoxAbortar');
    }

    setEstilos(estilos = {}){
        let _this = this;
        for (let estilo in estilos){            
            _this[estilo].addClass(estilos[estilo]);
        }        
    }
}



