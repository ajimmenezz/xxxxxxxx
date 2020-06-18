<?php

use Controladores\Controller_Base as Base;

class Controller_TareasTecnico extends Base {

    private $proyecto;

    public function __construct() {
        parent::__construct();
        $this->proyecto = new \Librerias\Proyectos\Proyecto2('tareasTecnico');
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Datos_Proyecto':
                $resultado = $this->proyecto->obtenerDatosProyecto($this->input->post());
                break;
            case 'Generar_Actividad':
                $resultado = $this->proyecto->generarActividad($this->input->post());
                break;            
            case 'Agregar_Nodo_Actividad':
                $resultado = $this->proyecto->agregarNodoEnActividad($this->input->post());
                break;
            case 'Eliminar_Nodo_Actividad':
                $resultado = $this->proyecto->eliminarNodoDeActividad($this->input->post());
                break;
            case 'Actualizar_Actividad':
                $resultado = $this->proyecto->actualizarActividad($this->input->post());
                break;
            case 'Eliminar_Actividad':
                $resultado = $this->proyecto->eliminarActividad($this->input->post());
                break;
            case 'Obtener_Tareas_Asignadas':
                $resultado = $this->proyecto->obtenerTareasAsignadasTecnico($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
