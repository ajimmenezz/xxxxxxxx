<?php

namespace Librerias\V2\Factorys;

use Librerias\V2\PaquetesServicios\ServicioGapsi as Gapsi;
use Librerias\V2\PaquetesServicios\ServicioAdist as Adist;

class FactoryServicio {
    
    private $servicio;    
    
    public function __construct() {        
    }
    
    public function getServicio(string $tipo, string $servicio) {
        switch ($tipo) {
            case 'Adist':
                $this->servicio = new Adist($servicio);
                break;
            case 'Gapsi':
                $this->servicio = new Gapsi($servicio);
                break;

            default:
                throw new Exception('No existe el objeto de tipo : '. $tipo);
                break;
        }
        
        return $this->servicio;
    }
}
