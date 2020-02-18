class Informacion {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
        this.alerta = new Alertas('modal-dialogo');
        this.bug = new Bug();
        this.formulario = null;
        this.datos = null;
        this.selects = {};
        this.inputs = {};
    }

    iniciarElementos() {
        this.crearSelects();
        this.crearInputs();
    }

    crearSelects() {
        let _this = this;
        let selects = [
            'selectCliente',
            'selectSucursal'
        ];
        $.each(selects, function (index, value) {
            _this.selects[value] = new SelectBasico(value);
        });

        $.each(_this.selects, function (index, value) {
            value.iniciarSelect();
        });

    }

    crearInputs() {
        let _this = this;
        let inputs = [
            'solicitud',
            'ticket',
            'solicita',
            'atiende',
            'fechaSolicitud',
            'folio',
            'servicio',
            'fechaCreacion',
            'fechaInicio'
        ];

        $.each(inputs, function (index, value) {
            _this.inputs[value] = new Input(value);
        });
    }

    setDatos(datos) {
        this.datos = datos;
        this.setDatosSelect(datos);
        this.setDatosInputs(datos.servicio);
        this.mostrarOcultarBotonesFolio(false, true);
        this.desabilitarFormulario();
    }

    setDatosSelect(datos) {
        let temporal = [];

        $.each(datos.clientes, function (index, value) {
            temporal.push({id: value.Id, text: value.Nombre});
        });

        this.selects["selectCliente"].cargaDatosEnSelect(temporal);
        this.setDatosSelects();
    }

    setDatosInputs(datos) {
        let _this = this;
        $.each(datos, function (index, value) {
            if (_this.inputs.hasOwnProperty(index)) {
                _this.inputs[index].definirValor(value);
            }
        });
    }

    listener(callback) {
        let _this = this;
        let evento = new Base();

        _this.selects["selectCliente"].evento('change', function () {
            _this.selects["selectCliente"].cargarElementosASelect('selectSucursal', _this.datos.sucursales, 'cliente');

            if (_this.selects['selectCliente'].obtenerValor() === '') {
                _this.selects['selectSucursal'].bloquearElemento();
            } else {
                _this.selects['selectSucursal'].habilitarElemento();
            }

        });

        $('#btnGuardarInformacionGeneral').on('click', function () {
            if (evento.validarFormulario('#formInformacionGeneral')) {
                let sucursal = $('#selectSucursal').val();
                let data = {sucursal: sucursal, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
                _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/GuardarInformacionGeneral', data, function (respuesta) {
                    if (_this.bug.validar(respuesta)) {
                        _this.datos.servicio = respuesta.servicio;
                        _this.datos.datosServicio = respuesta.datosServicio;
                        _this.mostrarOcultarBotones(true);
                        _this.habilitarDeshabilitarSelect(false);
                        callback(respuesta);
                        _this.mensajeConfirmacionModal('Se actualizo correctamente la sucursal.');
                    }
                });
            }
        });

        $('#btnEditarInformacionGeneral').on('click', function () {
            _this.mostrarOcultarBotones(false);
            _this.habilitarDeshabilitarSelect(true);

            if (_this.selects['selectCliente'].obtenerValor() === '') {
                _this.selects['selectSucursal'].bloquearElemento();
            }
        });

        $('#btnCancelarInformacionGeneral').on('click', function () {
            _this.setDatosSelects();
            _this.mostrarOcultarBotones(true);
            _this.habilitarDeshabilitarSelect(false);
        });

        $('#btnAgregar').on('click', function () {
            _this.inputs['folio'].habilitarElemento();
            _this.mostrarOcultarBotonesFolio(true);
        });

        $('#btnEditarFolio').on('click', function () {
            _this.inputs['folio'].habilitarElemento();
            _this.mostrarOcultarBotonesFolio(true);
        });

        $('#btnCancelar').on('click', function () {
            _this.inputs['folio'].bloquearElemento();
            _this.inputs['folio'].definirValor(_this.datos.servicio.folio);
            _this.mostrarOcultarBotonesFolio(false);
        });

        $('#btnGuardar').on('click', function () {
            let nuevoFolio = _this.inputs['folio'].obtenerValor();
            let data = {folio: nuevoFolio, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
            _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/Folio/editar', data, function (respuesta) {
                if (_this.bug.validar(respuesta)) {
                    _this.datos.servicio.folio = nuevoFolio;
                    _this.datos.folio = respuesta.folio;
                    _this.datos.notasFolio = respuesta.notasFolio;
                    _this.datos.resolucionFolio = respuesta.resolucionFolio;
                    _this.datos.html.folio = respuesta.html.folio;
                    _this.inputs['folio'].bloquearElemento();
                    _this.mostrarOcultarBotonesFolio(false);
                    _this.mensajeConfirmacionModal(`Se actualizo el folio correctamente.`);
                }
            });
        });

        $('#btnVerFolio').on('click', function () {
            _this.modal.mostrarModal(`Información SD - ${_this.datos.servicio.folio}`, _this.datos.html.folio);
            _this.modal.ocultarBotonAceptar();
            _this.modal.cambiarValorBotonCanelar('<i class="fa fa-times"></i> Cerrar');
            handleSlimScroll();
        });

        $('#btnRegresar').on('click', function () {
            evento.empezarCargando('#panel-ticket');
            location.reload();
        });

        $('#btnValidarServicio').on('click', function () {
            let data = {id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
            _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/validarServicio', data, function (respuesta) {
                if (_this.bug.validar(respuesta)) {
                    _this.modal.mostrarModal("Exito", '<h3 class="text-center">Servicio validado correctamente.</h3>');
                    _this.modal.ocultarBotonCanelar();
                    _this.modal.funcionalidadBotonAceptar(null, function () {
                        _this.modal.cerrarModal();
                        evento.empezarCargando('#panel-ticket');
                        location.reload();
                    });
                }
            });
        });

        $('#btnExportarPDF').on('click', function () {
            let data = {id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
            _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/exportarPDF', data, function (respuesta) {
                if (_this.bug.validar(respuesta)) {
                    window.open(respuesta.PDF, '_blank');
                }
            });
        });
    }

    mostrarOcultarBotones(habilitar) {
        if (habilitar) {
            this.peticion.mostrarElemento('btnEditarInformacionGeneral')
            this.peticion.ocultarElemento('btnGuardarInformacionGeneral');
            this.peticion.ocultarElemento('btnCancelarInformacionGeneral');
        } else {
            this.peticion.ocultarElemento('btnEditarInformacionGeneral')
            this.peticion.mostrarElemento('btnGuardarInformacionGeneral');
            this.peticion.mostrarElemento('btnCancelarInformacionGeneral');
        }
    }

    setDatosSelects() {
        this.selects["selectCliente"].definirValor(this.datos.servicio.cliente);
        this.selects["selectCliente"].cargarElementosASelect('selectSucursal', this.datos.sucursales, 'cliente');
        this.selects["selectSucursal"].definirValor(this.datos.servicio.sucursal);
    }

    habilitarDeshabilitarSelect(accion) {
        if (accion) {
            this.selects['selectCliente'].habilitarElemento();
            this.selects['selectSucursal'].habilitarElemento();
        } else {
            this.selects['selectCliente'].bloquearElemento();
            this.selects['selectSucursal'].bloquearElemento();
        }
    }

    mensajeConfirmacionModal(mensaje) {
        this.modal.mostrarModal('Actualizar', '<h3 class="text-center">' + mensaje + '</h3>');
        this.modal.ocultarBotonAceptar();
        this.modal.cambiarValorBotonCanelar('<i class="fa fa-times"></i> Cerrar');
    }

    mostrarOcultarBotonesFolio(editar = false, establecer = false) {
        let folio = this.datos.servicio.folio;

        if (folio === '0') {
            folio = '';
        }

        if (folio !== '' && establecer) {
            this.peticion.mostrarElemento('btnEditarFolio');
            this.peticion.mostrarElemento('btnVerFolio');
            this.peticion.ocultarElemento('btnAgregar');
        }

        if (folio === '' && editar && !establecer) {
            this.peticion.ocultarElemento('btnAgregar');
            this.peticion.mostrarElemento('btnGuardar');
            this.peticion.mostrarElemento('btnCancelar');
        } else if (folio === '' && !editar && !establecer) {
            this.peticion.mostrarElemento('btnAgregar');
            this.peticion.ocultarElemento('btnGuardar');
            this.peticion.ocultarElemento('btnCancelar');
        } else if (folio !== '' && !editar && !establecer) {
            this.peticion.mostrarElemento('btnEditarFolio');
            this.peticion.mostrarElemento('btnVerFolio');
            this.peticion.ocultarElemento('btnAgregar');
            this.peticion.ocultarElemento('btnGuardar');
            this.peticion.ocultarElemento('btnCancelar');
        } else if (folio !== '' && editar && !establecer) {
            this.peticion.ocultarElemento('btnAgregar');
            this.peticion.ocultarElemento('btnEditarFolio');
            this.peticion.ocultarElemento('btnVerFolio');
            this.peticion.mostrarElemento('btnGuardar');
            this.peticion.mostrarElemento('btnCancelar');
    }
    }

    desabilitarFormulario() {
        if (this.datos.servicio.estatusServicio === '5') {
            this.peticion.ocultarElemento('btnEditarFolio');
            this.peticion.ocultarElemento('btnEditarInformacionGeneral');
            this.peticion.mostrarElemento('btnValidarServicio');
            this.peticion.mostrarElemento('btnExportarPDF');
        }
    }
}

