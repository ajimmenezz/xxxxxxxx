
class FactoryDashboard {

    constructor() {
        this.objeto = null;
    }

    getInstance(clave, datos = {}) {
        switch (clave) {
            case 'VGT':                
                this.objeto = new DashboardTendencias(clave, datos);
                break;

            default:

                break;
        }
        return this.objeto;
    }
}
