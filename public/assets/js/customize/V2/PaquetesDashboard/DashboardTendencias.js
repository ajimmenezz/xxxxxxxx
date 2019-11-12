
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT', 'select-tiempo-VGT', 'select-numero-VGT', 'select-zona-VGT'],
            graficas: ['grafica-VGT-1']
        };
        this.informacion = {
            clave: "VGT",
            cliente: "1",
            tiempo: "WEEK",
            zona: ""
        };
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-cliente-VGT':
                    _this.eventoSelectCliente(value);
                    break;
                case 'select-tiempo-VGT':
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-numero-VGT':
                    _this.eventoSelectNumero(value);
                    break;
                case 'select-zona-VGT':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }

    eventoSelectCliente(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['cliente'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGT-1`).empty();
                _this.datos = respuesta['VGT'];
                _this.setGrafica([`grafica-VGT-1`]);
            });
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            if (select.obtenerValor() != "") {
                _this.informacion['tiempo'] = select.obtenerValor();
                $('#select-numero-VGT').attr('disabled', false);
                switch (select.obtenerValor()) {
                    case 'WEEK':
                        let semanas = [];
                        for (var i = 1; i < 53; i++) {
                            semanas.push(i);
                        }
                        _this.objetos['select-numero-VGT'].cargaDatosEnSelect(semanas);
                        break;
                    case 'MONTH':
                        _this.objetos['select-numero-VGT'].cargaDatosEnSelect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
                        break;
                }
            }
        });
    }

    eventoSelectNumero(select) {
        let _this = this;
        select.evento('change', function () {
            if (_this.informacion['tiempo'] == 'WEEK') {
                _this.informacion['week'] = select.obtenerValor();
            } else {
                _this.informacion['month'] = select.obtenerValor();
            }
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGT-1`).empty();
                _this.datos = respuesta['VGT'];
                _this.setGrafica([`grafica-VGT-1`]);
            });
        });
    }

    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGT-1`).empty();
                _this.datos = respuesta['VGT'];
                _this.setGrafica([`grafica-VGT-1`]);
            });
        });
    }

}
