
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT', 'select-tiempo-VGT', 'select-zona-VGT'],
            botones: ['btn-actualizar-VGT'],
            graficas: ['grafica-VGT-1']
        };
        this.informacion = {
            clave: "VGT",
            cliente: "1",
            tiempo: "WEEK",
            zona: ""
        };
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-cliente-VGT':
                    _this.eventoSelectCliente(value);
                    break;
                case 'select-tiempo-VGT':
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-zona-VGT':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }

    eventoSelectCliente(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['cliente'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                console.log(respuesta);
            });
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                console.log(respuesta);
            });
        });
    }
    
    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
        });
    }


}
