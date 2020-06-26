<?php

use Controladores\Controller_Base as Base;

class Controller_ProyectosEspecialesV2 extends Base {

    private $reporte;

    public function __construct() {
        parent::__construct();
        $this->reporte = new \Librerias\Reportes\PEV2();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'GeneraPDF':
                $resultado = $this->reporte->generaPDF($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
