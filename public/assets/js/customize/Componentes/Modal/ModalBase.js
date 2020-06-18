
class ModalBase {

    constructor(nombreModal) {
        this.modal = $(`#${nombreModal}`);
        this.titulo = $(`#${nombreModal} .modal-title`);
        this.cuerpo = $(`#${nombreModal} .modal-body`);
        this.piePagina = $(`#${nombreModal} .modal-footer`);
                       
    }
    
    mostrarModal(titulo = 'Titulo Modal', contenido = 'Contenido del modal') {

        let _this = this;

        _this.modal.modal({
            backdrop: 'static',
            keyboard: true
        });

        _this.titulo.empty().append(titulo).addClass('text-center');
        _this.cuerpo.empty().append(contenido);

        _this.modal.on('hidden.bs.modal', function () {
            _this.titulo.empty();
            _this.cuerpo.empty();
            _this.btnAceptar.empty().append('Aceptar').removeClass('hidden');
            _this.btnCancelar.empty().append('Cancelar').removeClass('hidden');
        });
    }
   
    cerrarModal() {
        this.modal.modal('hide');
    }
    
    mostrarError(objeto = '', mensaje = '') {

        let objetoError = $(`#${objeto}`);
        let hijos = objetoError.children();

        if (hijos.length <= 0) {
            objetoError.css('display', 'block');
            objetoError.append(`<div class="alert alert-danger fade in m-b-15 text-center">
                                <strong>Error!</strong> ${mensaje}.
                                <span class="close" data-dismiss="alert">&times;</span>
                            </div>`);
            setTimeout(function () {
                objetoError.fadeOut('slow', function () {
                    objetoError.empty();
                });
            }, 4000);
        }
    }
    
}


