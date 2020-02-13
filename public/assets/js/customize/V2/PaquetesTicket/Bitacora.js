class Bitacora {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
        this.modalBox = new ModalBox('modal-box');
        this.bug = new Bug();
        this.evento = new Base();

        this.datos = null;
        this.file = {};
        this.inputs = {};
        this.idAvanceProblema = null;
        this.botones = `<button type="button" id="btnCancelarProblema" class="btn btn-white"><i class="fa fa-close"></i> Cerrar</button>`;
    }

    iniciarElementos() {
        this.crearFiles();
        this.crearInputs();
    }

    crearFiles() {
        this.file = new FileUpload_Basico('agregarEvidenciaProblema', {url: 'Seguimiento/Servicio/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        this.file.iniciarFileUpload();
    }

    crearInputs() {
        let _this = this;
        let inputs = [
            'textareaDescProblema'
        ];

        $.each(inputs, function (index, value) {
            _this.inputs[value] = new IInput(value);
        });
    }
    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('BitacoraProblemas', this.datos.html.bitacora);
    }

    listener(callback) {
        let _this = this;

        $("#btnReportarProblema").off("click");
        $("#btnReportarProblema").on("click", function () {
            _this.cargarModalProblema('Agregar Problema', '<i class="fa fa-pencil"></i> Guardar');
            _this.modalBox.funcionalidadBotonAceptar(null, function () {
                let problema = _this.inputs['textareaDescProblema'].obtenerValor();
                if (_this.evento.validarFormulario('#formProblema')) {
                    let data = {'evidencia': true, descripcion: problema, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio, tipoOperacion: 'guardar'};
                    _this.file.enviarPeticionServidor('modal-box', data, function (respuesta) {
                        if (_this.bug.validar(respuesta)) {
                            _this.datos.servicio = respuesta.servicio;
                            _this.datos.html.bitacora = respuesta.html.bitacora;
                            _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                            _this.modalBox.cerrarModal();
                            _this.botonEditar();
                            _this.botonEliminar();
                        }
                    });
                }
            });
        });

        _this.botonEditar();
        _this.botonEliminar();
    }

    cargarModalProblema(titulo, textoBoton) {
        this.modalBox.mostrarModal(titulo, this.datos.html.problema);
        this.modalBox.colorFondoTitulo('background-color:#f59c1a');
        this.modalBox.colorTitulo('text-white');
        this.modalBox.cambiarValorBotonCanelar('<i class="fa fa-times"></i> Cerrar');
        this.modalBox.cambiarValorBotonAceptar(textoBoton);
        this.modalBox.colorBotonAceptar('btn-warning');
        this.iniciarElementos();
    }

    respuestaProblemaActualizar(respuesta) {
        this.datos.servicio = respuesta.servicio;
        this.datos.html.bitacora = respuesta.html.bitacora;
        this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
        this.modalBox.cerrarModal();
        this.botonEditar();
        this.botonEliminar();
    }

    botonEditar() {
        let _this = this;

        $(".btnEditarAvanceSeguimientoSinEspecificar").off("click");
        $(".btnEditarAvanceSeguimientoSinEspecificar").on("click", function () {
            _this.idAvanceProblema = $(this).data('id');
            _this.cargarModalProblema('Actualizar Problema', '<i class="fa fa-pencil"></i> Actualizar');
            let htmlEvidencias = '';
            $.each(_this.datos.servicio.problemas, function (index, value) {
                if (value.Id == _this.idAvanceProblema) {
                    _this.inputs['textareaDescProblema'].definirValor(value.Descripcion);
                    $('#fileMostrarEvidenciaProblema').removeClass('hidden');
                    let arrayEvidencias = value.Archivos.split(',');
                    $.each(arrayEvidencias, function (key, valor) {
                        htmlEvidencias += `<div id="img-${key}" class="evidencia">
                                    <a href="${valor}" data-lightbox="evidencias">
                                        <img src ="${valor}" />
                                    </a>
                                    <div class="eliminarEvidenciaProblema" data-id="${_this.idAvanceProblema}" data-value="${valor}" data-key="${key}">
                                        <a href="#">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </div>`;
                    });
                    $('#evidenciasProblema').empty().append(htmlEvidencias);
                    _this.botonEliminarEvidenciaProblema(arrayEvidencias);
                    _this.botonEditar();
                    _this.botonEliminar();
                }
                _this.modalBox.funcionalidadBotonAceptar(null, function () {
                    let problema = _this.inputs['textareaDescProblema'].obtenerValor();
                    if (problema !== '') {
                        let data = {
                            evidencia: true,
                            descripcion: problema,
                            id: _this.datos.servicio.servicio,
                            tipo: _this.datos.servicio.tipoServicio,
                            tipoOperacion: 'actualizar',
                            idAvanceProblema: idAvanceProblema
                        };
                        try {
                            data.evidencia = true;
                            _this.file.enviarPeticionServidor('modal-box', data, function (respuesta) {
                                if (_this.bug.validar(respuesta)) {
                                    _this.respuestaProblemaActualizar(respuesta);
                                }
                            });
                        } catch (exception) {
                            data.evidencia = false;
                            _this.peticion.enviar('modal-box', 'Seguimiento/Servicio/agregarProblema', data, function (respuesta) {
                                _this.respuestaProblemaActualizar(respuesta);
                            });
                        }
                    } else {
                        _this.evento.mostrarMensaje('#errorAgregarProblema', false, 'Falta un campo de descripción.', 3000);
                    }
                });
            });
        });
    }

    botonEliminar() {
        let _this = this;
        $('.btnEliminarAvanceSeguimientoSinEspecificar').off('click');
        $('.btnEliminarAvanceSeguimientoSinEspecificar').on('click', function () {
            _this.modal.mostrarModal(`Advertencia`, '<h3 class="text-center">¿Realmente quiere eliminar la información?</h3>');
            _this.modal.funcionalidadBotonAceptar(null, function () {
                let data = {'idAvanceProblema': _this.idAvanceProblema, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
                _this.peticion.enviar('modal-dialogo', 'Seguimiento/Servicio/elminarAvanceProblema', data, function (respuesta) {
                    if (_this.bug.validar(respuesta)) {
                        _this.datos.servicio = respuesta.servicio;
                        _this.datos.html.bitacora = respuesta.html.bitacora;
                        _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                        _this.botonEditar();
                        _this.botonEliminar();
                        _this.modal.cerrarModal();
                    }
                });
            });
        });
    }

    botonEliminarEvidenciaProblema(arrayEvidencias) {
        let _this = this;

        $(".eliminarEvidenciaProblema").off("click");
        $(".eliminarEvidenciaProblema").on("click", function () {
            let archivo = $(this).data('value');
            let indice = $(this).data('key');
            let idAvanceProblema = $(this).data('id');

            $.each(arrayEvidencias, function (key, value) {
                if (key == indice) {
                    delete arrayEvidencias[key];
                }
            });

            if (arrayEvidencias.length > 1) {
                let data = {
                    'evidencia': archivo,
                    'idAvanceProblema': idAvanceProblema,
                    id: _this.datos.servicio.servicio,
                    tipo: _this.datos.servicio.tipoServicio
                };

                _this.peticion.enviar('modalReportarProblema', 'Seguimiento/Servicio/eliminarEvidenciaProblema', data, function (respuesta) {
                    _this.datos.html.bitacora = respuesta.html.bitacora;
                    _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                    $(`#img-${indice}`).addClass('hidden');
                    _this.botonEditar();
                    _this.botonEliminar();
                });
            } else {
                _this.evento.mostrarMensaje('#errorAgregarProblema', false, 'Debe haber al menos un archivo.', 3000);
            }
        });
    }

}

