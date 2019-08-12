<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Censo extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getCensoComponente(string $idSucursal, string $componente) {        
//        $consulta = $this->ejecutaFuncion('call getInventoryFromSucursalItem('.$idSucursal.',"'.$componente.'")');
        $consulta = $this->ejecutaFuncion('call getInventoryFromSucursalItem(10,"'.$componente.'")');
        return $consulta;
    }
}
