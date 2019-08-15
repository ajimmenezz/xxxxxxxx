class Imagenes {

    constructor(nombreImagen) {
        this.imagen = nombreImagen;
        this.objetoImagen = $(`#${this.imagen}`);
    }

    aplicarImagen(ruta) {
        this.objetoImagen.addClass('image-inner');
        this.objetoImagen.append('<a class="text-center">\n\
                                    <img id="im" style="height:150px !important; max-height:150px !important;" src="' + ruta + '">\n\
                                </a>')
    }
    
    aplicarMultipleImagen(ruta){
        let _this = this;
        var misImagenes = ruta.split(",");
        $.each(misImagenes, function (key, value) {
            _this.aplicarImagen(value);
        });
    }
    
    limpiarCampoImagen(){
        this.objetoImagen.empty();
    }
}