<?php

namespace Librerias\Factorys;

use Librerias\PaquetesProyectos\ProyectoGapsi as Gapsi;
use Librerias\PaquetesProyectos\ProyectoAdist as Adist;

class FactorySucursal {
    
    private $proyecto;    
    
    public function __construct() {        
    }
    
    public function getProject(string $tipo, string $idProyecto) {
        
        switch ($tipo) {
            case 'Adist':
                $this->proyecto = new Adist($idProyecto);
                break;
            case 'Gapsi':
                $this->proyecto = new Gapsi($idProyecto);
                break;

            default:
                throw new Exception('No existe el objeto de tipo : '. $tipo);
                break;
        }
        
        return $this->proyecto;
    }
}
