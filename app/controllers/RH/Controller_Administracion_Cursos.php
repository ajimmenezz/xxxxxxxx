<?php

use Controladores\Controller_Base as Base;
use Librerias\RH\Cursos as Cursos;

class Controller_Administracion_Cursos extends Base{
    
    private $curso;
    public function __construct() {
        parent::__construct();
        $this->curso = new Cursos();
    }
    
    public function manejarEvento(string $evento = null) {
        $resultado = array();
        switch ($evento) {
            case 'Nuevo-Curso':                
                $resultado = $this->curso->prueba($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
