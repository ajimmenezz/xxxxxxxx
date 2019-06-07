class Proyecto {

    constructor(nombre) {
        this.nombre = nombre;
        this.objeto = $(`#${this.nombre}`);
        this.tipo;
    }
    
    obtenerTipo(){
        return this.tipo;
    }
    
    obtenerDatos(){
        
    }

}


