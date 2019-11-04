
class Dashboard {

    constructor(clave) {
        this.clave = clave;
        this.datos = {};
        this.componentes = {};
        this.objetos = {};
    }

    setComponentes() {
        let _this = this;

        $.each(this.componentes, function (key, value) {
            switch (key) {
                case 'selects':
                    _this.setSelect(value);
                    break;
                case 'graficas':
                    _this.setGrafica(value);
                    break;
            }

        });
    }

    setSelect(selects) {
        let _this = this;
        $.each(selects, function (key, value) {
            _this.objetos[value] = new SelectBasico(value);
            _this.objetos[value].iniciarSelect();
        });
    }

    setGrafica(graficas) {
        let _this = this;
        $.each(graficas, function (key, value) {
            _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'LineChart');
            _this.objetos[value].inicilizarGrafica();
        });
    }

}