
class FactoryDashboard {

    constructor() {
        this.objeto = null;
    }

    getInstance(clave, datos = {}) {
        switch (clave) {
            case 'VGT':                
                this.objeto = new DashboardTendencias(clave, datos);
                break;
            case 'VGC':                
                this.objeto = new DashboardComparacion(clave, datos);
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
        return this.objeto;
    }
}
