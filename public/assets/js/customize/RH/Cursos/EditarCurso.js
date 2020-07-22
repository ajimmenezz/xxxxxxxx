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
        this.alerta = new Alertas('modal-alerta-error');
        this.tablaCursos;
        this.datosTablaCursos;
        this.tablaTemarios;
    }

    init(idCurso, tablaCursos) {
        this.idCurso = idCurso;
        this.tablaCursos = tablaCursos;
        this.getDatosCurso();
        this.initDatosCurso();
        this.initTemarios();
    }

    events() {
        this.eventRegresarCursos();
        this.eventDatosCurso();
        this.eventTemarios();
    }

    getDatosCurso() {
        let _this = this;
        Helper.enviarPeticionServidor('panel-cursos', 'Administracion_Cursos/Obtener-Curso', {id: this.idCurso}, function (respond) {
            console.log(respond.data.infoCurso.dataCurso);
            _this.datosCurso.idCurso = _this.idCurso;
            _this.datosCurso.imagen = respond.data.infoCurso.dataCurso.curso.imagen;
            _this.datosCurso.nombre = respond.data.infoCurso.dataCurso.curso.nombre;
            _this.datosCurso.descripcion = respond.data.infoCurso.dataCurso.curso.descripcion;
            _this.datosCurso.url = respond.data.infoCurso.dataCurso.curso.url;
            _this.datosCurso.certificado = respond.data.infoCurso.dataCurso.curso.idTipoCertificado;
            _this.datosCurso.costo = respond.data.infoCurso.dataCurso.curso.costo;
            _this.datosCurso.temarios = respond.data.infoCurso.dataCurso.temas;
            _this.setDatosCurso();
            _this.setTemarios();
        });
    }

    initDatosCurso() {
        this.inputNombre = $(`#input-edit-nombre`);
        this.textDescripcion = $(`#textarea-edit-descripcion`);
        this.inputUrl = $(`#input-edit-url`);
        this.selectCertificado = new SelectBasico('select-edit-certificado');
        this.inputCosto = $(`#input-edit-costo`);
        this.file = new FileNativo('file-edit-imagen', 'contenedor-edit-imagen');
        this.formulario = $(`#form-edit-datos-curso`);
    }

    initTemarios() {
        let botonFilaTemario = [
            {
                targets: 3,
                data: null,
                render: function (data, type, row, meta) {
                    return `<i class='fa fa-trash delete-temario' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'></i>`;
                }
            }
        ];
        let configTablaTemario = {
            info: false,
            pageLength: 3,
            searching: false,
            lengthChange: false,
            columnas: botonFilaTemario
        };
        this.tablaTemarios = new TablaRender('tabla-edita-temarios', [], configTablaTemario);
    }

    setDatosCurso() {
        this.inputNombre.val(this.datosCurso.nombre);
        this.textDescripcion.val(this.datosCurso.descripcion);
        this.inputUrl.val(this.datosCurso.url);
        this.inputCosto.val(parseInt(this.datosCurso.costo) > 0 ? this.datosCurso.costo : '');
        this.selectCertificado.definirValor(this.datosCurso.certificado);
        this.file.setImage(this.datosCurso.imagen);
    }

    setTemarios() {
        let _this = this;
        $.each(_this.datosCurso.temarios, function (key, value) {
            _this.tablaTemarios.agregarDatosFila([value['id'], value['nombre'], value['porcentaje'] + '%', '']);
        });
    }

    eventRegresarCursos() {
        let _this = this;
        $('#btn-regresar-cursos').on('click', function () {
            Helper.ocultarElemento('seccion');
            Helper.quitarContenidoElemento('seccion');
            Helper.mostrarElemento('seccion-cursos');
            _this.updateTablaCursos();
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

    eventTemarios() {
        let _this = this;

        $('#btn-edti-agregar-temario').on('click', function (e) {
            let curso = $('#input-edit-temario').val();

            if (curso) {
                let temarios = _this.tablaTemarios.datosTabla();
                let porcentaje = temarios.length ? 100 / (temarios.length + 1) : 100;

                let nuevoTemario = {
                    idCurso: _this.idCurso,
                    tema: curso,
                    porcentaje: parseFloat(porcentaje.toFixed(2))
                };
                console.log(nuevoTemario);
//                Helper.enviarPeticionServidor('panel-cursos', 'Administracion_Cursos/Obtener-Curso', {id: this.idCurso}, function (respond) {
                        _this.tablaTemarios.limpiartabla();
                        _this.updateTablaTemarios(temarios,{id: 2, nombre : curso, porcentaje : porcentaje.toFixed(2) + '%'});
                        $('#input-temario').val('');
//                });
            }
        });

        _this.tablaTemarios.addListenerOnclik('.delete-temario', function (dataRow, fila) {
            let temarios = _this.tablaTemarios.datosTabla();
            if (temarios.length > 1) {
                _this.tablaTemarios.eliminarFila(fila);
                temarios = _this.tablaTemarios.datosTabla();
                let porcentaje = temarios.length ? 100 / temarios.length : 100;
                _this.updateTablaTemarios(temarios, {porcentaje : porcentaje.toFixed(2) + '%'});
            }else{
                _this.alerta.mostrarMensajeError('alerta-temarios','No se puede dejar sin temas al curso');
            }
        });
    }

    updateTablaTemarios(temarios = [], nuevoTemario = []) {
        let _this = this;

        _this.tablaTemarios.limpiartabla();
        console.log(nuevoTemario);
        if (nuevoTemario.curso) {
            _this.tablaTemarios.agregarDatosFila([nuevoTemario.id,nuevoTemario.nombre, nuevoTemario.porcentaje]);
        }

        $.each(temarios, function (key, value) {
            _this.tablaTemarios.agregarDatosFila([value[0],value[1],nuevoTemario.porcentaje]);
        });
    }

    uploadFile(datos = {}){
        let _this = this;
        this.file.uploadServer('Administracion_Cursos/Editar-Curso', datos, function (respond) {
            console.log(respond);
            if (respond.success) {
                _this.datosTablaCursos = respond.data;
                _this.showMensajeExito();
                _this.hiddenBotonesDatos(true);
            } else {
                _this.showMensajeError();
            }
        });
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

    updateTablaCursos() {
        let listaCursos = [];
        this.tablaCursos.limpiartabla();

        $.each(this.datosTablaCursos.cursos, function (key, value) {
            listaCursos.push([
                value['Id'],
                value['Nombre'],
                value['Descripcion'],
                value['Participantes'],
                value['Estatus'] === '1' ? 'Activo' : 'Inactivo',
                null
            ]);
        });
        this.tablaCursos.agregarContenidoTabla(listaCursos);
        this.tablaCursos.reordenarTabla(0, 'asc');
    }

}


