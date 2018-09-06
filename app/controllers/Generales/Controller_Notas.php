<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Solicitud
 *
 * @author Freddy
 */
class Controller_Notas extends Base {

    private $Notas;

    public function __construct() {
        parent::__construct();
        $this->Notas = \Librerias\Generales\Notas::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {    
            case 'Guardar_Nota_Servicio':                          
                $resultado = $this->Notas->setNotaServicio($this->input->post());
                break;            
            case 'ActualizaNotas':                          
                $resultado = $this->Notas->actualizaNotas($this->input->post());
                break;            
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
