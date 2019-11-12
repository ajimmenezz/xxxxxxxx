class DashboardIncidentesPendientes extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGIP';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGIP', 'select-zona-VGIP'],
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
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-zona-VGIP':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }
    
    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGIP', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGIP-1`).empty();
                _this.datos = respuesta['VGIP'];
                _this.setGrafica([`grafica-VGIP-1`]);
            });
        });
    }

    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGIP', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGIP-1`).empty();
                console.log(respuesta);
                _this.datos = respuesta['VGIP'];
                _this.setGrafica([`grafica-VGIP-1`]);
            });
        });
    }
}