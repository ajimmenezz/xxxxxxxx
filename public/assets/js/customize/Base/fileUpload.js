//Super clase que define cuando se envia un evento
function Upload() {

    this.extra = {};

    //Metodo que define el valor de extra
    this.setDatosExtra = function (datos) {
        this.extra = datos;
    };

    //Metodo que se encarga regresar la variable exra
    this.getDatosExtra = function () {
        return this.extra;
    };

    //Metodo en cargado de definir las imagenes iniciales.
    this.getNombreImagen = function (arreglo, solicitud, extraData = {}) {
        var imagenes = [];
        var extimagenes = ['jpg', 'bmp', 'jpeg', 'gif', 'png'];
        var extdocumentos = ['doc', 'docx', 'xls', 'xlsx', 'xml'];
        $.each(arreglo, function (key, value) {
            var extencion = value.substring(value.lastIndexOf('.') + 1);
            extencion.toLowerCase();
            if (extimagenes.indexOf(extencion) !== -1) {
                extencion = 'image';
            } else if (extdocumentos.indexOf(extencion) !== -1) {
                extencion = 'other';
            }
            imagenes.push({
                type: extencion,
                key: value,
                caption: value.substring(value.lastIndexOf('/') + 1),
                extra: {id: solicitud, extra: extraData}
            });
        });
        return imagenes;
    };

    //metodo encargado de generar botones de descarga por imagen
    this.getBotonesDescargaXImagen = function (urls) {
        var botones = [];
        $.each(urls, function (key, value) {
            var nombreExtencion = value.substring(value.lastIndexOf('/') + 1);
            var nombre = nombreExtencion.substring(0, nombreExtencion.lastIndexOf('.'));
            botones.push({
                '{CUSTOM_TAG_INIT}': '<button type="button" onclick="window.open(\'' + value + '\')" id="descargar-' + nombre + '" class="btn btn-xs btn-default m-r-5" title="Descargar Archivo" data-url="' + value + '" ><i class="fa fa-cloud-download"></i></button>'
            });
        });
        return botones;
    };

}

//Herencia del objeto Base
Upload.prototype = new Base();
Upload.prototype.constructor = Upload;

//Crea el File upload 
Upload.prototype.crearUpload = function () {
    var _this = this;
    var nombres = [];
    var botonosDescargas = [];
    var objeto = arguments[0];
    var url = arguments[1] || '#';
    var tiposArchivos = arguments[2] || ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    var inhabilitar = arguments[3] || false;
    var imgenesInciales = arguments[4] || [];
    var urlBorrar = arguments[5] || '';
    var solicitud = arguments[6] || null;
    var activarTotalArchivos = arguments[7] || false;
    var archivosMaximo = arguments[8] || 0;
    var sobreescribirImagen = arguments[9] || false;
    var botonGuardar = arguments[10] || false;
    var datosExtraIniciales = (arguments[11]) ? _this.setDatosExtra(arguments[11]) : false;
    var clasePreview = (arguments[12]) ? arguments[12] : false;
    var archivosMinimo = arguments[13] || 0;
    var showPreview = (arguments[14] === undefined || arguments[14] === null) ? true : false;
    if (imgenesInciales.length > 0) {
        if (imgenesInciales[0] !== '') {
            nombres = _this.getNombreImagen(imgenesInciales, solicitud, _this.getDatosExtra());
            botonosDescargas = _this.getBotonesDescargaXImagen(imgenesInciales);
        } else {
            imgenesInciales.splice(0, 1);
        }
    }

    $(objeto).fileinput({
        language: 'es',
        uploadUrl: url,
        uploadAsync: false,
        uploadExtraData: function () {
            var datos = _this.getDatosExtra();
            return datos;
        },
        allowedFileExtensions: tiposArchivos,
        showUpload: botonGuardar,
        showRemove: false,
        dropZoneEnabled: false,
        maxFileCount: archivosMaximo,
        minFileCount: archivosMinimo,
        validateInitialCount: activarTotalArchivos,
        initialPreviewAsData: true,
        initialPreview: imgenesInciales,
        initialPreviewConfig: nombres,
        autoReplace: sobreescribirImagen,
        previewClass: clasePreview,
        previewThumbTags: {
            '{CUSTOM_TAG_NEW}': '',
            '{CUSTOM_TAG_INIT}': ''
        },
        initialPreviewThumbTags: botonosDescargas,
        previewSettings: {
            image: {width: '160px', height: '160px'},
            video: {width: "160px", height: "160px"},
            other: {width: "160px", height: "160px"},
            object: {width: "160px", height: "160px"}
        },
        previewZoomButtonClasses: {
            toggleheader: 'hidden',
            fullscreen: 'hidden',
            borderless: 'hidden',
            close: 'botonCerraZomm'
        },
        previewFileIconSettings: {
            doc: '<img src="/assets/img/Iconos/word_icon.png" style="width:112px; height:144px;">',
            docx: '<img src="/assets/img/Iconos/word_icon.png" style="width:112px; height:144px;">',
            xls: '<img src="/assets/img/Iconos/excel_icon.png" style="width:112px; height:144px;">',
            xlsx: '<img src="/assets/img/Iconos/excel_icon.png" style="width:112px; height:144px;">'
        },
        layoutTemplates: {
            footer: '<div class="file-thumbnail-footer">\n' +
                    '    <div class="file-footer-caption">{caption}</div>\n' +
                    '    {actions}\n' +
                    '</div>',
            actions: '<div class="file-actions">\n' +
                    '    <div class="file-footer-buttons">\n' +
                    '        {CUSTOM_TAG_INIT}{delete}' +
                    '    </div>\n' +
                    '    <div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>\n' +
                    '    <div class="clearfix"></div>\n' +
                    '</div>'
        },
        deleteUrl: urlBorrar,
        deleteExtraData: function () {
            var datos = _this.getDatosExtra();
            return datos;
        },
        overwriteInitial: false,
        showPreview: showPreview
    }).on('filedeleted', function (event, key) {
        _this.mostrarModal('Archivo Eliminado', '<p class="text-center">Se elimino con exito el archivo.</p>');
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').empty().append('Cerrar');
    });

    if (inhabilitar) {
        $(objeto).fileinput('disable');
    }

    if (nombres.length === 0) {
        _this.limpiar(objeto);
    }
};

