class Firma {

    constructor() {
        this.peticion = new Utileria();
        this.solucion = new Solucion();
        this.evento = new Base();
        this.alerta = new Alertas('errorMessageFirmaCliente');
        this.modal = new Modal('modal-dialogo');
        this.bug = new Bug();
        this.datos = null;
    }

    setDatos(datos) {
        this.datos = datos;
        this.desabilitarFormulario();
    }

    listener(callback) {
        let _this = this;

        $("#btnCierre").off("click");
        $('#btnCierre').on('click', function () {
            _this.solucion.setDatos(_this.datos);
            _this.solucion.listener(dato => _this.servicio.setDatos(dato));
            if (_this.solucion.validarSolucion()) {
                _this.peticion.insertarContenido('Solucion', _this.htmlFirma());
                let firmaCliente = new DrawingBoard.Board("firmaCliente", {
                    background: "#fff",
                    color: "#000",
                    size: 1,
                    controlsPosition: "right",
                    controls: [
                        {
                            Navigation: {
                                back: false,
                                forward: false
                            }
                        }
                    ],
                    webStorage: false
                });

                _this.botonRegresar();
                _this.botonConcluir(firmaCliente);
            } else {
                _this.modal.mostrarModal('Advertencia', '<h3 class="text-center">Falta la solución del servicio.</h3>');
                _this.modal.ocultarBotonAceptar();
                _this.modal.cambiarValorBotonCanelar('<i class="fa fa-times"></i> Cerrar');
            }
        });

    }

    htmlFirma() {
        return `<div class="panel-body">
                    <div id="contentfirmaCliente" class="text-center">
                        <form id="formAgregarCliente" data-parsley-validate="true" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Nombre y Firma de Cliente *</label>
                                        <input id="inputCliente" type="text" class="form-control" style="width: 100%" data-parsley-required="true"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div id="firmaCliente" style="width: 600px; height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <div id="errorMessageFirmaCliente"></div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 text-center">
                            <a id="btnConcluir" class="btn btn-sm btn-success"><i class="fa fa-sign-in"></i> Concluir</a>
                            <a id="btnRegresarServicio" class="btn btn-sm btn-danger"><i class="fa fa-rotate-180 fa-sign-in"></i> Regresar</a>
                        </div>
                    </div>
                </div>`;
    }

    botonRegresar() {
        let _this = this;

        $("#btnRegresarServicio").off("click");
        $('#btnRegresarServicio').on('click', function () {
            _this.solucion.setDatos(_this.datos);
            _this.solucion.listener(dato => _this.servicio.setDatos(dato));
        });
    }

    botonConcluir(firmaCliente) {
        let _this = this;

        $('#btnConcluir').on('click', function () {
            let cliente = $('#inputCliente').val();
            let imgFirmaCliente = firmaCliente.getImg();
            let inputFirmaCliente = (firmaCliente.blankCanvas == imgFirmaCliente) ? '' : imgFirmaCliente;
            if (inputFirmaCliente === '') {
                _this.evento.mostrarMensaje("#errorMessageFirmaCliente", false, 'Falta firma del cliente.', 2000);
            } else if (cliente === '') {
                _this.evento.mostrarMensaje("#errorMessageFirmaCliente", false, 'Falta nombre del que firma.', 2000);
            } else {
                let data = {
                    id: _this.datos.servicio.servicio,
                    tipo: _this.datos.servicio.tipoServicio,
                    nombreCliente: $('#inputCliente').val(),
                    firmaCliente: inputFirmaCliente,
                    folio: _this.datos.servicio.folio};

                _this.peticion.enviar('panel-ticket', 'Seguimiento/Servicio/concluir', data, function (respuesta) {
                    if (_this.bug.validar(respuesta)) {
                        _this.modal.mostrarModal("Exito", '<h4 class="text-center">Se han concluido el servicio correctamente</h4>');
                        $('#btnCerrar').addClass('hidden');
                        _this.modal.ocultarBotonCanelar();
                        _this.modal.funcionalidadBotonAceptar(null, function () {
                            _this.modal.cerrarModal();
                            _this.evento.empezarCargando('#panel-ticket');
                            location.reload();
                        });
                    }
                });
            }
        });
    }

    desabilitarFormulario() {
        if (this.datos.servicio.estatusServicio === '5') {
            this.peticion.ocultarElemento('btnCierre');
        }
    }
}

