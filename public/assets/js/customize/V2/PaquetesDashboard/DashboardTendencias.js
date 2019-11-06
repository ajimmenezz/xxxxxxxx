
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT', 'select-tiempo-VGT', 'select-lapso-VGT'],
            graficas: ['grafica-VGT-1']
        };
        this.informacion = {};
        this.peticion = new Utileria();
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
                case 'select-lapso-VGT':
                    _this.eventoSelectLapso(value);
                    break;
            }
        });
    }

    eventoSelectCliente(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['cliente'] = select.obtenerValor();
            _this.peticion.enviar('grafica-VGT-1', 'Dashboard_Generico/Mostrar_Graficas', _this.informacion, function (respuesta) {
                console.log(_this.informacion);
                console.log(respuesta);
            });
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            $('#select-lapso-VGT').prop("disabled", false);
            _this.informacion['tiempo'] = select.obtenerValor();
            let lapso = null;
            switch (_this.informacion['tiempo']) {
                case 'WEEK':
                    lapso = [3, 4, 5, 6, 7, 8, 9];
                    break;
                case 'MONTH':
                    lapso = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                    break;
                case 'YEAR':
                    lapso = [2, 3, 4];
                    break;
            }
            select.cargaDatosEnSelect(lapso, 'select-lapso-VGT')
        });
    }

    eventoSelectLapso(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['lapso'] = select.obtenerValor();
            _this.peticion.enviar('', 'Dashboard_Generico/Mostrar_Graficas', _this.informacion, function (respuesta) {
                console.log(respuesta);
            });
        });
    }

}