//Habilita el file input para poder agregar archivos
Upload.prototype.habilitar = function (objeto) {
    $(objeto).fileinput('enable');
};

//Deshabilita el file input para no agregar archivos
Upload.prototype.deshabilitar = function (objeto) {
    $(objeto).fileinput('clear').fileinput('disable');
};

//Limpiar file input
Upload.prototype.limpiar = function (objeto) {
    $(objeto).fileinput('clear');
};

//Enviando datos adicionales
Upload.prototype.enviarArchivos = function () {
    var _this = this;
    var objeto = arguments[0];
    var url = arguments[1] || '#';
    var panel = arguments[2] || false;
    var extra = arguments[3] || {};
    var callback = arguments[4] || null;
    _this.setDatosExtra(extra);
    $(objeto).on('filebatchpreupload', function (event, data, previewId, index) {
        _this.empezarCargando(panel);
    }).on('filebatchuploadsuccess', function (event, data, previewId, index) {
        _this.finalizarCargando(panel);
        _this.regresarDatos(callback, data.response);
    });
    if ($(objeto).val() !== '') {
        $(objeto).fileinput('upload');
    } else {
        _this.enviarEvento(url, extra, panel, function (respuesta) {
            _this.regresarDatos(callback, respuesta);
        });
    }
};


//Destruir file input
Upload.prototype.destruir = function (objeto) {
    $(objeto).fileinput('destroy');
};

//Destruir file input
Upload.prototype.totalFiles = function (objeto) {
    return $(objeto).fileinput('getFilesCount');
};

//Get Previews
Upload.prototype.previews = function (classPreview) {
    var archivosPreview = new Array();
    $(classPreview + " .kv-file-content > img").each(function () {
        var cadena = $(this).attr("src");
        if (cadena.indexOf('/storage') != -1) {
            archivosPreview.push(cadena);
        }
    });
    return archivosPreview;
};

Upload.prototype.descargarImagen = function (evidenciaImpericia) {
    $.each(evidenciaImpericia, function (key, value) {
        var nombreExtencion = value.substring(value.lastIndexOf('/') + 1);
        var nombre = nombreExtencion.substring(0, nombreExtencion.lastIndexOf('.'));

        //Permite descargar el archivo en otra pagina
        $('#descargar-' + nombre).off('click');
        $('#descargar-' + nombre).on('click', function () {
            var url = $(this).attr('data-url');
            window.open(url, '_blank');
        });
    });
};

Upload.prototype.verificarImagenCuadrada = function (objeto) {
    var ancho = $(objeto).width();
    var alto = $(objeto).height();
    console.log(ancho);
    console.log(alto);
    if (ancho < alto || ancho > alto) {
        return false
    } else {
        return true;
    }
};