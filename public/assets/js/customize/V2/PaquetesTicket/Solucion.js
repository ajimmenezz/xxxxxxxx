class Solucion {

    constructor() {
        this.peticion = new Utileria();
        this.evento = new Base();
//        this.input = new IInput();
        this.formulario = null;
        this.selects = {};
        this.tablas = {};
        this.inputs = {};
        this.file = {};
    }

    iniciarElementos() {
        this.peticion.insertarContenido('Solucion', this.datos.html.solucion);
        this.crearSelects();
//        this.crearTablas();
        this.crearInputs();
        this.crearFiles();
    }

    crearSelects() {
        let _this = this;
        let selects = [
            'selectOperacionInstalaciones',
            'selectModeloInstalaciones',
            'selectAreaAtencionInstalaciones',
            'selectPuntoInstalaciones'
        ];
        $.each(selects, function (index, value) {
            _this.selects[value] = new SelectBasico(value);
        });

        $.each(_this.selects, function (index, value) {
            value.iniciarSelect();
        });
    }

    crearTablas() {
        let _this = this;
        let tablas = [
            'data-table-equipos-instalaciones'
        ];

        $.each(tablas, function (index, value) {
            _this.tablas[value] = new TablaBasica(value);
        });

        $.each(_this.tablas, function (index, value) {
            value.iniciarTabla();
        });
    }

    crearInputs() {
        let _this = this;
        let inputs = [
            'inputSerieInstalaciones'
        ];

        $.each(inputs, function (index, value) {
            _this.inputs[value] = new Input(value);
        });
    }

    crearFiles() {
        this.file = new FileUpload_Basico('agregarEvidenciaEquipo', {url: 'Seguimiento/Servicio/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        this.file.iniciarFileUpload();
    }

    setDatos(datos) {
        this.datos = datos;
    }

    listener(callback) {
        let _this = this;

        _this.selects['selectOperacionInstalaciones'].evento('change', function () {
            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '') {
                _this.selects['selectAreaAtencionInstalaciones'].bloquearElemento();
                _this.selects['selectPuntoInstalaciones'].bloquearElemento();
                _this.selects['selectModeloInstalaciones'].bloquearElemento();
                _this.selects['selectAreaAtencionInstalaciones'].limpiarElemento();
                _this.selects['selectPuntoInstalaciones'].limpiarElemento();
                _this.selects['selectModeloInstalaciones'].limpiarElemento();
            } else {
                _this.selects['selectAreaAtencionInstalaciones'].habilitarElemento();
            }

            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '1') {
                _this.selects['selectAreaAtencionInstalaciones'].cargaDatosEnSelect(_this.datos.datosServicio.areasAtencionSucursal);
                let dataPuntos = [
                    {id: '1', text: 1},
                    {id: '2', text: 2},
                    {id: '3', text: 3},
                    {id: '4', text: 4},
                    {id: '5', text: 5},
                    {id: '6', text: 6},
                    {id: '7', text: 7},
                    {id: '8', text: 8},
                    {id: '9', text: 9},
                    {id: '10', text: 10}
                ];
                _this.selects['selectPuntoInstalaciones'].cargaDatosEnSelect(dataPuntos);
                $(`#divIlegible`).removeClass('hidden');
                _this.inputs['inputSerieInstalaciones'].habilitarElemento();
                _this.selects['selectPuntoInstalaciones'].habilitarElemento();
            } else {
                _this.selects['selectAreaAtencionInstalaciones'].cargaDatosEnSelect(_this.datos.datosServicio.areasSucursal);
                $(`#divIlegible`).addClass('hidden');
                _this.inputs['inputSerieInstalaciones'].bloquearElemento();
                _this.selects['selectPuntoInstalaciones'].bloquearElemento();
            }
        });

        _this.selects['selectAreaAtencionInstalaciones'].evento('change', function () {
            _this.selects['selectPuntoInstalaciones'].habilitarElemento();
            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '2') {
                _this.selects["selectAreaAtencionInstalaciones"].cargarElementosASelect('selectPuntoInstalaciones', _this.datos.datosServicio.areasPuntosSucursal, 'idArea');
            }
        });

        _this.selects['selectPuntoInstalaciones'].evento('change', function () {
            _this.selects['selectModeloInstalaciones'].habilitarElemento();
            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '1') {
                _this.selects['selectModeloInstalaciones'].cargaDatosEnSelect(_this.datos.datosServicio.equipos);
            } else {
                let sucursal = _this.datos.servicio.sucursal;
                let area = _this.selects['selectAreaAtencionInstalaciones'].obtenerValor();
                let punto = _this.selects['selectPuntoInstalaciones'].obtenerValor();
                let data = {sucursal: sucursal, area: area, punto: punto};
                _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/equipoCensadosAreaEquipo', data, function (respuesta) {
                    $("#selectModeloInstalaciones").empty().append('<option data-serie="" value="">Seleccionar</option>');
                    if (respuesta !== false) {
                        $.each(respuesta.equipos, function (key, valor) {
                            $("#selectModeloInstalaciones").append(`<option data-serie="${valor.serie}" value="${valor.id}">${valor.text}</option>`);
                        });
                    }
                });
            }
        });

        _this.selects['selectModeloInstalaciones'].evento('change', function () {
            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '2') {
                let serie = $("#selectModeloInstalaciones option:selected").attr("data-serie");
                _this.inputs['inputSerieInstalaciones'].definirValor(serie);
            }
        });

        $('#inputIlegibleInstalciones').change(function () {
            if (!$(this).is(':checked')) {
                $(`#divAdjuntos`).addClass('hidden');
            } else {
                $(`#divAdjuntos`).removeClass('hidden');
            }
        });

        $('#btnAgregarEquipoInstalacion').off("click");
        $('#btnAgregarEquipoInstalacion').on("click", function () {
            let arrayCampos = [
                {'objeto': '#selectOperacionInstalaciones', 'mensajeError': 'Falta seleccionar el campo operación.'},
                {'objeto': '#selectAreaAtencionInstalaciones', 'mensajeError': 'Falta seleccionarel campo Área de Atención.'},
                {'objeto': '#inputPuntoInstalaciones', 'mensajeError': 'Falta seleccionar el campo Punto.'},
                {'objeto': '#selectModeloInstalaciones', 'mensajeError': 'Falta seleccionar el campo Modelo.'},
                {'objeto': '#inputSerieInstalaciones', 'mensajeError': 'Falta escribir el campo Serie.'}
            ];

            if ($('#inputIlegibleInstalciones').is(':checked')) {
                arrayCampos.push({'objeto': '#agregarEvidenciaEquipo', 'mensajeError': 'Falta seleccionar el campo adjuntos.'});
            }

            let camposFormularioValidados = _this.evento.validarCamposObjetos(arrayCampos, '.errorInstalaciones');

            if (camposFormularioValidados) {
                console.log('pumas');
            }
        });
    }

}

