
class Dashboard {

    constructor(clave) {
        this.clave = clave;
        this.datos = {};
        this.componentes = {};
        this.objetos = {};
        this.peticion = new Utileria();
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
            switch (value) {
                case 'grafica-VGT-1':
                    _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'LineChart', true);
                    break;
                case 'grafica-VGC-1':
                    _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'LineChart', true);
                    break;
                case 'grafica-VGIP-1':
                    _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'LineChart', true);
                    break;
                case 'grafica-VGZ-1':
                    _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'ColumnChart', true);
                    break;
                case 'grafica-VGTO-1':
                    _this.objetos[value] = new GraficaGoogle(value, _this.datos, 'ColumnChart', true);
                    break;

                default:
                    console.log("No se encontro la clave de grafica");
                    break;
            }
            _this.objetos[value].inicilizarGrafica({
                curveType: 'function',
                pointSize: 10,
            });
        });
    }

}