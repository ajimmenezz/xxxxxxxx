<?php

use Controladores\Controller_Base as Base;

class Controller_Ayuda extends Base {

    public function __construct() {
        parent::__construct();
    }
    
    //Encargaco de manejar las peticiones del cliente
    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Ayuda_Proyectos':
                $resultado = $this->SECCIONES->getAyuda('Ayuda_Proyectos');
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
