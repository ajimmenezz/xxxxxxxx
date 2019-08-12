<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenUsuario;
use Librerias\V2\PaquetesSucursales\SucursalAdist as Sucursal;
use Librerias\V2\PaquetesSucursales\Censo as Censo;

class GestorDatosServicio {
    
    private $almacenUsuario;
    private $sucursal;
    private $censo;
    
    public function __construct() {
        
    }
    
    public function getInformacion(string $servicio, array $datos = array()) {
        $informacion = array();        
        switch ($servicio) {
            case 'Cableado':                  
                $this->almacenUsuario = new AlmacenUsuario();                
                $this->sucursal = new Sucursal($datos['datosServicio']['Sucursal']);
                $this->censo = new Censo($this->sucursal);
                $informacion['materialUsuario'] = $this->almacenUsuario->getAlmacen(); 
                $informacion['areasSucursal'] = $this->sucursal->getAreas();
                $informacion['censoSwitch'] = $this->censo->getRegistrosComponente('switch');
                
                    
                break;

            default:
                break;
        }
        
        return $informacion;
    }
}
