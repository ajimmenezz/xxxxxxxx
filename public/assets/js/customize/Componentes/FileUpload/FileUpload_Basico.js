class FileUpload_Basico extends IUpload {

    iniciarFileUpload() {

        let configuracionBasica = this.obtenerConfiguracionBasica();
        
        (this.configuracion.hasOwnProperty('url')) ? configuracionBasica['uploadUrl'] = this.configuracion.url : null;
        (this.configuracion.hasOwnProperty('extensiones')) ? configuracionBasica['allowedFileExtensions'] = this.configuracion.extensiones : null;        
                
        $(`#${this.fileUpload}`).fileinput(configuracionBasica);
    }

}


