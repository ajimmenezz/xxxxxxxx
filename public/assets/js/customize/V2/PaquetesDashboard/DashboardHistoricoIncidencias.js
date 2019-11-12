class DashboardHistoricoIncidencias extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGHI';
        this.datos = datos;
        this.componentes = {
            selects: ['select-tiempo-VGHI', 'select-lapso-VGHI', 'select-zona-VGHI', 'select-year-VGHI']
        };
        this.informacion = {
            clave: "VGHI"
        };
        this.objetos['tablaVGHI'] = new TablaBasica('tabla-VGHI');
        let temp = this.objetos['tablaVGHI'];
        $.each(this.datos, function (key, value) {
            temp.agregarDatosFila([
                value.year,
                value.concept,
                value.total
            ]);
        });
    }

    setEvento() {
        let _this = this;
        $.each(this.objetos, function (key, value) {
            switch (key) {
                case 'select-year-VGHI':
                    _this.eventoSelectYear(value);
                    break;
                case 'select-tiempo-VGHI':
                    _this.eventoSelectTiempo(value);
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

    eventoSelectYear(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['year'] = select.obtenerValor();
            _this.cambiarTablaHistorico();
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tiempo'] = select.obtenerValor();
            $('#select-lapso-VGHI').attr('disabled', false);
            switch (select.obtenerValor()) {
                case 'WEEK':
                    let semanas = [];
                    for (var i = 1; i < 53; i++) {
                        semanas.push(i);
                    }
                    _this.objetos['select-lapso-VGHI'].cargaDatosEnSelect(semanas);
                    break;
                case 'MONTH':
                    _this.objetos['select-lapso-VGHI'].cargaDatosEnSelect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
                    break;
            }
        });
    }

    eventoSelectLapso(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['week'] = select.obtenerValor();
            _this.cambiarTablaHistorico();
        });
    }

    eventoSelectZona(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['zona'] = select.obtenerValor();
            _this.cambiarTablaHistorico();
        });
    }
    
    cambiarTablaHistorico(){
        let _this = this;
        _this.peticion.enviar('panel-grafica-VGHI', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                _this.objetos.tablaVGHI.limpiartabla();
                $.each(respuesta.VGHI, function (key, value) {
                    _this.objetos.tablaVGHI.agregarDatosFila([
                        value.year,
                        value.concept,
                        value.total
                    ]);
                });
            });
    }
}