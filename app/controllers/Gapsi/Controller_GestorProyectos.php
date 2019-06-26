<?php

use Controladores\Controller_Base as Base;

class Controller_GestorProyectos extends Base {

    private $gestorProyecto;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->gestorProyecto = new \Librerias\Gapsi\GerstorProyectosGAPSI();
        $this->catalogo = \Librerias\Gapsi\Catalogos::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'tipoProyecto':
                $resultado = $this->gestorProyecto->getProjectsInfo($this->input->post());
                break;
            case 'infoRegistro':
                $resultado = $this->catalogo->cargaGasto($this->input->post());
                break;
            case 'listaRegistros':
                $resultado = $this->gestorProyecto->getProjectRecords($this->input->post());
                break;
            case 'filtroPrincipal':
                $resultado['listaProyectos'] = $this->gestorProyecto->getListProjects($this->input->post());
                $resultado['tipoProyectos'] = $this->gestorProyecto->getProjectTypes($this->input->post());
                break;
        }

        echo json_encode($resultado);
    }

}
