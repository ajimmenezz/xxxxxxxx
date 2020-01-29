 class factoryServicio{
     
     constructor() {
        this.objeto = undefined;
    }

    getInstance(tipoServicio, datos = {}) {
        
        switch (tipoServicio) {
            case 'Instalaciones':
                this.objeto = new ServicioInstalaciones();
                console.log(tipoServicio);
                break;                        
        }
        return this.objeto;
    }
     
 }

