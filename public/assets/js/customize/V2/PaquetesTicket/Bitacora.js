class Bitacora {

    constructor() {
        this.peticion = new Utileria();
    }

    setDatos(datos) {
        this.datos = datos;
        this.peticion.insertarContenido('BitacoraProblemas', this.datos.html.bitacora);
    }

}

