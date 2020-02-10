class Solucion {

    constructor() {
        this.peticion = new Utileria();
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
            'selectModeloInstalaciones'
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
        console.log('pumas');
        this.file = new FileUpload_Basico('agregarEvidenciaEquipo', {url: 'Seguimiento/Servicio/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        this.file.iniciarFileUpload();
    }

    setDatos(datos) {
        this.datos = datos;
    }

    listener(callback) {
        let _this = this;
        let evento = new Base();


    }

}

