<?php

use Controladores\Controller_Base as Base;

class Controller_GestorProyectos extends Base {

    private $gestorProyecto;

    public function __construct() {
        parent::__construct();
//        $this->gestorProyecto = new \Librerias\Gapsi\GerstorProyectosGAPSI();
        $this->gestorProyecto = new \Librerias\Gapsi\GestorProyectos();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'tipoProyecto':
                $resultado = $this->gestorProyecto->getProjectsInfo($this->input->post());
                break;
            case 'infoProyecto':
                $resultado = $this->gestorProyecto->getProjectInfo($this->input->post());
                break;
            case 'filtroPrincipal':
                $resultado['listaProyectos'] = $this->gestorProyecto->getListProjects($this->input->post());
                $resultado['tipoProyectos'] = $this->gestorProyecto->getProjectTypes($this->input->post());
                break;
            case 'getDatosProyectos':
                $resultado = $this->gestorProyecto->getInfoProyecto($this->input->post());
                break;
            case 'getDatosTipoProyecto':
                $resultado = $this->gestorProyecto->getDatosTipoProyecto($this->input->post());
                break;
        }

        echo json_encode($resultado);
    }

}
