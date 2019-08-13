<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_NodoRedes extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setNodo(string $idServicio,array $datos) {
        
        $consulta = $this->insertar('insert into t_redes_nodos values(
                           "",
                           '.$idServicio.',
                           '.$datos['area'].',
                           "'.$datos['nodo'].'",
                           '.$datos['switch'].',
                           '.$datos['numSwitch'].',
                           "ninguno"    
                         )');
        
//        return $consulta;
        return '9';
    }
    
    public function setMaterialNodo(string $idNodo, array $material) {
        $consulta = $this->insertar('insert into t_redes_nodos values(
                           "",
                           '.$idServicio.',
                           '.$datos['area'].',
                           "'.$datos['nodo'].'",
                           '.$datos['switch'].',
                           '.$datos['numSwitch'].',
                           "ninguno"    
                         )');
    }
}
