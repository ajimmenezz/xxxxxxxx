
class DashboardTendencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGT';
        this.datos = datos;
        this.componentes = {
            selects: ['select-cliente-VGT', 'select-tiempo-VGT'],
            botones: ['btn-actualizar-VGT'],
            graficas: ['grafica-VGT-1']
        };
        this.informacion = {};
        this.peticion = new Utileria();
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
            }
        });
    }

    eventoSelectCliente(select) {
        let _this = this;
        select.evento('change', function () {
             _this.informacion['cliente']= select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGT', 'Dashboard_Generico/Mostrar_Datos_Tendencia',  _this.informacion, function (respuesta) {
                console.log(respuesta);
            });
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            $('#select-lapso-VGT').prop("disabled", false);
            _this.informacion['tiempo'] = select.obtenerValor();
        });
    }

}
