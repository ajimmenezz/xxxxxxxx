<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo;

class Modelo_AlmacenVirtual extends Modelo {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getMaterial(string $idUsuario) {
//        return $this->ejecutaFuncion('call getInventoryByUser('.$idUsuario.')');        
        return $this->ejecutaFuncion('call getInventoryByUser(49)');                  
    }
}
