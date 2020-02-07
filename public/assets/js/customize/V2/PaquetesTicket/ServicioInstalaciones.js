class ServicioInstalaciones extends IServicio {
    constructor() {
        super();

    }

    setDatos(datos) { 
        console.log(datos);
        if (datos.hasOwnProperty('Error')) {
            console.log(datos);
        }
    }

}


