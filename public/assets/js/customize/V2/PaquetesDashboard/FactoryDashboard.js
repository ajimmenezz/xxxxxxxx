
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
                this.objeto = new DashboardHistoricoIncidencias(clave, datos);
                break;
            case 'VGIP':
                this.objeto = new DashboardIncidentesPendientes(clave, datos);
                break;
            case 'VGZ':
                this.objeto = new DashboardGraficaZonas(clave, datos);
                break;
            case 'VGTO':
//                this.objeto = new DashboardGraficasTop(clave, datos);
                break;
            case 'clientes':
                let select = new SelectBasico();
                select.cargaDatosEnSelect(datos, 'select-cliente-VGT');
                break;
            case 'tipoServicios':
                let selectServicios = new SelectBasico();
                selectServicios.cargaDatosEnSelect(datos, 'select-servicio-VGC');
                break;

            default:
                console.log("No se encontro la clave");
                break;
        }
        return this.objeto;
    }
}
