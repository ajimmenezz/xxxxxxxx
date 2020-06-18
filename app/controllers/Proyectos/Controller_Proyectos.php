<?php

use Controladores\Controller_Base as Base;

class Controller_Proyectos extends Base {
    
    private $proyecto;

    public function __construct() {
        parent::__construct();        
        $this->proyecto = new \Librerias\Proyectos\Proyecto2('proyectos');
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Proyecto':
                $resultado = $this->proyecto->nuevoProyecto($this->input->post());
                break;
            case 'Datos_Proyecto':
                $resultado = $this->proyecto->obtenerDatosProyecto($this->input->post());
                break;
            case 'Actualizar_Datos_Proyecto':
                $resultado = $this->proyecto->actualizarDatosGenerales($this->input->post());
                break;
            case 'Guardar_Nodo_Alcance':
                $resultado = $this->proyecto->guardarNodoAlcance($this->input->post());
                break;
            case 'Eliminar_Nodo_Alcance':
                $resultado = $this->proyecto->eliminarNodoAlcance($this->input->post());
                break;
            case 'Guardar_Asistente':
                $resultado = $this->proyecto->guardarAsistente($this->input->post());
                break;
            case 'Eliminar_Asistente':
                $resultado = $this->proyecto->eliminarAsistente($this->input->post());
                break;
            case 'Generar_Solicitud':
                $resultado = $this->proyecto->generarSolicitudPersonal($this->input->post());
                break;
            case 'Nueva_Tarea':
                $resultado = $this->proyecto->generarNuevaTarea($this->input->post());
                break;
            case 'Actualizar_Tarea':
                $resultado = $this->proyecto->actualizarTarea($this->input->post());
                break;
            case 'Eliminar_Tarea':
                $resultado = $this->proyecto->eliminarTarea($this->input->post());
                break;
            case 'Agregar_Complejo':
                $resultado = $this->proyecto->agregarComplejo($this->input->post());
                break;
            case 'Eliminar_Complejo':
                $resultado = $this->proyecto->eliminarComplejoProyecto($this->input->post());
                break;
            case 'Eliminar_Proyecto':
                $resultado = $this->proyecto->eliminarProyecto($this->input->post());
                break;
            case 'Solicitud_Material':
                $resultado = $this->proyecto->generarSolicitudMaterial($this->input->post());
                break;
            case 'Iniciar_Proyecto':
                $resultado = $this->proyecto->iniciarProyecto($this->input->post());
                break;
            case 'Reporte_Inicio_Proyecto':
                $resultado = $this->proyecto->generarPDFInicioProyecto($this->input->post());
                break;
            case 'Reporte_Material':
                $resultado = $this->proyecto->generarPDFMaterial($this->input->post());
                break;
            case 'Carga_Areas_By_Concepto':
                $resultado = $this->proyecto->getAreasByConcepto($this->input->post());
                break;           
            case 'Carga_Ubicaciones_By_Area':
                $resultado = $this->proyecto->getUbicacionesByArea($this->input->post());
                break;           
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
