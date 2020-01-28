
class Controlador_Ticket {

    constructor(clave) {
        this.clave = clave;
        this.datos = {};
        this.componentes = {};
        this.objetos = {};
        this.peticion = new Utileria();
        this.serviciosPoliza = new Seguimiento();
    }


    setComponentes() {
        let _this = this;
    }

}