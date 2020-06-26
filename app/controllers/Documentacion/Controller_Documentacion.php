<?php

use Controladores\Controller_Base as Base;

class Controller_Documentacion extends Base {

    private $Documentacion;

    public function __construct() {
        parent::__construct();
        $this->Documentacion = \Librerias\Documentacion\Documentacion::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'ValidarCartaResponsiva':
                $resultado = $this->Documentacion->validarCartaResponsiva($this->input->post());
                break;
            case 'GuardarFirmaCartaResponsiva':
                $resultado = $this->Documentacion->guardarFirmaCartaResponsiva($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
