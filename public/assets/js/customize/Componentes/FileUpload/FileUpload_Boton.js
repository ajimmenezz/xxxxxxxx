class FileUpload_Boton extends IUpload {

    iniciarFileUpload() {

        let configuracionBoton = this.obtenerConfiguracionBoton();

        (this.configuracion.hasOwnProperty('url')) ? configuracionBoton['uploadUrl'] = this.configuracion.url : null;
        (this.configuracion.hasOwnProperty('tituloAceptar')) ? configuracionBoton['browseLabel'] = this.configuracion.tituloAceptar : 'Examinar';
        (this.configuracion.hasOwnProperty('colorBotonAceptar')) ? configuracionBoton['browseClass'] = this.configuracion.colorBotonAceptar : 'btn btn-warning';
        (this.configuracion.hasOwnProperty('extensiones')) ? configuracionBoton['allowedFileExtensions'] = this.configuracion.extensiones : null;

        $(`#${this.fileUpload}`).fileinput(configuracionBoton);
    }

}


