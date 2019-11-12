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
            _this.informacion['tipoReporte'] = select.obtenerTexto();
            console.log(_this.informacion);
        });
    }
    
    eventoSelectTiempo(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerTexto();
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
                    _this.objetos['select-lapso-VGTO'].cargaDatosEnSelect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
                    break;
            }
        });
    }
    
    eventoSelectLapso(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['lapso'] = select.obtenerTexto();
            console.log(_this.informacion);
        });
    }
    
    eventoSelectZona(select){
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerTexto();
            _this.peticion.enviar('panel-grafica-VGTO', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGTO-1`).empty();
                console.log(respuesta);
                _this.datos = respuesta['VGTO'];
                _this.setGrafica([`grafica-VGTO-1`]);
            });
        });
    }
}