class DashboardComparacion extends Dashboard {

    constructor(clave, datos) {
        super(clave);
        this.panel = 'panel-grafica-VGC';
        this.datos = datos;
        this.componentes = {
            selects: ['select-servicio-VGC', 'select-tiempo-VGC', 'select-numero-VGC', 'select-zona-VGC'],
            tablas: ['tabla-VGC'],
            graficas: ['grafica-VGC-1']
        };
        this.informacion = {
            clave: "VGC",
            tipoServicio: "",
            tiempo: "WEEK",
            zona: ""
        };
        this.objetos['tabla-VGC'] = new TablaBasica('tabla-VGC');
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
                case 'select-numero-VGC':
                    _this.eventoSelectNumero(value);
                    break;
                case 'select-zona-VGC':
                    _this.eventoSelectZona(value);
                    break;
                case 'tabla-VGC':
                    _this.setDatosTabla(value);
                    break;
            }
        });
    }

    eventoSelectServicio(select) {
        let _this = this;
        select.evento('change', function () {
            _this.informacion['tipoServicio'] = select.obtenerTexto();
            _this.peticion.enviar('panel-grafica-VGC', 'Dashboard_Generico/Mostrar_Datos_Actualizados', _this.informacion, function (respuesta) {
                $(`#grafica-VGC-1`).empty();
                _this.datos = respuesta['VGC'];
                _this.setGrafica([`grafica-VGC-1`]);
                _this.setDatosTabla(_this.objetos['tabla-VGC']);
            });
        });
    }

    eventoSelectTiempo(select) {
        let _this = this;
        select.evento('change', function () {
            if (select.obtenerValor() != "") {
                _this.informacion['tiempo'] = select.obtenerValor();
                $('#select-numero-VGC').attr('disabled', false);
                switch (select.obtenerValor()) {
                    case 'WEEK':
                        let semanas = [];
                        for (var i = 1; i < 53; i++) {
                            semanas.push(i);
                        }
                        _this.objetos['select-numero-VGC'].cargaDatosEnSelect(semanas);
                        break;
                    case 'MONTH':
                        _this.objetos['select-numero-VGC'].cargaDatosEnSelect([{id: 1, text: 'Enero'},{id: 2, text: 'Febrero'},{id: 3, text: 'Marzo'}
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
                _this.setDatosTabla(_this.objetos['tabla-VGC']);
            });
        });
    }

    setDatosTabla(tabla) {
        let _this = this;
        let datosTemporal = _this.datos.slice();
        datosTemporal.shift();
        _this.objetos[tabla.tabla].limpiartabla();
//        let titulos = [];
//        let semana1 = [];
//        let semana2 = [];
//        let semana3 = [];
//        let semana4 = [];
//        let nuevo = [];
//        $.each(datosTemporal, function (key, value) {
////            console.log(value);
//            for (var i = 0; i < Object.keys(value).length; i += 2) {
//                switch (i) {
//                    case 0:
//                        titulos.push(value[i]);
//                        break;
//                    case 2:
//                        semana1.push(value[i]);
//                        break;
//                    case 4:
//                        semana2.push(value[i]);
//                        break;
//                    case 6:
//                        semana3.push(value[i]);
//                        break;
//                    case 8:
//                        semana4.push(value[i]);
//                        break;
//                }
//            }
//        });
//        let suma1 = 0;
//        $.each(semana1, function (key, value){
//            suma1 += value;
//        });
//        $.each(semana1, function (key, value){
//            semana1[key] = Number.parseFloat(value*100/suma1).toFixed(2) + " %";
//        });
//        
//        let suma2 = 0;
//        $.each(semana2, function (key, value){
//            suma2 += value;
//        });
//        $.each(semana2, function (key, value){
//            semana2[key] = Number.parseFloat(value*100/suma2).toFixed(2) + " %";
//        });
//        
//        let suma3 = 0;
//        $.each(semana3, function (key, value){
//            suma3 += value;
//        });
//        $.each(semana3, function (key, value){
//            semana3[key] = Number.parseFloat(value*100/suma3).toFixed(2) + " %";
//        });
//        
//        let suma4 = 0;
//        $.each(semana4, function (key, value){
//            suma4 += value;
//        });
//        $.each(semana4, function (key, value){
//            semana4[key] = Number.parseFloat(value*100/suma4).toFixed(2) + " %";
//        });
//        
//        for (var i = 0; i < Object.keys(titulos).length; i += 2) {
//            nuevo.push(titulos[i]);
//            nuevo.push(semana1[i]);
//            nuevo.push(semana2[i]);
//        }
//        console.log(titulos);
//        console.log(datosTemporal);
        _this.objetos[tabla.tabla].agregarContenidoTabla(datosTemporal);
    }
}


