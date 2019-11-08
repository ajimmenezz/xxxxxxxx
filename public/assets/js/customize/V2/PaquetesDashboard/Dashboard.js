
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

    setBotones(botones) {
        let _this = this;
        $.each(botones, function (key, value) {
            var clave = value.split("-");
            $(`#${value}`).on('click', function () {
                _this.peticion.enviar('panel-grafica-' + clave[2], 'Dashboard_Generico/Mostrar_Datos_Tendencia', _this.informacion, function (respuesta) {
                    $(`#grafica-${clave[2]}-1`).empty();
                    _this.datos = respuesta[clave[2]];
                    _this.setGrafica([`grafica-${clave[2]}-1`]);
                    console.log(respuesta);
                });
            });
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
                case 'VGHI':
                    this.objeto = null;
                    break;
                case 'VGIP':
                    this.objeto = null;
                    break;
                case 'VGZ':
                    this.objeto = null;
                    break;
                case 'VGTO':
                    this.objeto = null;
                    break;

                default:
                    console.log("No se encontro la clave");
                    break;
            }
            _this.objetos[value].inicilizarGrafica({
                curveType: 'function',
                pointSize: 10,
                tooltip: {
                    trigger: 'none'
                }
            });
        });
    }

}