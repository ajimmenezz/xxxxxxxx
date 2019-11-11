class DashboardHistoricoIncidencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGHI';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGHI', 'select-lapso-VGHI', 'select-zona-VGHI'],
            graficas: ['grafica-VGHI-1']
        };
        this.informacion = {
            clave: "VGHI"
        };
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-tiempo-VGHI':
                    _this.eventoSelectTiempoVGHI(value);
                    break;
                case 'select-lapso-VGHI':
                    _this.eventoSelectLapso(value);
                    break;
                case 'select-zona-VGHI':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            $('#select-lapso-VGHI').attr('disabled', false);

        });
    }

    eventoSelectLapso(select) {
        let _this = this;
        select.evento('change', function () {

        });
    }

    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGHI', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                console.log(respuesta);
//                $(`#grafica-VGC-1`).empty();
//                _this.datos = respuesta['VGC'];
//                _this.setGrafica([`grafica-VGC-1`]);
//                _this.setDatosTabla(_this.objetos['tabla-VGC']);
            });
        });
    }
}