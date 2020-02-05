class Informacion {

    constructor() {
        this.formulario = null;
        this.selects = {};
        this.inputs = {};
    }

    iniciarElementos() {
        this.crearSelects();
    }

    crearSelects() {
        let _this = this;
        let selects = [
            'selectClienteInformacionGeneral',
            'selectSucursalInformacionGeneral'
        ];
        $.each(selects, function (index, value) {
            console.log(value);
            _this.selects[value] = new SelectBasico(value);
        });

        $.each(_this.selects, function (index, value) {
            value.iniciarSelect();
        });
    }   

    colocandoDatosSelects(datosServicio) {
        let _this = this;

        if (datosServicio.sucursales.length > 0) {
            _this.selects.selectSucursalInformacionGeneral.cargaDatosEnSelect(datosServicio.sucursales, 'selectSucursalInformacionGeneral');
        }
    }
}

