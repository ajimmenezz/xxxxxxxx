class ISelect {

    constructor(nombreSelect) {
        this.select = nombreSelect;
        this.objetoSelect = $(`#${this.select}`);
//        this.iniciarSelect();
    }

    iniciarSelect() {
        this.objetoSelect.select2();
    }

    limpiarElemento() {
        this.objetoSelect.select2().val(null).trigger('change');
    }

    obtenerValor() {
        return this.objetoSelect.val();
    }

    bloquearElemento() {
        this.objetoSelect.attr('disabled', 'disabled');
    }

    habilitarElemento() {
        this.objetoSelect.removeAttr('disabled');
    }

    definirValor(valor = '') {
        this.objetoSelect.select2().val(valor).trigger('change');
    }

    iniciarPlugin() {
        this.objetoSelect = $(`#${this.select}`);
        this.iniciarSelect();
    }

    cargaDatosEnSelect(datos = [], elemento = '') {

        let objeto;

        if (elemento !== '') {
            objeto = $(`#${elemento}`);
        } else {
            objeto = this.objetoSelect;
        }

        objeto.empty().append('<option value="">Seleccionar</option>');
        objeto.select2({
            data: datos
        });
    }

    cargarElementosASelect(elemento = '', contenido = [], comparacion = '') {

        let _this = this;
        let datos = [];
        let contador = 0;
        let seleccion = _this.objetoSelect.val();

        $.each(contenido, function (key, valor) {
            if (seleccion === valor[comparacion]) {
                datos[contador] = {id: valor.id, text: valor.text};
                contador++;
            }
        });

        this.cargaDatosEnSelect(datos, elemento);
    }

    evento(evento = '', callback) {
        this.objetoSelect.on(evento, callback);
    }

    obtenerDatosSeleccionado() {
        return this.objetoSelect.select2('data');
    }
    
    obtenerTexto(){
        return $(this.objetoSelect + ' option:selected').html()
    }

}


