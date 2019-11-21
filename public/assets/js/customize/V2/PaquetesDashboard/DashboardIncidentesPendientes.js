class DashboardIncidentesPendientes extends Dashboard{
    
    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGIP';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGIP', 'select-numero-VGIP', 'select-zona-VGIP'],
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
                case 'select-numero-VGIP':
                    _this.eventoSelectNumero(value);
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
            if (select.obtenerValor() != "") {
                _this.informacion['tiempo'] = select.obtenerValor();
                $('#select-numero-VGIP').attr('disabled', false);
                switch (select.obtenerValor()) {
                    case 'WEEK':
                        let semanas = [];
                        for (var i = 1; i < 53; i++) {
                            semanas.push(i);
                        }
                        _this.objetos['select-numero-VGIP'].cargaDatosEnSelect(semanas);
                        break;
                    case 'MONTH':
                        _this.objetos['select-numero-VGIP'].cargaDatosEnSelect([{id: 1, text: 'Enero'},{id: 2, text: 'Febrero'},{id: 3, text: 'Marzo'}
                        ,{id: 4, text: 'Abril'},{id: 5, text: 'Mayo'},{id: 6, text: 'Junio'},{id: 7, text: 'Julio'},{id: 8, text: 'Agosto'},{id: 9, text: 'Septiembre'},{id: 10, text: 'Octubre'},{id: 11, text: 'Noviembre'},{id: 12, text: 'Diciembre'}]);
                        break;
                }
            }
        });
    }

    eventoSelectNumero(select) {
        let _this = this;
        select.evento('change', function () {
            if (_this.informacion['tiempo'] == 'WEEK') {
                _this.informacion['week'] = select.obtenerValor();
            } else {
                _this.informacion['month'] = select.obtenerValor();
            }
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
                _this.datos = respuesta['VGIP'];
                _this.setGrafica([`grafica-VGIP-1`]);
            });
        });
    }
}