class Informacion {

    constructor() {
        this.peticion = new Utileria();
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
        let dato = {};
        let _this = this;
        let evento = new Base();

        _this.selects["selectCliente"].evento('change', function () {            
            _this.selects["selectCliente"].cargarElementosASelect('selectSucursal', _this.datos.sucursales, 'cliente');

             if (_this.selects['selectCliente'].obtenerValor() === '') {
                _this.selects['selectSucursal'].bloquearElemento();
            }else{
                _this.selects['selectSucursal'].habilitarElemento();
            }
           
        });

        $('#btnGuardarInformacionGeneral').on('click', function () {
            if (evento.validarFormulario('#formInformacionGeneral')) {
                let sucursal = $('#selectSucursal').val();
                let data = {sucursal: sucursal, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
                _this.peticion.enviar('panelDetallesTicket', 'Seguimiento/Servicio/GuardarInformacionGeneral', data, function (respuesta) {
                    console.log(respuesta);
                    _this.mostrarOcultarBotones(true);
                    _this.habilitarDeshabilitarSelect(false);
                    callback(respuesta);
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
            console.log('pumas');
//            modal.mostrarModal('Eliminar Folio', '<h4>¿Estas Seguro de eliminar este FOLIO?</h4>');
//            $('#btnAceptar').on('click', function () {
//                datoServicioTabla.folio = '';
//                peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Folio/eliminar', datoServicioTabla, function (respuesta) {
//                    if (!validarError(respuesta)) {
//                        return;
//                    }
//                    $('#addFolio').prop('disabled', false);
//                    $('#addFolio').val('');
//
//                    ocultarElementosAgregarFolio();
//                    $("#creadoPorFolio").empty();
//                    $("#fechaCreacionFolio").empty();
//                    $("#solicitaFolio").empty();
//                    $("#prioridadFolio").empty();
//                    $("#asignadoFolio").empty();
//                    $("#estatusFolio").empty();
//                    $("#asuntoFolio").empty();
//                    $('#editarFolio').addClass('hidden');
//                    $('#guardarFolio').removeClass('hidden');
//                });
//                modal.cerrarModal();
//            });
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
}

