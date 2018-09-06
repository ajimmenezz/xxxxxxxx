<?php

use Controladores\Controller_Base as Base;

class Controller_Proyectos extends Base {

    private $proyectos;

    public function __construct() {
        parent::__construct();
        $this->proyectos = new \Librerias\Proyectos2\Proyecto();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Tipo':
                $resultado = $this->catalogo->setTipoProyecto($this->input->post('tipo'), $this->input->post('descripcion'));
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
