class IUpload {

    constructor(nombreFileUpload, configuracion = {}) {
        this.fileUpload = nombreFileUpload;
        this.configuracion = configuracion;
        this.datosExtra = {};
        this.pagina = new Utileria();
    }

    iniciarPlugin() {
        this.iniciarFileUpload();
    }

    obtenerConfiguracionBasica() {
        let _this = this;
        return {
            language: 'es',
            uploadUrl: 'Sin definir url en FileUpload',
            uploadAsync: false,
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            allowedFileExtensions: ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
            overwriteInitial: false,
            initialPreviewAsData: true,
            showAjaxErrorDetails: false,
            initialPreview: [],
            previewSettings: {
                image: {width: '160px', height: '164px'},
                video: {width: "160px", height: "164px"},
                other: {width: "160px", height: "164px"},
                object: {width: "160px", height: "164px"}
            },
            previewFileIconSettings: {
                doc: '<img src="/assets/img/Iconos/word_icon.png" style="width:112px; height:144px;">',
                docx: '<img src="/assets/img/Iconos/word_icon.png" style="width:112px; height:144px;">',
                xls: '<img src="/assets/img/Iconos/excel_icon.png" style="width:112px; height:144px;">',
                xlsx: '<img src="/assets/img/Iconos/excel_icon.png" style="width:112px; height:144px;">'
            },
            previewZoomButtonClasses: {
                toggleheader: 'hidden',
                fullscreen: 'hidden',
                borderless: 'hidden',
                close: 'botonCerraZomm'
            },
            previewThumbTags: {
                '{CUSTOM_TAG_NEW}': '',
                '{CUSTOM_TAG_INIT}': ''
            },
            layoutTemplates: {
                footer: `<div class="file-thumbnail-footer">
                            <div class="file-footer-caption">{caption}</div>
                            {actions}
                        </div>`,
                actions: `<div class="file-actions">
                            <div class="file-footer-buttons">
                                {CUSTOM_TAG_INIT}{delete}{zoom}
                            </div>
                            <div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>
                            <div class="clearfix"></div>
                        </div>`
            },
            deleteUrl: '',
            uploadExtraData: function () {
                var datos = _this.obteniendoDatosExtra();
                return datos;
            }
        }
    }

    obtenerConfiguracionBoton() {
        let _this = this;
        return {
            uploadUrl: 'Sin definir url en FileUpload',
            uploadAsync: false,
            language: 'es',
            showUpload: false,
            showCaption: false,
            dropZoneEnabled: false,
            browseClass: 'btn btn-warning',
            browseLabel: 'Examinar',
            browseIcon: "",
            removeIcon: " ",
            removeClass: "btn btn-danger",
            removeLabel: 'Borrar',
            fileActionSettings: {
                showUpload: false,
            },
            uploadExtraData: function () {
                var datos = _this.obteniendoDatosExtra();
                return datos;
            }
        }
    }

    obteniendoDatosExtra() {
        return this.datosExtra;
    }

    enviarPeticionServidor(panel = '', datos = {}, callback) {
        let _this = this;
        _this.definiendoDatosExtra(datos);
        console.log(datos);
//        if (_this.validarArchivos()) {
        $(`#${this.fileUpload}`).on('filebatchpreupload', function (event, data, previewId, index) {
            _this.pagina.empezarPantallaCargando(panel);
        }).on('filebatchuploadsuccess', function (event, data, previewId, index) {
            _this.pagina.quitarPantallaCargando(panel);
            if (callback !== null) {
                callback(data.response);
            }
        }).on('filebatchuploaderror ', (event, data, msg) => {
            _this.pagina.quitarPantallaCargando(panel);
        });

        $(`#${this.fileUpload}`).fileinput('upload');
//    }

    }

    validarArchivos() {
        if (this.totalArchivos() > 0) {
            return true;
        } else {
            throw 'Debes definir al menos un archivo';
        }
    }

    definiendoDatosExtra(datos = {}){
        this.datosExtra = datos;
        console.log(this.datosExtra);
    }

    totalArchivos() {
        return $(`#${this.fileUpload}`).fileinput('getFilesCount');
    }

    limpiarElemento() {
        $(`#${this.fileUpload}`).fileinput('clear');
    }

    bloquearElemento() {
        $(`#${this.fileUpload}`).fileinput('clear').fileinput('disable');
    }

    habilitarElemento() {
        $(`#${this.fileUpload}`).fileinput('enable');
    }

    setAtributos(valores = {}){
        let _this = this;
        $.each(valores, function (key, value) {
            $(`#${_this.fileUpload}`).attr(key, value);
        });
    }

}
