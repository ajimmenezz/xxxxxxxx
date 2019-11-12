class DashboardGraficasTop extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGTO';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tipo-VGTO','select-tiempo-VGTO','select-lapso-VGTO','select-zona-VGTO'],
            graficas: ['grafica-VGTO-1']
        };
        this.informacion = {
            clave: "VGTO"
        };
    }
}