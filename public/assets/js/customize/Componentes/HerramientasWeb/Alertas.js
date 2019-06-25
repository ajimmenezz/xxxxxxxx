class Alertas {

    constructor(nombre) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
    }

    iniciarAlerta() {
        var id = arguments[0];
        var contenido = arguments[1] || '&nbsp;';
        var atributos = arguments[2] || null;
        var colorAlert = arguments[3] || 'info';
        var colorTexto = arguments[4] || 'black';
        var icono = arguments[5] || 'fa-times';
        var span = '';
        
        if(atributos !== null){
            span = '<span class="close" \n\
                            data-dismiss="alert" \n\
                            ' + atributos + '><i class="fa '+icono+'"></i>\n\
                        </span>';
        }
        
        this.objeto.append('<div id="' + id + '" \n\
                                class="alert alert-' + colorAlert + ' fade in m-b-12 remover" \n\
                                style="color: ' + colorTexto + '">\n\
                            ' + contenido + '\n\
                            ' + span + '\n\
                        </div>');
    }

    quitarAlert() {
        $(".remover").remove();
    }
}