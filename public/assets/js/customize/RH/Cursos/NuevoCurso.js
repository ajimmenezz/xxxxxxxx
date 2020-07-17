class NuevoCurso {

    constructor() {
        this.tablaTemario = null;
        this.tablaParticipantes = null;
        this.selectParticipante = null;
        this.selectCertificado = null;
        this.wizarNuevoCurso = null;
        this.file = null;
    }

    init() {
        this.initTablas();
        this.initWizard();
        this.initFileUpload();
        this.initSelects();
    }

    events() {
        this.eventCancelWizard();
        this.eventWizardPaso1();
        this.eventWizardPaso2();
        this.eventWizardPaso3();
    }

    initTablas() {
        let botonesFilaNuevoTemario = [
            {
                targets: 2,
                data: null,
                render: function (data, type, row, meta) {
                    return `<i class='fa fa-trash delete-temario' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'></i>`;
                }
            }
        ];
        let botonesFilaNuevoParticipante = [
            {
                targets: 2,
                data: null,
                render: function (data, type, row, meta) {
                    return `<i class='fa fa-trash delete-participante' style='cursor: pointer; margin: 5px; font-size: 17px;  color: red;'></i>`;
                }
            }
        ];
        let configTablaNuevoTemario = {
            info: false,
            pageLength: 3,
            searching: false,
            lengthChange: false,
            columnas: botonesFilaNuevoTemario
        };

        let configTablaNuevoParicipante = {
            info: false,
            pageLength: 3,
            searching: false,
            lengthChange: false,
            columnas: botonesFilaNuevoParticipante
        };

        this.tablaTemario = new TablaRender('tabla-nuevo-curso-temarios', [], configTablaNuevoTemario);
        this.tablaParticipantes = new TablaRender('tabla-nuevo-cursos-participantes', [], configTablaNuevoParicipante);
    }

    initSelects() {
        this.selectParticipante = new SelectBasico('select-participante');
        this.selectCertificado = new SelectBasico('select-certificado');
    }

    initWizard() {
        let validarExtrasWizard = {
            '1': [this.tablaTemario]
        };

        this.wizarNuevoCurso = new WizardValidation('wizard', {index: [0, 1, 2], validate: validarExtrasWizard});
    }

    initFileUpload() {
        this.file = new FileNativo('agregar-imagen', 'img-curso');
    }

    eventCancelWizard() {
        let _this = this;
        $('.btn-cancel-wizard').on('click', function () {
            _this.file.clear();
            Helper.ocultarElemento('seccion');
            Helper.quitarContenidoElemento('seccion');
            Helper.mostrarElemento('seccion-cursos');
        });
    }

    eventWizardPaso1() {
        let _this = this;

        $('#btn-imagen-curso').on('click', function () {
            $('#agregar-imagen').click();
        });

        _this.file.addListenerChange(["jpeg", "png"]);
    }

    eventWizardPaso2() {
        let _this = this;

        $('#btn-agregar-nuevo-temario').on('click', function (e) {
            let curso = $('#input-temario').val();

            if (curso) {
                let temarios = _this.tablaTemario.datosTabla();
                let porcentaje = temarios.length ? 100 / (temarios.length + 1) : 100;

                _this.tablaTemario.limpiartabla();
                _this.tablaTemario.agregarDatosFila([curso, porcentaje.toFixed(2) + '%']);
                $.each(temarios, function (key, value) {
                    _this.tablaTemario.agregarDatosFila([value[0], porcentaje.toFixed(2) + '%']);
                });
            }
            $('#input-temario').val('');
        });

        _this.tablaTemario.addListenerOnclik('.delete-temario', function (dataRow, fila) {
            _this.tablaTemario.eliminarFila(fila);
        });
    }

    eventWizardPaso3() {
        let _this = this;

        $('#btn-nuevo-puestoParticipante').on('click', function (e) {
            let participante = _this.selectParticipante.obtenerTexto();
            let idParticipante = _this.selectParticipante.obtenerValor();
            let datosTabla = _this.tablaParticipantes.datosTabla();
            let listId = [];

            $.each(datosTabla, function (key, value) {
                listId.push(value[0]);
            });

            if (idParticipante && listId.indexOf(idParticipante) === -1) {
                _this.tablaParticipantes.agregarDatosFila([idParticipante, participante]);
            }
            _this.selectParticipante.limpiarElemento();
        });

        _this.tablaParticipantes.addListenerOnclik('.delete-participante', function (dataRow, fila) {
            _this.tablaParticipantes.eliminarFila(fila);
        });

        $('#btn-generar-curso').on('click', function (e) {

            let datosParticipantes = _this.tablaParticipantes.datosTabla();

            if (datosParticipantes.length === 0) {
                _this.wizarNuevoCurso.showMensaje(' Debes ingresar al menos un pariticipante para poder generar el curso', 2);
                return false;
            }

            let datosCurso = [
                $('#input-nombreCurso').val(),
                $('#textarea-descripcionCurso').val(),
                $('#input-urlCurso').val(),
                _this.selectCertificado.obtenerValor(),
                $('#input-costoCurso').val() ? $('#input-costoCurso').val() : "00.00"
            ];

            let listaTemario = [];
            $.each(_this.tablaTemario.datosTabla(), function (key, value) {
                listaTemario.push({tema: value[0], porcentaje: parseFloat(value[1].replace('%', ''))})
            });

            let listaParticipantes = [];
            $.each(datosParticipantes, function (key, value) {
                listaParticipantes.push(value[0]);
            });

            let datos = {
                'datosCurso': datosCurso,
                'temarios': listaTemario,
                'participantes': listaParticipantes
            };


            _this.file.uploadServer('Administracion_Cursos/Nuevo-Curso', datos, function (respond) {
                console.log(respond);
            });
        });
    }

}


