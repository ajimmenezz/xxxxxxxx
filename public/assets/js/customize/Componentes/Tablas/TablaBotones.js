class TablaBotones extends ITabla {

    iniciarTabla() {
        let tabla = $(`#${this.tabla}`).DataTable({
            responsive: true,
            language: super.obtenerIdioma()
        });
        tabla.draw();
    }

    campoEvidencias(evidencias, id) {
        let html = '';
        
        $.each(evidencias.split(','), function (llave, imagen) {
            html += '<a href="' + imagen + '" data-lightbox="evidenciaInstalacion' + id + '">';
        });
        
        html += '<i class="fa fa-file-photo-o "></i></a></div>';

        return html;
    }

    botonEliminar(id) {
        return `<div class="seccion-botones-acciones">
                    <a href="javascript:;" class="btn btn-danger btn-xs m-r-5 btnEliminar" data-id="${id}"><i class="fa fa fa-trash-o"></i> Eliminar</a>
                </div>`;
    }
}
;


