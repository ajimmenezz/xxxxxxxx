class Informacion {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
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
                _this.peticion.enviar('panelDetallesTicket', 'Seguimiento/Servicio/GuardarInformacionGeneral', data, function (respuesta) {
                    console.log(respuesta);
                    if (_this.bug.validar(respuesta)) {
                        _this.datos.servicio = respuesta.servicio;
                        _this.mostrarOcultarBotones(true);
                        _this.habilitarDeshabilitarSelect(false);
                        callback(respuesta);
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

        $('#btnEditarFolio').on('click', function () {
            console.log('pumas');
        });

        $('#btnEliminarFolio').on('click', function () {
            console.log(_this.inputs['folio'].obtenerValor());
            if (_this.inputs['folio'].obtenerValor() !== '0' && _this.inputs['folio'].obtenerValor() !== '') {
                _this.modal.mostrarModal('Eliminar Folio', '<h3 class="text-center">¿Estas Seguro de eliminar este FOLIO?</h3>');
//                $('#btnModalConfirmar').on('click', function () {
//                    let data = {folio: '', id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
//                    _this.peticion.enviar('modal-dialog', 'Seguimiento/Servicio/Folio/eliminar', data, function (respuesta) {
//                        if (_this.bug.validar(respuesta)) {
//                            _this.inputs['folio'].definirValor('');
//                        }
//                    });
//                    _this.modal.cerrarModal();
//                });
                _this.modal.funcionalidadBotonAceptar(null, function () {
                    console.log('pumas');
//                    let dato = _this.formularioJustificarMaterial.validarFormulario();
//                    datos.justificacion = dato['textarea-justificar'];
//                    _this.insertarDatosTablaMaterial(datos);
//                    _this.formularioMaterialNodo.limpiarElementos();
//                    _this.modal.cerrarModal();
                });
            } else {
                _this.modal.mostrarModal('Eliminar Folio', '<h3 class="text-center">No cuenta con folio este servicio</h3>');
                _this.modal.ocultarBotonAceptar();
            }
        });
    }

    mostrarOcultarBotones(accion) {
        if (accion) {
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
        this.modal.mostrarModal('Correcto', '<h3 class="text-center">' + mensaje + '</h3>');
        this.modal.ocultarBotonAceptar();
    }
}

