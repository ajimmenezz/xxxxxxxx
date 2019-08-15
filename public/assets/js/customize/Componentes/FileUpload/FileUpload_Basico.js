class FileUpload_Basico extends Upload {

    iniciarFileUpload(configuracionAdicional = {}) {
        
        let configuracionBasica = this.obtenerConfiguracionBasica();

        (this.configuracion.hasOwnProperty('url')) ? configuracionBasica['uploadUrl'] = this.configuracion.url : null;
        (this.configuracion.hasOwnProperty('extensiones')) ? configuracionBasica['allowedFileExtensions'] = this.configuracion.extensiones : null;

        if (configuracionAdicional !== {}) {
            $.each(configuracionAdicional, function (key, value) {
                if (configuracionBasica.hasOwnProperty(key)) {
                    configuracionBasica[key] = value;
                }
            });
        }
        $(`#${this.fileUpload}`).fileinput(configuracionBasica);
    }

}


