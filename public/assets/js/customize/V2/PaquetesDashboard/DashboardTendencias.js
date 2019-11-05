
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT', 'select-tiempo-VGT', 'select-lapso-VGT'],
            graficas: ['grafica-VGT-1']
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
                case 'select-lapso-VGT':
                    _this.eventoSelectLapso(value);
                    break;
            }
        });
    }

    eventoSelectCliente(select) {
        let valor = select.obtenerValor();
        
        select.evento('change', function () {
            valor = select.obtenerValor();
            console.log(valor);
        });
    }

    eventoSelectTiempo(select) {
        let valor = select.obtenerValor();
        
        select.evento('change', function () {
            $('#select-actual-VGT').prop("disabled", false);
            valor = select.obtenerValor();
            console.log(valor);
        });
    }
    
    eventoSelectLapso(select) {
        let valor = select.obtenerValor();
        
        select.evento('change', function () {
            valor = select.obtenerValor();
            console.log(valor);
        });
    }

}
