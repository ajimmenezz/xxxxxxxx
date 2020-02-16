class Solucion {

    constructor() {
        this.peticion = new Utileria();
        this.evento = new Base();
        this.modal = new Modal('modal-dialogo');
        this.formulario = null;
        this.datos = null;
        this.selectOperacion = null
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
                    url: 'Seguimiento/Servicio/Accion/AgregarEquipo',
                    extensiones: ['jpg', 'jpeg', 'png']}
            },
        };

        this.formulario = new Formulario('formInstalaciones', elementosFormulario);
    }

    crearTabla() {
        this.tabla = new TablaBotones('data-table-equipos-instalaciones');
    }

    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('Solucion', this.datos.html.solucion);
        this.formulario.iniciarElementos();
        this.selectOperacion = this.formulario.obtenerElemento('selectOperacionInstalaciones');
        this.selectOperacion.cargaDatosEnSelect(this.datos.datosServicio.operaciones);
        this.crearTabla();
        this.agregarDatosTabla(this.datos.datosServicio.instalaciones);
    }

    listener(callback) {
        let _this = this;

        let selectModelo = _this.formulario.obtenerElemento('selectModeloInstalaciones');
        let selectAreaAtencion = _this.formulario.obtenerElemento('selectAreaAtencionInstalaciones');
        let selectPunto = _this.formulario.obtenerElemento('selectPuntoInstalaciones');
        let inputSerie = _this.formulario.obtenerElemento('inputSerieInstalaciones');
        let file = _this.formulario.obtenerElemento('agregarEvidenciaEquipo');

        this.selectOperacion.evento('change', function () {
            if (_this.selectOperacion.obtenerValor() === '') {
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

            if (_this.selectOperacion.obtenerValor() === '1') {
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
            if (_this.selectOperacion.obtenerValor() === '2') {
                selectAreaAtencion.cargarElementosASelect('selectPuntoInstalaciones', _this.datos.datosServicio.areasPuntosSucursal, 'idArea');
            }
        });

        selectPunto.evento('change', function () {
            if (selectPunto.obtenerValor() !== '') {
                selectModelo.habilitarElemento();
                if (_this.selectOperacion.obtenerValor() === '1') {
                    selectModelo.cargaDatosEnSelect(_this.datos.datosServicio.equipos);
                } else if (_this.selectOperacion.obtenerValor() === '2') {
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
            }

        });

        selectModelo.evento('change', function () {
            if (_this.selectOperacion.obtenerValor() === '2') {
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
            if ($('#inputIlegibleInstalciones').is(':checked')) {
                file.setAtributos({'data-parsley-required': 'true'});
            } else {
                file.setAtributos({'data-parsley-required': 'false'});
            }

            try {
                _this.formulario.validarFormulario();
                let data = {
                    'id': _this.datos.servicio.servicio,
                    'tipo': _this.datos.servicio.tipoServicio,
                    'idOperacion': _this.selectOperacion.obtenerValor(),
                    'idArea': selectAreaAtencion.obtenerValor(),
                    'punto': selectPunto.obtenerValor(),
                    'idModelo': selectModelo.obtenerValor(),
                    'serie': inputSerie.obtenerValor()
                };

                file.enviarPeticionServidor('panel-ticket', data, function (respuesta) {
                    _this.selectOperacion.limpiarElemento();
                    selectAreaAtencion.limpiarElemento();
                    selectPunto.limpiarElemento();
                    selectPunto.bloquearElemento();
                    selectModelo.limpiarElemento();
                    inputSerie.limpiarElemento();

                    _this.respuestaDatosServicio(respuesta);
                });
            } catch (Error) {
                _this.modal.mostrarError('errorInstalaciones', Error);
            }
        });
        
        _this.botonEliminar()
    }

    botonEliminar() {
        let _this = this;
        
        $('.btnEliminar').off("click");
        $('.btnEliminar').on("click", function () {
            let idInstalacion = $(this).data('id');
            let data = {'id': _this.datos.servicio.servicio, 'tipo': _this.datos.servicio.tipoServicio, 'idInstalacion': idInstalacion};
            _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/Accion/EliminarEquipo', data, function (respuesta) {
                _this.respuestaDatosServicio(respuesta);
            });
        });
    }

    respuestaDatosServicio(respuesta) {
        this.datos.datosServicio = respuesta.datosServicio;
        this.tabla.limpiartabla();
        this.agregarDatosTabla(respuesta.datosServicio.instalaciones);
        this.botonEliminar();
    }

    agregarDatosTabla(datos) {
        let _this = this;

        $.each(datos, function (key, valor) {
            let evidencias = 'Sin Evidencias';

            if (valor.Archivos !== null) {
                evidencias = _this.tabla.campoEvidencias(valor.Archivos, valor.Id);
            }

            _this.tabla.agregarDatosFila([
                valor.Id,
                valor.IdModelo,
                valor.Modelo,
                valor.Serie,
                valor.IdArea,
                valor.Area,
                valor.Punto,
                valor.IdOperacion,
                valor.Operacion,
                evidencias,
                _this.tabla.botonEliminar(valor.Id)
            ]);
        });
    }

}

