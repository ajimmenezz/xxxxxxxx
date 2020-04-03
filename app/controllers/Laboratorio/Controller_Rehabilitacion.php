<?php

use Controladores\Controller_Base as Base;
use Librerias\Almacen\Inventario as Inventario;

class Controller_Rehabilitacion extends Base {

    private $rehabilitacion;

    public function __construct() {
        parent::__construct();
        $this->rehabilitacion = \Librerias\Laboratorio\Rehabilitacion::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        try {
            switch ($evento) {
                case 'InfoBitacora':
                    $resultado = $this->rehabilitacion->getModelo($this->input->post());
                    break;
                case 'SetComentario':
                    $resultado = $this->rehabilitacion->setComentario($this->input->post());
                    break;
                default:
                    $resultado = FALSE;
                    break;
            }
            echo json_encode($resultado);
        } catch (\Exception $ex) {
            echo json_encode(array('response' => 400, 'message' => $ex->getMessage()));
        }
    }

}
