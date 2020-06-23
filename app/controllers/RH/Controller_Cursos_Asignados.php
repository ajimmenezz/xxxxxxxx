<?php

use Controladores\Controller_Base as Base;

class Controller_Cursos_Asignados extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function manejarEvento(string $evento = null) {
        $resultado = array();
        // switch ($evento) {
        //     case 'Nuevo-Curso':                
        //         $resultado = $this->curso->prueba($this->input->post());
        //         // $this->load->view('RH/NuevoCurso');
              
        //     break;
        // }
        echo json_encode($resultado);
    }

}
