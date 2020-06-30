<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Buscar
 *
 * @author Alonso
 */
class Controller_Agenda extends Base
{

    private $agenda_library;

    public function __construct()
    {
        parent::__construct();
        $this->agenda_library = \Librerias\Generales\Agenda::factory();
    }


    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'LoadGoogleEvents':
                $resultado = $this->agenda_library->loadGoogleEvents($this->input->post());
                break;
            case 'LoadPendingServices':
                $resultado = $this->agenda_library->loadPendingServices();
                break;
            case 'LoadProgramServiceForm':
                $resultado = $this->agenda_library->loadProgramServiceForm($this->input->post());
                break;
            case 'SaveEvent':
                $resultado = $this->agenda_library->saveEvent($this->input->post());
                break;

            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
