class Formulario {

    constructor(nombreFormulario, elementos) {

        this.formulario = nombreFormulario;
        this.selects = new Map();
        this.fechas = new Map();
        this.filesUpload = new Map();
        this.inputs = new Map();        

        elementos.hasOwnProperty('selects') ? this.crearSelects(elementos.selects) : null;
        elementos.hasOwnProperty('fechas') ? this.crearFechas(elementos.fechas) : null;
        elementos.hasOwnProperty('filesUpload') ? this.crearFileUpload(elementos.filesUpload) : null;
        elementos.hasOwnProperty('inputs') ? this.crearInputs(elementos.inputs) : null;

    }

    crearSelects(objetos) {

        let selects = this.selects;

        $.each(objetos, function (key, value) {

            let select;

            switch (value) {
                case 'basico':
                    select = new SelectBasico(key);
                    break;
                case 'multiple':
                    select = new SelectMultiple(key);
                    break;
            }

            selects.set(key, select);
        });
    }

    crearFechas(objetos) {

        let fechas = this.fechas;

        $.each(objetos, function (key, value) {
            fechas.set(key, new Fecha(key));
        });

    }

    crearFileUpload(objetos) {
        let _this = this;
        let fileUpload = this.filesUpload;

        $.each(objetos, function (key, value) {
            let upload;

            switch (value.tipo) {
                case 'basico':
                    upload = new FileUpload_Basico(key, value);
                    break;
            }

            fileUpload.set(key, upload);
        });
    }

    crearInputs(objetos) {

        let inputs = this.inputs;

        $.each(objetos, function (key, value) {
            inputs.set(key, new Input(key));
        });
    }

    limpiarElementos() {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            objeto.forEach((objeto, key, map) => {
                objeto.limpiarElemento();
            });
        }
        $(`#${this.formulario}`).parsley().reset();
    }

    limpiarElemento(elemento = '') {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                let elementoObjeto = objeto.get(elemento);
                elementoObjeto.limpiarElemento();
            }
    }
    }

    validarFormulario() {

        let formulario = $(`#${this.formulario}`);

        formulario.parsley().validate();

        if (formulario.parsley().isValid() === false) {
            throw "Faltan campos por llenar";
        } else {
            return this.obtenerDatosFormulario();
        }
    }

    obtenerDatosFormulario() {

        let objetos = [this.selects, this.fechas, this.inputs];
        let datos = {}, selectTexto = {};

        for (let objeto of objetos) {
            objeto.forEach((objeto, key, map) => {
                datos[key] = objeto.obtenerValor();
                if (objeto instanceof SelectBasico) {
                    selectTexto[key] = objeto.obtenerTexto();
                }
            });
        }
        datos['selectTexto'] = selectTexto;
        return datos;
    }

    obtenerDato(elemento = '') {

        let objetos = [this.selects, this.fechas, this.inputs];
        let valor;

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                let elementoObjeto = objeto.get(elemento);
                valor = elementoObjeto.obtenerValor();
            }
        }

        return valor;
    }

    bloquearFormulario() {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            objeto.forEach((objeto, key, map) => {
                objeto.bloquearElemento();
            });
        }
    }

    habilitarFormulario() {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            objeto.forEach((objeto, key, map) => {
                objeto.habilitarElemento();
            });
        }
    }

    asignarValorElemento(elemento = '', valor = '') {

        let objetos = [this.selects, this.fechas, this.inputs];

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                let elementoObjeto = objeto.get(elemento);
                valor = elementoObjeto.definirValor(valor);
            }
    }

    }

    validarExistenciaElemento(elemento = '') {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];
        let existe = false;

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                existe = true;
            }
        }
        return existe;
    }

    iniciarElemento(elemento = '') {
        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                let elementoObjeto = objeto.get(elemento);
                elementoObjeto.iniciarPlugin();
            }
        }
    }

    obtenerElemento(elemento = '') {

        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];
        let elementoObjeto;

        for (let objeto of objetos) {
            if (objeto.has(elemento)) {
                elementoObjeto = objeto.get(elemento);
            }
        }

        return elementoObjeto;
    }

    iniciarElementos() {
        let objetos = [this.selects, this.fechas, this.inputs, this.filesUpload];

        for (let objeto of objetos) {
            objeto.forEach((value, key, map) => {
                value.iniciarPlugin();
            });
        }
    }
}
