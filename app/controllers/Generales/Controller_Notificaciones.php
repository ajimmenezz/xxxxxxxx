<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Notificaciones
 *
 * @author Freddy
 */
class Controller_Notificaciones extends Base{
    
    private $Notificaion;


    public function __construct() {
        parent::__construct();
        $this->Notificaion = \Librerias\Generales\Notificacion::factory();
    }
    
    
    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Abierta':
                $resultado = $this->Notificaion->notificacionVista($this->input->post('Id'));
                break;            
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
