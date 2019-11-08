class DashboardGraficaZonas extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGZ';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGZ'],
            graficas: ['grafica-VGZ-1']
        };
        this.informacion = {
            clave: "VGZ"
        };
    }
    
    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-tiempo-VGZ':
                    console.log("evento select VGZ");
                    break;
            }
        });
    }
}