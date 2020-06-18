<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_SLA extends Base {

    private $sla;

    public function __construct() {
        parent::__construct();
        $this->sla = \Librerias\Poliza\SLA::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'ReporteExcel':
                $resultado = $this->sla->getExcel($this->input->post());
                break;
            case 'Filtro':
                $resultado = $this->sla->getSla($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
