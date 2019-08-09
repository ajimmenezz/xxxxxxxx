<?php

namespace Librerias\V2\PaquetesSucursales;

use Modelos\Modelo_GestorSucursales as Modelo;

class GestorSucursales {
    
    private $DBModelo_GestorSucursales;

    public function __construct() {
        $this->DBModelo_GestorSucursales = new Modelo();
    }
    
    public function getSucursales(string $idCliente = '') {
        $consulta = array();
        
        if(!empty($idCliente)){
            $consulta = $this->DBModelo_GestorSucursales->getSucursalesCliente($idCliente);
        }else{
            $consulta = $this->DBModelo_GestorSucursales->getSucursales();            
        }
        
        return $consulta;
    }

}
