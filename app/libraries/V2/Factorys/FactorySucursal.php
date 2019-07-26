<?php

namespace Librerias\V2\Factorys;

use Librerias\V2\PaquetesSucursales\SucursalGapsi as Gapsi;
use Librerias\V2\PaquetesSucursales\SucursalAdist as Adist;

class FactorySucursal {
    
    private $sucursal;    
    
    public function __construct() {        
    }
    
    public function getSucursal(string $tipo, string $idSucursal) {
        switch ($tipo) {
            case 'Adist':
                $this->sucursal = new Adist($idSucursal);
                break;
            case 'Gapsi':
                $this->sucursal = new Gapsi($idSucursal);
                break;

            default:
                throw new Exception('No existe el objeto de tipo : '. $tipo);
                break;
        }
        
        return $this->sucursal;
    }
}
