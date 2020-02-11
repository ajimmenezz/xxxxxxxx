class Bitacora {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
        this.bug = new Bug();
        this.evento = new Base();

        this.datos = null;
        this.file = {};
    }

    iniciarElementos() {
        this.crearFiles();
    }

    crearFiles() {
        this.file = new FileUpload_Basico('agregarEvidenciaProblema', {url: 'Seguimiento/Servicio/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        this.file.iniciarFileUpload();
    }

    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('BitacoraProblemas', this.datos.html.bitacora);
    }

    listener(callback) {
        let _this = this;

        $('#btnGuardarProblema').on('click', function () {
            let problema = $('#textareaDescProblema').val();
            if (_this.evento.validarFormulario('#formProblema')) {
                let data = {'evidencia': true, descripcion: problema, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio, tipoOperacion: 'guardar'};
                _this.file.enviarPeticionServidor('modalReportarProblema', data, function (respuesta) {
                    if (_this.bug.validar(respuesta)) {
                        _this.datos.servicio = respuesta.servicio;
                        _this.datos.html.bitacora = respuesta.html.bitacora;
                        _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                        $('#textareaDescProblema').val('');
                        _this.file.limpiarElemento();
                        $('#modalReportarProblema').modal('hide');
                        _this.botonEditar();
                        _this.botonEliminar();
                    }
                });
            }
        });

        $('#btnCancelarProblema').on('click', function () {
            $('#fileMostrarEvidenciaProblema').addClass('hidden');
            $('#textareaDescProblema').val('');
            _this.file.limpiarElemento();
            $('#modalReportarProblema').modal('hide');
        });

        $('#btnActualizarProblema').on('click', function () {
            var idAvanceProblema = $(".btnEditarAvanceSeguimientoSinEspecificar").data('id');
            let problema = $('#textareaDescProblema').val();
            if (problema !== '') {
                var data = {
                    evidencia: true,
                    descripcion: problema,
                    id: _this.datos.servicio.servicio,
                    tipo: _this.datos.servicio.tipoServicio,
                    tipoOperacion: 'actualizar',
                    idAvanceProblema: idAvanceProblema
                };
                try {
                    data.evidencia = true;
                    _this.file.enviarPeticionServidor('modalReportarProblema', data, function (respuesta) {
                        if (_this.bug.validar(respuesta)) {
                            _this.respuestaProblemaActualizar(respuesta);
                        }
                    });
                } catch (exception) {
                    data.evidencia = false;
                    _this.peticion.enviar('modalReportarProblema', 'Seguimiento/Servicio/agregarProblema', data, function (respuesta) {
                        _this.respuestaProblemaActualizar(respuesta);
                    });
                }
            } else {
                _this.evento.mostrarMensaje('#errorAgregarProblema', false, 'Falta un campo de descripción.', 3000);
            }
        });

        _this.botonEditar();
        _this.botonEliminar();
    }

    respuestaProblemaActualizar(respuesta) {
        this.datos.servicio = respuesta.servicio;
        this.datos.html.bitacora = respuesta.html.bitacora;
        this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
        $('#textareaDescProblema').val('');
        this.file.limpiarElemento();
        $('#modalReportarProblema').modal('hide');
        this.botonEditar();
        this.botonEliminar();
    }

    botonEditar() {
        let _this = this;

        $(".btnEditarAvanceSeguimientoSinEspecificar").off("click");
        $(".btnEditarAvanceSeguimientoSinEspecificar").on("click", function () {
            let htmlEvidencias = '';
            var idAvanceProblema = $(this).data('id');
            $('#modalReportarProblema').modal({
                backdrop: 'static',
                keyboard: true
            });
            $('#modalReportarProblema .modal-title').empty().append('Actualizar Problema');
            $('#btnGuardarProblema').addClass('hidden');
            $('#btnActualizarProblema').removeClass('hidden');
            $.each(_this.datos.servicio.problemas, function (index, value) {
                if (value.Id == idAvanceProblema) {
                    $('#textareaDescProblema').val(value.Descripcion);
                    $('#fileMostrarEvidenciaProblema').removeClass('hidden');
                    let arrayEvidencias = value.Archivos.split(',');
                    $.each(arrayEvidencias, function (key, valor) {
                        htmlEvidencias += `<div id="img-${key}" class="evidencia">
                                    <a href="${valor}" data-lightbox="evidencias">
                                        <img src ="${valor}" />
                                    </a>
                                    <div class="eliminarEvidenciaProblema" data-id="${idAvanceProblema}" data-value="${valor}" data-key="${key}">
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
            });
        });
    }

    botonEliminar() {
        let _this = this;
        $('.btnEliminarAvanceSeguimientoSinEspecificar').off('click');
        $('.btnEliminarAvanceSeguimientoSinEspecificar').on('click', function () {
            var idAvanceProblema = $(this).data('id');
            _this.modal.mostrarModal(`Advertencia`, '<h3 class="text-center">¿Realmente quiere eliminar la información?</h3>');
            _this.modal.funcionalidadBotonAceptar(null, function () {
                let data = {'idAvanceProblema': idAvanceProblema, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
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

