 class factoryServicio{
     
     constructor() {
        this.objeto = null;
    }

    getInstance(tipoServicio, datos = {}) {
        switch (tipoServicio) {
            case 'Instalaciones':
                this.objeto = new ServicioInstalaciones();
                console.log(tipoServicio);
                break;            
            default:
                throw (`No se encontro el servicio ${tipoServicio}`);                
                break;
        }
        return this.objeto;
    }
     
 }

