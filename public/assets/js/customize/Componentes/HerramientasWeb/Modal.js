class Modal {

    mostrarModalBasico() {
        var titulo = arguments[0] || '';
        var contenido = arguments[1] || '';
        var alinearContenido = arguments[2] || 'text-center';
        var alinearTitulo = arguments[3] || 'text-center';

        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        $('#modal-dialogo .modal-title').empty().append(titulo).addClass(alinearTitulo);
        $('#modal-dialogo .modal-body').empty().append(contenido).addClass(alinearContenido);
        $('#modal-dialogo .modal-footer').empty().append('<a id="btnAceptar" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</a>\n\
                                            <a id="btnCerrar" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"> Cerrar</a>').addClass(alinearTitulo);

        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
    }

    btnAceptar(idElemento, callback = null) {
        $('#btnModalConfirmar').on('click', callback);
        this.cerrarModal();
    }

    cerrarModal() {
        $('#modal-dialogo .modal-title').empty();
        $('#modal-dialogo .modal-body').empty();
        $('#btnModalConfirmar').empty().append('Aceptar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cancelar').removeClass('hidden');
        $('#modal-dialogo').modal('hide');
    }

    mostrarModalBotonTabla(claseBoton, modal) {
        let list, index;
        list = document.getElementsByClassName(claseBoton);
        for (index = 0; index < list.length; ++index) {
            list[index].setAttribute('href', modal);
        }
    }

}