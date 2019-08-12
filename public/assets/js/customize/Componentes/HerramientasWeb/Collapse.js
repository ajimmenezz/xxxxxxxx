class Collapse {
    
    constructor(nombreCollapse) {
        this.collapse = nombreCollapse;
        this.objetoCollapse = $(`#${this.collapse}`);
    }
    
    iniciarCollapse(nombre, contenido, href = 'one'){
        this.objetoCollapse.append('<div class="panel panel-inverse overflow-hidden">\n\
                                        <div class="panel-heading">\n\
                                            <h3 class="panel-title">\n\
                                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion" href="#'+href+'">\n\
                                                    <i class="fa fa-plus-circle pull-right"></i> \n\
                                                    '+nombre+'\n\
                                                </a>\n\
                                            </h3>\n\
                                        </div>\n\
                                        <div id="'+href+'" class="panel-collapse collapse">\n\
                                            <div class="panel-body">\n\
                                                '+contenido+'\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>');
    }
    
    multipleCollapse(datos = []){
        let _this = this;
        let contador = 0;

        $.each(datos, function (key, valor) {
            _this.iniciarCollapse(valor.titulo, valor.contenido, 'collapse'+contador);
            contador++;
        });
    }
}