<?php

namespace Librerias\V2\Factorys;

use Librerias\V2\PaquetesCatalogos\Catalogo_Motivos_Permisos as Motivos;
use Librerias\V2\PaquetesCatalogos\Catalogo_Rechazo_Permisos as Rechazos;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;

class FactoryCatalogos {
    
    private $catalogo;    
    
    public function __construct() {    
        
    }
    
    public function getInstancia() {
        
    }
    
    public function getCatalogo(string $tipo) {
        var_dump(Usuario::getId());
        switch ($tipo) {
            case 'CatalogoMotivoPermisos':
                
                $this->catalogo= new Motivos();
                break;
            case 'CatalogoRechazoPermisos':
                $this->catalogo= new Rechazos();
                break;

            default:
                throw new Exception('No existe el objeto de tipo : '. $tipo);
                break;
        }
        
        return $this->catalogo;
    }
}
