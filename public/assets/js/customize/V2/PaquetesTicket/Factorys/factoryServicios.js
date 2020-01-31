 class factoryServicio{
     
     constructor() {
        this.objeto = undefined;
    }

    getInstance(tipoServicio, datos = {}) {
        
        switch (tipoServicio) {
            case 'Instalaciones':
                this.objeto = new ServicioInstalaciones();
                break;                        
        }
        return this.objeto;
    }
     
 }

