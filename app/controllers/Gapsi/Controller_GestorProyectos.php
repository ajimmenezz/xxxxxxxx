<?php

use Controladores\Controller_Base as Base;

class Controller_GestorProyectos  extends Base {

    private $gestorProyecto;

    public function __construct() {
        parent::__construct();
        $this->gestorProyecto = new \Librerias\Gapsi\GerstorProyectos();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'tipoProyecto':
                $resultado = $this->gestorProyecto->getProjectsInfo($this->input->post());
                break;
            case 'infoProyecto':
                $resultado = $this->gestorProyecto->getProjectInfo($this->input->post());
                break;
        }
        
        echo json_encode($resultado);
    }

}
