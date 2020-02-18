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
        this.evidenciasProblema = [];
        this.evidenciasEliminar = [];

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
        this.desabilitarFormulario();
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
            if (_this.evidenciasEliminar.length === _this.evidenciasProblema.length && operacion === 'actualizar') {
                throw('Debe al menos haber una evidencias para eliminar.');
            }
            let datosFormulario = _this.formularioProblema.validarFormulario();
            let data = {
                descripcion: datosFormulario['textareaDescProblema'],
                id: _this.datos.servicio.servicio,
                tipo: _this.datos.servicio.tipoServicio,
                tipoOperacion: operacion,
                idAvanceProblema: _this.idAvanceProblema,
                archivosEleminar: _this.evidenciasEliminar
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
            _this.evidenciasEliminar = [];
            _this.idAvanceProblema = $(this).data('id');
            _this.cargarModalProblema('Actualizar Problema', '<i class="fa fa-pencil"></i> Actualizar');
            _this.formularioProblema.iniciarElementos();
            let file = _this.formularioProblema.obtenerElemento('agregarEvidenciaProblema');
            file.setAtributos({'data-parsley-required': 'false'});
            _this.cargarDatosFormularioProblema();

            $('.inputImagen').on('change', function () {
                if ($(this).is(':checked')) {
                    _this.evidenciasEliminar.push($(this).attr('data-imagen'));
                } else {
                    let i = _this.evidenciasEliminar.indexOf($(this).attr('data-imagen'));

                    if (i !== -1) {
                        _this.evidenciasEliminar.splice(i, 1);
                    }
                }
            });

            _this.modalBox.funcionalidadBotonAceptar('<i class="fa fa-pencil"></i> Actualizar', function () {
                _this.setProblema(file, 'actualizar');
            });
        });
    }

    cargarDatosFormularioProblema() {
        let _this = this;
        let htmlEvidencias = '';

        $.each(_this.datos.servicio.problemas, function (index, value) {
            if (value.Id == _this.idAvanceProblema) {
                _this.formularioProblema.asignarValorElemento('textareaDescProblema', value.Descripcion);
                $('#fileMostrarEvidenciaProblema').removeClass('hidden');
                _this.evidenciasProblema = value.Archivos.split(',');
                $.each(_this.evidenciasProblema, function (key, valor) {
                    htmlEvidencias += `<div id="img-${key}" class="evidencia">
                                            <a href="${valor}" data-lightbox="evidencias">
                                                <img src ="${valor}" />
                                            </a>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="inputImagen" name="inputImagen" data-imagen="${valor}" data-key="${key}"  value="1"/>
                                                    <i class="fa fa-trash text-danger"></i>
                                                </label>
                                            </div>
                                        </div>`;
                });
            }
        });

        _this.peticion.insertarContenido('evidenciasProblema', htmlEvidencias);

    }

    botonEliminar() {
        let _this = this;
        $('.btnEliminarAvanceSeguimientoSinEspecificar').off('click');
        $('.btnEliminarAvanceSeguimientoSinEspecificar').on('click', function () {
            _this.idAvanceProblema = $(this).data('id');
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

    desabilitarFormulario() {
        if (this.datos.servicio.estatusServicio === '5') {
            this.peticion.ocultarElemento('btnReportarProblema');
            $('.seccion-botones-problema').addClass('hidden');
        }
    }
}