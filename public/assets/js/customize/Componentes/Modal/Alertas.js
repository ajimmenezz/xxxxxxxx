
class Alertas extends ModalBase {

    constructor(nombreModal = '') {
        super(nombreModal);
        this.cuerpo = $(`#${nombreModal} .modal-body .alert-danger`);
        this.btnAceptar = $('#btnAlertaModalConfirmar');
        this.btnCancelar = $('#btnAlertaModalAbortar');
    }

    mostrarAlerta(titulo = 'Error', mensaje = '') {

        let _this = this;

        _this.mostrarModal(titulo, mensaje);
        setTimeout(function () {
            _this.cerrarModal();
        }, 4000);
    }

    mostrarMensajeError(objeto = '', mensaje = '') {

        let objetoError = $(`#${objeto}`);
        let hijos = objetoError.children();

        if (hijos.length <= 0) {
            objetoError.css('display', 'block');
            objetoError.append(`<div class="alert alert-danger fade in m-b-15">
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
