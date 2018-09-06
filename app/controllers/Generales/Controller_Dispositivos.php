<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Buscar
 *
 * @author Alonso
 */
class Controller_Dispositivos extends Base{        

    private $miradore;    

    public function __construct() {
        parent::__construct();
        $this->miradore = \Librerias\Generales\Miradore::factory();        
    }
    
    
    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'GetMiradoreInfo':
                $resultado = $this->miradore->getMiradoreInfo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
