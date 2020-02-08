class Bitacora {

    constructor() {
        this.peticion = new Utileria();
        this.modal = new Modal('modal-dialogo');
        this.bug = new Bug();
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
        let evento = new Base();

        $('#btnGuardarProblema').on('click', function () {
            let problema = $('#textareaDescProblema').val();
            if (evento.validarFormulario('#formProblema')) {
                let data = {'evidencia': true, descripcion: problema, id: _this.datos.servicio.servicio, tipo: _this.datos.servicio.tipoServicio};
                _this.file.enviarPeticionServidor('modalReportarProblema', data, function (respuesta) {
                    if (_this.bug.validar(respuesta)) {
                        _this.datos.html.bitacora = respuesta.html.bitacora;
                        _this.peticion.insertarContenido('BitacoraProblemas', respuesta.html.bitacora);
                        $('#textareaDescProblema').val('');
                        _this.file.limpiarElemento();
                        $('#modalReportarProblema').modal('hide');
                    }
                });
            }
        });
    }

}

