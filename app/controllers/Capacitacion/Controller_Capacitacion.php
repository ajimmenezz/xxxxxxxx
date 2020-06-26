<?php

use Controladores\Controller_Base as Base;

class Controller_Capacitacion extends Base{
    
    private $videos;
    
    public function __construct() {
        parent::__construct();     
        $this->videos = new \Librerias\Capacitacion\Videos();
    }
    
    /*
     * Se encarga se recibir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {

        switch ($evento) {
            case 'CargaVideos':                                
                $resultado = $this->videos->cargaVideos($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }
    
}
