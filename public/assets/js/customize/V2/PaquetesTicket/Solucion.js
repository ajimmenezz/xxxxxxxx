class Solucion {

    constructor() {
        this.peticion = new Utileria();
        this.evento = new Base();
        this.formulario = null;
        this.datos = null;

        this.selects = {};
        this.tablas = {};
        this.inputs = {};
        this.file = {};

        this.setElementosFormulario();
    }

    setElementosFormulario() {
        let elementosFormulario = {
            selects: {
                'selectOperacionInstalaciones': 'basico',
                'selectModeloInstalaciones': 'basico',
                'selectAreaAtencionInstalaciones': 'basico',
                'selectPuntoInstalaciones': 'basico'
            },
            inputs: {
                'inputSerieInstalaciones': ''
            },
            filesUpload: {
                'agregarEvidenciaEquipo': {
                    tipo: 'basico',
                    url: 'Seguimiento/Servicio/agregarProblema',
                    extensiones: ['jpg', 'jpeg', 'png']}
            },
        };

        this.formulario = new Formulario('formProblema', elementosFormulario);
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

    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('Solucion', this.datos.html.solucion);
        this.formulario.iniciarElementos();
    }

    listener(callback) {
        let _this = this;

        let selectOperacion = _this.formulario.obtenerElemento('selectOperacionInstalaciones');
        let selectModelo = _this.formulario.obtenerElemento('selectModeloInstalaciones');
        let selectAreaAtencion = _this.formulario.obtenerElemento('selectAreaAtencionInstalaciones');
        let selectPunto = _this.formulario.obtenerElemento('selectPuntoInstalaciones');
        let inputSerie = _this.formulario.obtenerElemento('inputSerieInstalaciones');

        selectOperacion.evento('change', function () {
            if (selectOperacion.obtenerValor() === '') {
                selectAreaAtencion.bloquearElemento();
                selectPunto.bloquearElemento();
                selectAreaAtencion.limpiarElemento();
                selectPunto.limpiarElemento();
                $(`#divAdjuntos`).addClass('hidden');
            } else {
                selectAreaAtencion.habilitarElemento();
            }

            selectPunto.limpiarElemento();
            inputSerie.limpiarElemento();
            selectModelo.limpiarElemento();
            selectModelo.bloquearElemento();

            if (selectOperacion.obtenerValor() === '1') {
                selectAreaAtencion.cargaDatosEnSelect(_this.datos.datosServicio.areasAtencionSucursal);
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
                selectPunto.cargaDatosEnSelect(dataPuntos);
                $(`#divIlegible`).removeClass('hidden');
                inputSerie.habilitarElemento();
                selectPunto.habilitarElemento();
            } else {
                selectAreaAtencion.cargaDatosEnSelect(_this.datos.datosServicio.areasSucursal);
                $(`#divIlegible`).addClass('hidden');
                inputSerie.bloquearElemento();
                selectPunto.bloquearElemento();
            }
        });

        selectAreaAtencion.evento('change', function () {
            selectPunto.habilitarElemento();
            if (selectOperacion.obtenerValor() === '2') {
                selectAreaAtencion.cargarElementosASelect('selectPuntoInstalaciones', _this.datos.datosServicio.areasPuntosSucursal, 'idArea');
            }
        });

        selectPunto.evento('change', function () {
            if (selectPunto.obtenerValor() !== '') {
                selectModelo.habilitarElemento();
            }

            if (selectOperacion.obtenerValor() === '1') {
                selectModelo.cargaDatosEnSelect(_this.datos.datosServicio.equipos);
            } else {
                let sucursal = _this.datos.servicio.sucursal;
                let area = selectAreaAtencion.obtenerValor();
                let punto = selectPunto.obtenerValor();
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

        selectModelo.evento('change', function () {
            if (selectOperacion.obtenerValor() === '2') {
                let serie = $("#selectModeloInstalaciones option:selected").attr("data-serie");
                inputSerie.definirValor(serie);
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
                {'objeto': '#selectPuntoInstalaciones', 'mensajeError': 'Falta seleccionar el campo Punto.'},
                {'objeto': '#selectModeloInstalaciones', 'mensajeError': 'Falta seleccionar el campo Modelo.'},
                {'objeto': '#inputSerieInstalaciones', 'mensajeError': 'Falta escribir el campo Serie.'}
            ];

            if ($('#inputIlegibleInstalciones').is(':checked')) {
                arrayCampos.push({'objeto': '#agregarEvidenciaEquipo', 'mensajeError': 'Falta seleccionar el campo adjuntos.'});
            }

            let camposFormularioValidados = _this.evento.validarCamposObjetos(arrayCampos, '.errorInstalaciones');

            if (camposFormularioValidados) {
                let data = {
                    operacion: selectOperacion.obtenerValor(),
                    areaAtencion: selectAreaAtencion.obtenerValor(),
                    punto: selectPunto.obtenerValor(),
                    modelo: selectModelo.obtenerValor(),
                    serie: inputSerie.obtenerValor()
                };
                console.log(data);
            }
        });
    }

}

