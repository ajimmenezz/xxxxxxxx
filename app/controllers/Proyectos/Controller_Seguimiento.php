<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base {

    private $proyecto;

    public function __construct() {
        parent::__construct();
        $this->proyecto = new \Librerias\Proyectos\Proyecto2('seguimiento');
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Datos_Proyecto':
                $resultado = $this->proyecto->obtenerDatosProyecto($this->input->post());
                break;
            case 'Actualizar_Datos_Proyecto':
                $resultado = $this->proyecto->actualizarDatosGenerales($this->input->post());
                break;
            case 'Actualizar_Tarea':
                $resultado = $this->proyecto->actualizarTarea($this->input->post());
                break;
            case 'Eliminar_Tarea':
                $resultado = $this->proyecto->eliminarTarea($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
