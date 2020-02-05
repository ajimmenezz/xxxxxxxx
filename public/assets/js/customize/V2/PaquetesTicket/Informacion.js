class Informacion {

    constructor() {
        this.formulario = null;
        this.datos = null;
        this.selects = {};
        this.inputs = {};
    }

    iniciarElementos() {
        this.crearSelects();
        this.crearInputs();
    }

    crearSelects() {
        let _this = this;
        let selects = [
            'selectCliente',
            'selectSucursal'
        ];
        $.each(selects, function (index, value) {
            _this.selects[value] = new SelectBasico(value);
        });

        $.each(_this.selects, function (index, value) {
            value.iniciarSelect();
        });

    }

    crearInputs() {
        let _this = this;
        let inputs = [
            'solicitud',
            'ticket',
            'solicita',
            'atiende',
            'fechaSolicitud',
            'folio',
            'servicio',
            'fechaCreacion',
            'fechaInicio'
        ];

        $.each(inputs, function (index, value) {
            _this.inputs[value] = new Input(value);
        });
    }

    setDatos(datos) {
        this.datos = datos;
        this.setDatosSelect(datos);
        this.setDatosInputs(datos.servicio);
    }

    setDatosSelect(datos) {
        let temporal = [];

        $.each(datos.clientes, function (index, value) {
            temporal.push({id: value.Id, text: value.Nombre});
        });

        this.selects["selectCliente"].cargaDatosEnSelect(temporal);
    }

    setDatosInputs(datos) {
        let _this = this;
        $.each(datos, function (index, value) {
            if (_this.inputs.hasOwnProperty(index)) {
                _this.inputs[index].definirValor(value);
            }
        });
    }

    listener(callback) {
        let dato = {};
        let _this = this;
        _this.selects["selectCliente"].evento('change', function () {
            dato = {algo: 'valor'};
            _this.selects["selectCliente"].cargarElementosASelect('selectSucursal', _this.datos.sucursales, 'cliente');
            callback(dato);
        });
    }
}

