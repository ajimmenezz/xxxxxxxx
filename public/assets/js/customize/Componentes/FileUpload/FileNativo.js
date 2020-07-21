class FileNativo {

    constructor(idInputfile = '', contenedor = '') {
        this.inputFile = idInputfile;
        this.contenedor = contenedor;
        this.formData = new FormData();
        this.formatoDefault = ['jpeg', 'png', 'gif', 'svg+xml'];
    }

    addListenerChange(formato = []) {
        let _this = this;
        let file = null;

        $(document).on('change', `#${this.inputFile}`, function () {

            let filesInvalid = false;
            let files = this.files;
            let listFiles = [];
            let setFormato = _this.setFormato(formato);

            for (let i = 0; i < files.length; i++) {
                file = files[i];

                if (setFormato.indexOf(file.type) !== -1) {
                    listFiles.push(file);
                } else {
                    filesInvalid = true;
                }
            }

            if (filesInvalid) {
                alert('Ingresaron archivos no validados');
            } else {
                _this.setFormData(listFiles);
                _this.insertPreview();
            }
        });
    }

    setFormato(formato) {

        if (formato.length === 0) {
            formato = this.formatoDefault;
        }
        return formato.map(tipo => 'image/' + tipo);
    }

    insertPreview() {
        $(`#${this.contenedor}`).empty();
        for (let file of this.formData.values()) {
            let imagenCodificada = URL.createObjectURL(file);
            $(`#${this.contenedor}`).append(`<img src="${imagenCodificada}" />`);
        }
    }

    setFormData(listaImage = []) {
        let _this = this;
        let multiple = $(`#${this.inputFile}`).attr('multiple');
        
        if(multiple === undefined){
            _this.formData.delete('image');
        }
        
        for (let imagen of listaImage) {
            _this.formData.append('image', imagen);
        }
    }

    uploadServer(url = '', datos = {}, callback){

        let _this = this;
        _this.setExtraData(datos);

        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: _this.formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (data) {
                callback(data);
            },
            error: function (e) {

            }
        });

    }

    setExtraData(datos) {
        let _this = this;
        
        if (datos) {
            _this.formData.delete('extraData');
            _this.formData.append('extraData', JSON.stringify(datos));         
        }
    }
    
    clear(){
        $(`#${this.contenedor}`).empty().append(`<img src="/assets/img/Iconos/no-thumbnail.jpg" />`);
        $(`#${this.inputFile}`).val('');
        this.formData.delete('image');
        this.formData.delete('extraData');
    }
}

