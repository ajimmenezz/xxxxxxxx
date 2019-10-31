
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT'],
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

                default:

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

}


