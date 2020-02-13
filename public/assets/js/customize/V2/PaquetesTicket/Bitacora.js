class Bitacora {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
        this.modalBox = new ModalBox('modal-box');
        this.bug = new Bug();
        this.evento = new Base();
        this.formularioProblema = null;

        this.datos = null;
        this.idAvanceProblema = null;

        this.setElementosFormulario();
    }

    setElementosFormulario() {
        let elementosFormulario = {
            filesUpload: {
                'agregarEvidenciaProblema': {
                    tipo: 'basico',
                    url: 'Seguimiento/Servicio/agregarProblema',
                    extensiones: ['jpg', 'jpeg', 'png']}
            },
            inputs: {
                'textareaDescProblema': ''
            }
        };

        this.formularioProblema = new Formulario('formProblema', elementosFormulario);
    }

    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('BitacoraProblemas', this.datos.html.bitacora);
    }

    listener(callback) {
        let _this = this;

        $("#btnReportarProblema").off("click");
        $("#btnReportarProblema").on("click", function () {
            _this.cargarModalProblema('Agregar Problema');
            _this.formularioProblema.iniciarElementos();
            let file = _this.formularioProblema.obtenerElemento('agregarEvidenciaProblema');
            file.setAtributos({'data-parsley-required': 'true'});

            _this.modalBox.funcionalidadBotonAceptar('<i class="fa fa-pencil"></i> Guardar', function () {
                _this.setProblema(file);
            });
        });

        _this.botonEditar();
        _this.botonEliminar();
    }

    cargarModalProblema(titulo) {
        let estilos = {cabecera: 'bg-orange', titulo: 'text-white', btnAceptar: 'btn-warning'};
        this.modalBox.mostrarModal(titulo, this.datos.html.problema);
        this.modalBox.setEstilos(estilos);
        this.modalBox.cambiarValorBotonCanelar('<i class="fa fa-times"></i> Cerrar');
    }

    setProblema(file, operacion = 'guardar') {
        let _this = this;
        try {
            let datosFormulario = _this.formularioProblema.validarFormulario();
            let data = {
                evidencia: true,
                descripcion: datosFormulario['textareaDescProblema'],
                id: _this.datos.servicio.servicio,
                tipo: _this.datos.servicio.tipoServicio,
                tipoOperacion: operacion,
                idAvanceProblema: _this.idAvanceProblema,
                archivosEleminar: []
            };
            file.enviarPeticionServidor('modal-box', data, function (respuesta) {
                if (_this.bug.validar(respuesta)) {
                    _this.datos.servicio = respuesta.servicio;
                    _this.datos.html.bitacora = respuesta.html.bitacora;
                    _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                    _this.modalBox.cerrarModal();
                    _this.botonEditar();
                    _this.botonEliminar();
                }
            });
        } catch (Error) {
            _this.modalBox.mostrarError('errorAgregarProblema', Error);
    }
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
            _this.formularioProblema.iniciarElementos();
            let file = _this.formularioProblema.obtenerElemento('agregarEvidenciaProblema');
            file.setAtributos({'data-parsley-required': 'false'});

//            let htmlEvidencias = '';
//            $.each(_this.datos.servicio.problemas, function (index, value) {
//                if (value.Id == _this.idAvanceProblema) {
//                    _this.inputs['textareaDescProblema'].definirValor(value.Descripcion);
//                    $('#fileMostrarEvidenciaProblema').removeClass('hidden');
//                    let arrayEvidencias = value.Archivos.split(',');
//                    $.each(arrayEvidencias, function (key, valor) {
//                        htmlEvidencias += `<div id="img-${key}" class="evidencia">
//                                    <a href="${valor}" data-lightbox="evidencias">
//                                        <img src ="${valor}" />
//                                    </a>
//                                    <div class="eliminarEvidenciaProblema" data-id="${_this.idAvanceProblema}" data-value="${valor}" data-key="${key}">
//                                        <a href="#">
//                                            <i class="fa fa-trash text-danger"></i>
//                                        </a>
//                                    </div>
//                                </div>`;
//                    });
//                    this.peticion.insertarContenido('evidenciasProblema', htmlEvidencias);
////                    $('#evidenciasProblema').empty().append(htmlEvidencias);
//                    _this.botonEliminarEvidenciaProblema(arrayEvidencias);
//                    _this.botonEditar();
//                    _this.botonEliminar();
//                }
//            });
//
            _this.modalBox.funcionalidadBotonAceptar('<i class="fa fa-pencil"></i> Actualizar', function () {
                _this.setProblema(file, 'actualizar');
//                try {
//                    let datosFormulario = _this.formularioProblema.validarFormulario();
//                    let data = {
//                        evidencia: false,
//                        descripcion: datosFormulario['textareaDescProblema'],
//                        id: _this.datos.servicio.servicio,
//                        tipo: _this.datos.servicio.tipoServicio,
//                        tipoOperacion: 'actualizar',
//                        idAvanceProblema: _this.idAvanceProblema,
//                        archivosEleminar: []
//                    };
//                    file.enviarPeticionServidor('modal-box', data, function (respuesta) {
//                        if (_this.bug.validar(respuesta)) {
//                            _this.datos.servicio = respuesta.servicio;
//                            _this.datos.html.bitacora = respuesta.html.bitacora;
//                            _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
//                            _this.modalBox.cerrarModal();
//                            _this.botonEditar();
//                            _this.botonEliminar();
//                        }
//                    });
//                } catch (Error) {
//                    _this.modalBox.mostrarError('errorAgregarProblema', Error);
//                }

//                let problema = _this.inputs['textareaDescProblema'].obtenerValor();
//                if (problema !== '') {
//                    let data = {
//                        evidencia: true,
//                        descripcion: problema,
//                        id: _this.datos.servicio.servicio,
//                        tipo: _this.datos.servicio.tipoServicio,
//                        tipoOperacion: 'actualizar',
//                        idAvanceProblema: idAvanceProblema
//                    };
//                    try {
//                        data.evidencia = true;
//                        _this.file.enviarPeticionServidor('modal-box', data, function (respuesta) {
//                            if (_this.bug.validar(respuesta)) {
//                                _this.respuestaProblemaActualizar(respuesta);
//                            }
//                        });
//                    } catch (exception) {
//                        data.evidencia = false;
//                        _this.peticion.enviar('modal-box', 'Seguimiento/Servicio/agregarProblema', data, function (respuesta) {
//                            _this.respuestaProblemaActualizar(respuesta);
//                        });
//                    }
//                } else {
//                    _this.evento.mostrarMensaje('#errorAgregarProblema', false, 'Falta un campo de descripción.', 3000);
//                }
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

