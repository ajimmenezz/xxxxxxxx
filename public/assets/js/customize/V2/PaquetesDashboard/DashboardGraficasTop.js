class DashboardGraficasTop extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGTO';
        this.datos = datos;
        this.componentes = {
            selects: ['select-zona-VGTO'],
            graficas: ['grafica-VGTO-1']
        };
        this.informacion = {
            clave: "VGTO"
        };
    }
}