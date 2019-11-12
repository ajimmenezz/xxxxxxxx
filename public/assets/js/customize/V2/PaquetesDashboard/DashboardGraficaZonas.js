class DashboardGraficaZonas extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGZ';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGZ', 'select-zona-VGZ'],
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
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-zona-VGZ':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }
    
    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGZ', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGZ-1`).empty();
                _this.datos = respuesta['VGZ'];
                _this.setGrafica([`grafica-VGZ-1`]);
            });
        });
    }

    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGZ', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGZ-1`).empty();
                _this.datos = respuesta['VGZ'];
                _this.setGrafica([`grafica-VGZ-1`]);
            });
        });
    }
}