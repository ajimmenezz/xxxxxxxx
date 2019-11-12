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
    
    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-tipo-VGTO':
                    _this.eventoSelectTipo(value);
                    break;
                case 'select-tiempo-VGTO':
                    _this.eventoSelectTiempo(value);
                    break;
                case 'select-lapso-VGTO':
                    _this.eventoSelectLapso(value);
                    break;
                case 'select-zona-VGTO':
                    _this.eventoSelectZona(value);
                    break;
            }
        });
    }
    
    eventoSelectTipo(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['reportType'] = select.obtenerValor();
            _this.peticion.enviar('panel-grafica-VGTO', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGTO-1`).empty();
                _this.datos = respuesta['VGTO'];
                _this.setGrafica([`grafica-VGTO-1`]);
            });
        });
    }
    
    eventoSelectTiempo(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            $('#select-lapso-VGTO').attr('disabled', false);
            switch (select.obtenerValor()) {
                case 'WEEK':
                    let semanas = [];
                    for (var i = 1; i < 53; i++) {
                        semanas.push(i);
                    }
                    _this.objetos['select-lapso-VGTO'].cargaDatosEnSelect(semanas);
                    break;
                case 'MONTH':
                    _this.objetos['select-lapso-VGTO'].cargaDatosEnSelect([{id: 1, text: 'Enero'},{id: 2, text: 'Febrero'},{id: 3, text: 'Marzo'}
                        ,{id: 4, text: 'Abril'},{id: 5, text: 'Mayo'},{id: 6, text: 'Junio'},{id: 7, text: 'Julio'},{id: 8, text: 'Agosto'},{id: 9, text: 'Septiembre'},{id: 10, text: 'Octubre'},{id: 11, text: 'Noviembre'},{id: 12, text: 'Diciembre'}]);
                    break;
            }
        });
    }
    
    eventoSelectLapso(select){
        let _this = this;
        select.evento('change', function () {
            if (_this.informacion['tiempo'] == 'WEEK') {
                _this.informacion['week'] = select.obtenerValor();
            } else {
                _this.informacion['month'] = select.obtenerValor();
            }
            _this.peticion.enviar('panel-grafica-VGTO', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGTO-1`).empty();
                _this.datos = respuesta['VGTO'];
                _this.setGrafica([`grafica-VGTO-1`]);
            });
        });
    }
    
    eventoSelectZona(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerTexto();
            _this.peticion.enviar('panel-grafica-VGTO', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGTO-1`).empty();
                _this.datos = respuesta['VGTO'];
                _this.setGrafica([`grafica-VGTO-1`]);
            });
        });
    }
}