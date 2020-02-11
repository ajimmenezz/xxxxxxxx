class Solucion {

    constructor() {
        this.peticion = new Utileria();
        this.evento = new Base();
        this.formulario = null;
        this.selects = {};
        this.tablas = {};
        this.inputs = {};
        this.file = {};
    }

    iniciarElementos() {
        this.peticion.insertarContenido('Solucion', this.datos.html.solucion);
        this.crearSelects();
//        this.crearTablas();
        this.crearFiles()
    }

    crearSelects() {
        let _this = this;
        let selects = [
            'selectOperacionInstalaciones',
            'selectModeloInstalaciones',
            'selectAreaAtencionInstalaciones'
        ];
        $.each(selects, function (index, value) {
            _this.selects[value] = new SelectBasico(value);
        });

        $.each(_this.selects, function (index, value) {
            value.iniciarSelect();
        });
    }

    crearTablas() {
        let _this = this;
        let tablas = [
            'data-table-equipos-instalaciones'
        ];

        $.each(tablas, function (index, value) {
            _this.tablas[value] = new TablaBasica(value);
        });

        $.each(_this.tablas, function (index, value) {
            value.iniciarTabla();
        });
    }

    crearFiles() {
        this.file = new FileUpload_Basico('agregarEvidenciaEquipo', {url: 'Seguimiento/Servicio/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        this.file.iniciarFileUpload();
    }

    setDatos(datos) {
        this.datos = datos;
    }

    listener(callback) {
        let _this = this;

        _this.selects['selectOperacionInstalaciones'].evento('change', function () {
            if (_this.selects['selectOperacionInstalaciones'].obtenerValor() === '1') {
                $(`#divIlegible`).removeClass('hidden');
                $(`#inputSerieInstalaciones`).prop('disabled', false);
            } else {
                $(`#divIlegible`).addClass('hidden');
                $(`#inputSerieInstalaciones`).prop('disabled', true);
            }
        });

        $('#inputIlegibleInstalciones').change(function () {
            if (!$(this).is(':checked')) {
                $(`#divAdjuntos`).addClass('hidden');
            } else {
                $(`#divAdjuntos`).removeClass('hidden');
            }
        });

        $('#btnAgregarEquipoInstalacion').off("click");
        $('#btnAgregarEquipoInstalacion').on("click", function () {
            let arrayCampos = [
                {'objeto': '#selectOperacionInstalaciones', 'mensajeError': 'Falta seleccionar el campo operación.'},
                {'objeto': '#selectAreaAtencionInstalaciones', 'mensajeError': 'Falta seleccionarel campo Área de Atención.'},
                {'objeto': '#inputPuntoInstalaciones', 'mensajeError': 'Falta seleccionar el campo Punto.'},
                {'objeto': '#selectModeloInstalaciones', 'mensajeError': 'Falta seleccionar el campo Modelo.'},
                {'objeto': '#inputSerieInstalaciones', 'mensajeError': 'Falta escribir el campo Serie.'}
            ];

            if ($('#inputIlegibleInstalciones').is(':checked')) {
                arrayCampos.push({'objeto': '#agregarEvidenciaEquipo', 'mensajeError': 'Falta seleccionar el campo adjuntos.'});
            }

            let camposFormularioValidados = _this.evento.validarCamposObjetos(arrayCampos, '.errorInstalaciones');

            if (camposFormularioValidados) {
                console.log('pumas');
            }
        });
    }

}

