class ServicioInstalaciones extends IServicio {
    constructor() {
        super();

    }

    setDatos(datos) {         
        if (datos.hasOwnProperty('Error')) {
            console.log(datos);
        }
    }

}


