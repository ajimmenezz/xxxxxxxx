class DashboardComparacion extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGC';
        this.datos = datos;
        this.componentes = {
            selects: ['select-servicio-VGC', 'select-tiempo-VGC', 'select-zona-VGC'],
            tablas: ['tabla-VGC'],
            graficas: ['grafica-VGC-1']
        };
        this.informacion = {
            clave: "VGC",
            tipoServicio: "",
            tiempo: "WEEK",
            zona: ""
        };
        let pruebaTabla = new TablaBasica('tabla-VGC');
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-servicio-VGC':
                    _this.eventoSelectServicio(value);
                    break;
                case 'select-tiempo-VGC':
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-zona-VGC':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }

    eventoSelectServicio(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tipoServicio'] = select.obtenerTexto();
            _this.peticion.enviar('panel-grafica-VGC', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                console.log(respuesta);
                $(`#grafica-VGC-1`).empty();
                _this.datos = respuesta['VGC'];
                _this.setGrafica([`grafica-VGC-1`]);
            });
        });
    }
    
    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGC', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGC-1`).empty();
                _this.datos = respuesta['VGC'];
                _this.setGrafica([`grafica-VGC-1`]);
            });
        });
    }
    
    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGC', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGC-1`).empty();
                _this.datos = respuesta['VGC'];
                _this.setGrafica([`grafica-VGC-1`]);
            });
        });
    }

}


