<?php

use Librerias\Gapsi\Proyecto as Proyecto;

class Controller_Proyecto extends CI_Controller {
    
    private $proyecto;
    
    public function getDatosProyecto() {
        
        
        $this->proyecto = new Proyecto();
    }

}
