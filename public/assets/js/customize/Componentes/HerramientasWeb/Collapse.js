class Collapse {

    constructor(nombreCollapse) {
        this.collapse = nombreCollapse;
        this.objetoCollapse = $(`#${this.collapse}`);
    }

    iniciarCollapse(nombre, contenido, href = 'one') {
        this.objetoCollapse.append('<div class="panel panel-inverse overflow-hidden">\n\
                                        <div class="panel-heading">\n\
                                            <h3 class="panel-title">\n\
                                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#' + href + '">\n\
                                                    <i class="fa fa-plus-circle pull-right"></i> \n\
                                                    ' + nombre + '\n\
                                                </a>\n\
                                            </h3>\n\
                                        </div>\n\
                                        <div id="' + href + '" class="panel-collapse collapse">\n\
                                            <div class="panel-body">\n\
                                                ' + contenido + '\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>');
    }

    multipleCollapse(datos = []) {
        let _this = this;
        let contador = 0;

        $.each(datos, function (key, valor) {
            _this.iniciarCollapse(valor.titulo, valor.contenido, 'collapse' + contador);
            contador++;
        });
    }

    iniciarCardMedia(informacion) {
        let html = '';
        let evidencias = null;

        if (informacion.evidencias !== null) {
            evidencias = informacion.evidencias.split(',');
            $.each(evidencias, function (key, value) {

                if (value !== '') {
                    var ext = value.substring(value.lastIndexOf("."));

                    if (ext !== '.png' && ext !== '.jpeg' && ext !== '.jpg' && ext !== '.gif') {
                        value = '/assets/img/Iconos/no-thumbnail.jpg';
                    }

                    html += '<div class="col-md-3 col-sm-3 col-xs-3">\n\
                                <div id="img" class="evidencia">\n\
                                    <a href="..' + value + '" target="_blank">\n\
                                        <img src ="..' + value + '" />\n\
                                    </a>\n\
                                </div>\n\
                            </div>';
                }
            });
        }

        this.objetoCollapse.append('<li class="media media-sm">\n\
                                        <div class="media-body">\n\
                                            <div class="row">\n\
                                                <div class="col-md-9 col-sm-6 col-xs-12">\n\
                                                    <h1 class="page-header">' + informacion.titulo + ' <a href="javascript:;" id="' + informacion.href + '" data-key="' + informacion.href + '" class="btn btn-sm btn-primary m-r-5 cardUtileria">' + informacion.accion + '</a></h1>\n\
                                                </div>\n\
                                                <div class="col-md-3 col-sm-6 col-xs-12 text-right cambioVistas hidden">\n\
                                                    <label>' + informacion.subtitulo + '</label>\n\
                                                </div>\n\
                                            </div>\n\
                                            <p>' + informacion.contenido + '</p>\n\
                                            ' + html + '\n\
                                        </div>\n\
                                    </li>');
    }

    multipleCardMedia(datos = []) {
        let _this = this;
        let infoCard = null;

        $.each(datos, function (key, valor) {
            infoCard = {
                titulo: valor.titulo,
                contenido: valor.contenido,
                href: 'card-' + key,
                evidencias: valor.evidencias,
                subtitulo: valor.fecha,
                accion: valor.boton
            }
            _this.iniciarCardMedia(infoCard);
        });
    }

    limpiarCollapse() {
        this.objetoCollapse.empty();
    }
}