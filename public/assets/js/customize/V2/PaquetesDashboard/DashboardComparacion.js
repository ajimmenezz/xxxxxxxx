class DashboardComparacion extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGC';
        this.datos = datos;
        this.componentes = {
            selects: ['select-servicio-VGC', 'select-tiempo-VGC', 'select-lapso-VGC'],
            graficas: ['grafica-VGC-1']
        };
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-servicio-VGC':
                    _this.eventoSelectServicio(value);
                    break;

                default:

                    break;
            }
        });
    }

    eventoSelectServicio(select) {
        let valor = select.obtenerValor();
        select.evento('change', function () {
            valor = select.obtenerValor();
            console.log(valor);            
        });
    }

}


