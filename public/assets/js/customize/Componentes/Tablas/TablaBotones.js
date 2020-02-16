class TablaBotones extends ITabla {

    iniciarTabla() {
        let tabla = $(`#${this.tabla}`).DataTable({
            responsive: true,
            language: super.obtenerIdioma()
        });
        tabla.draw();
    }

    campoEvidencias(evidencias) {
        return `<div class"text-center">
                        <a href="${evidencias}" data-lightbox="evidencias">
                            <img src ="/assets/img/Iconos/jpg_icon.png" width="20" height="20" />
                        </a>
                    </div>`;
    }
    
    botonEliminar(){
        return `<a href="javascript:;" class="btn btn-danger btn-xs m-r-5 btnEliminar"><i class="fa fa fa-trash-o"></i> Eliminar</a>`;
    }
}
;


