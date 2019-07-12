<?php

namespace Librerias\V2\Factorys;

use Librerias\V2\PaquetesProyectos\ProyectoGapsi as Gapsi;
use Librerias\V2\PaquetesProyectos\ProyectoAdist as Adist;

class FactoryProject {
    
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
