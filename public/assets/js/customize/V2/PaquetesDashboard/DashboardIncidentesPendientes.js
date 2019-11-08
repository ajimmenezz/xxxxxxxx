class DashboardIncidentesPendientes extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGIP';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGIP'],
            graficas: ['grafica-VGIP-1']
        };
        this.informacion = {
            clave: "VGIP"
        };
    }
    
    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-tiempo-VGIP':
                    break;
            }
        });
    }
    
    
}