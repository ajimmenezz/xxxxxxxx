<?php

use Controladores\Controller_Base as Base;

class Controller_Areas extends Base {

    private $catalogo;
    private $area;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->area = \Librerias\RH\Areas::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nueva_Area':
                $resultado = $this->catalogo->catAreas('1', array($this->input->post('nombre'), $this->input->post('descripcion')));
                break;
            case 'Actualizar_Area':
                $resultado = $this->catalogo->catAreas('2', array($this->input->post('id'), $this->input->post('nombre'), $this->input->post('descripcion'), $this->input->post('estatus')));
                break;
            case 'MostrarFormularioArea':
                $resultado = $this->area->mostrarFormularioArea($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
