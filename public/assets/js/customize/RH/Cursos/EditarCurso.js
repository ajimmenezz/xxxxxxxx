class EditarCurso {

    constructor() {
        this.idCurso;
        this.datosCurso = {};
        this.selectCertificado;
        this.inputNombreCurso;
        this.inputUrl;
        this.inputCosto;
        this.textDescripcion;
        this.file;
        this.formulario;
        this.modal = new ModalBox('modal-box');
    }

    init(idCurso) {
        this.idCurso = idCurso;
        this.initDatosCurso();
    }

    events() {
        this.eventRegresarCursos();
        this.eventDatosCurso();
    }

    initDatosCurso() {
        this.inputNombre = $(`#input-edit-nombre`);
        this.textDescripcion = $(`#textarea-edit-descripcion`);
        this.inputUrl = $(`#input-edit-url`);
        this.selectCertificado = new SelectBasico('select-edit-certificado');
        this.inputCosto = $(`#input-edit-costo`);
        this.file = new FileNativo('file-edit-imagen', 'contenedor-edit-imagen');
        this.formulario = $(`#form-edit-datos-curso`);
//        this.getDatosCurso();
    }

    getDatosCurso() {
        let _this = this;
        _this.datosCurso.nombre = '';
        _this.datosCurso.descripcion = '';
        _this.datosCurso.url = '';
        _this.datosCurso.certificado = '';
        _this.datosCurso.costo = '';
        Helper.enviarPeticionServidor('panel-cursos', 'Administracion_Cursos/Obtener-Curso', {id: this.idCurso}, function (respond) {
            console.log(respond);

        });
    }

    eventRegresarCursos() {
        $('#btn-regresar-cursos').on('click', function () {
            Helper.ocultarElemento('seccion');
            Helper.quitarContenidoElemento('seccion');
            Helper.mostrarElemento('seccion-cursos');
        });
    }

    eventDatosCurso() {
        let _this = this;

        $('#btn-edit-habilitar').on('click', function () {
            Helper.habilitar('input-edit-nombre');
            Helper.habilitar('textarea-edit-descripcion');
            Helper.habilitar('input-edit-url');
            Helper.habilitar('input-edit-costo');
            Helper.habilitar('btn-edit-imagen');
            _this.selectCertificado.habilitarElemento();
            _this.hiddenBotonesDatos(false);
        });

        $('#btn-edit-guardar').on('click', function () {
            if (_this.formulario.parsley().validate()) {
                _this.datosCurso.nombre = _this.inputNombre.val();
                _this.datosCurso.descripcion = _this.textDescripcion.val();
                _this.datosCurso.url = _this.inputUrl.val();
                _this.datosCurso.certificado = _this.selectCertificado.obtenerValor();
                _this.datosCurso.costo = _this.inputCosto.val() ? _this.inputCosto.val() : "00.00";
                _this.showMensaje();
                _this.uploadFile(_this.datosCurso);
            }
        });

        $('#btn-edit-cancelar').on('click', function () {
            Helper.bloquear('input-edit-nombre');
            Helper.bloquear('textarea-edit-descripcion');
            Helper.bloquear('input-edit-url');
            Helper.bloquear('input-edit-costo');
            Helper.bloquear('btn-edit-imagen');
            _this.selectCertificado.bloquearElemento();
            _this.hiddenBotonesDatos(true);
            _this.setDatosCurso();
            _this.formulario.parsley().reset();
        });

        $('#btn-edit-imagen').on('click', function () {
            $('#file-edit-imagen').click();
        });

        _this.file.addListenerChange(["jpeg", "png"]);
    }

    uploadFile(datos = {}){
        this.showMensajeExito();
        this.file.uploadServer('Administracion_Cursos/Editar-Curso', datos, function (respond) {

        });
    }

    setDatosCurso() {
        this.inputNombre.val(this.datosCurso.nombre);
        this.textDescripcion.val(this.datosCurso.descripcion);
        this.inputUrl.val(this.datosCurso.url);
        this.selectCertificado.definirValor(this.datosCurso.certificado);
        this.inputCosto.val(this.datosCurso.costo);
    }

    hiddenBotonesDatos(hidden = true) {
        if (hidden) {
            Helper.mostrarElemento('btn-edit-habilitar');
            Helper.ocultarElemento('btn-edit-guardar');
            Helper.ocultarElemento('btn-edit-cancelar');
        } else {
            Helper.ocultarElemento('btn-edit-habilitar');
            Helper.mostrarElemento('btn-edit-guardar');
            Helper.mostrarElemento('btn-edit-cancelar');
    }
    }

    showMensaje() {
        let _this = this;

        let contenido = `<div class="row">
                            <div class="col-md-12 text-center">
                                Se esta actulizando el curso                                
                            </div>
                            <div class="col-md-12 text-center">
                                <i class="fa fa-refresh"></i>                              
                            </div>
                         </div>`;
        _this.modal.mostrarModal('Editar Curso', contenido);
        _this.modal.ocultarBotonAceptar();
        _this.modal.ocultarBotonCanelar();
    }

    showMensajeExito() {
        this.modal.borrarContenido();
        this.modal.agregarContenido(`<div class="row">
                                        <div class="col-md-12 text-center">
                                            Se actualizo el curso                                
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <b>${this.datosCurso.nombre}</b>                                
                                        </div>
                                        <div class="col-md-12 text-center">
                                            con éxito.
                                        </div>
                                        <div class="col-md-12 text-center text-success">
                                            <i class="fa fa-2x fa-check-circle "></i>
                                        </div>
                                     </div>`);
        this.modal.mostrarBotonCancelar();
        this.modal.cambiarValorBotonCanelar('Cerrar');
    }

    showMensajeError() {
        this.modal.borrarContenido();
        this.modal.agregarContenido(`<div class="row">
                                        <div class="col-md-12 fa-2x text-center text-danger">
                                            <i class="fa fa-exclamation-circle"></i> Error
                                        </div>
                                        <div class="col-md-12 text-center">
                                            No se pudo actualizar el curso                               
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <b>${this.datosCurso.nombre}</b>                                
                                        </div>
                                        <div class="col-md-12 text-center">
                                            Favor de volver a intentarlo.
                                        </div>
                                        
                                     </div>`);
        this.modal.mostrarBotonCancelar();
        this.modal.cambiarValorBotonCanelar('Cerrar');
    }
}


