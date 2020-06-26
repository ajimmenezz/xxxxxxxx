<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Calendario
 *
 * @author Alonso
 */
class Controller_Calendario extends Base{
    
    private $Calendario;


    public function __construct() {
        parent::__construct();
        $this->Calendario = \Librerias\Generales\Calendario::factory();
    }
    
    
    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Servicios':
                $resultado = $this->Calendario->getServiciosCalendario($this->input->post());
                break;            
            case 'PermisosCalendario':
                $resultado = $this->Calendario->getCalendarPermissions();
                break;
            case 'DetallesServicio':
                $resultado = $this->Calendario->getDetallesServicioCalendario($this->input->post());
                break;
            case 'ActualizaTentativa':
                $resultado = $this->Calendario->actualizaTentativa($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
