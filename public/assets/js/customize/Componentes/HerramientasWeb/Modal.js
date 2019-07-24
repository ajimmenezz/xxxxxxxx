class Modal {

    mostrarModal() {
        var titulo = arguments[0] || '';
        var contenido = arguments[1] || '';
        var botones = arguments[2] || false;
        var alinearContenido = arguments[3] || 'text-center';
        var alinearTitulo = arguments[4] || 'text-center';

        $('#modal-dialogo').modal({
            backdrop: 'static',
            keyboard: true
        });
        $('#modal-dialogo .modal-title').empty().append(titulo).addClass(alinearTitulo);
        $('#modal-dialogo .modal-body').empty().append(contenido).addClass(alinearContenido);
        
        if(botones){
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
        }
    }
    
    btnAceptar(idElemento, callback = null){
        $('#btnModalConfirmar').on('click', callback);
        this.cerrarModal();
    }
    
    cerrarModal(){
        $('#modal-dialogo .modal-title').empty();
        $('#modal-dialogo .modal-body').empty();
        $('#btnModalConfirmar').empty().append('Aceptar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cancelar').removeClass('hidden');
        $('#modal-dialogo').modal('hide');
    }

}