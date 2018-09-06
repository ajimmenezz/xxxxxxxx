<?php

use Controladores\Controller_Base as Base;

class Controller_Planeacion extends Base {

    private $planeacion;

    public function __construct() {
        parent::__construct();
        $this->planeacion = new \Librerias\Proyectos2\Planeacion();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'FormularioNuevoProyecto':
                $resultado = $this->planeacion->formularioNuevoProyecto();
                break;
            case 'SucursalesByCliente':
                $resultado = $this->planeacion->sucursalesByCliente($this->input->post());
                break;
            case 'LideresBySistema':
                $resultado = $this->planeacion->lideresBySistema($this->input->post());
                break;
            case 'GenerarProyecto':
                $resultado = $this->planeacion->generarProyecto($this->input->post());
                break;
            case 'FormularioDetallesProyecto':
                $resultado = $this->planeacion->formularioDetallesProyecto($this->input->post());
                break;
            case 'GuardarGeneralesProyecto':
                $resultado = $this->planeacion->guardarGeneralesProyecto($this->input->post());
                break;                
            case 'FormularioNuevaUbicacion':
                $resultado = $this->planeacion->formularioNuevaUbicacion($this->input->post());
                break;            
            case 'AreasByConcepto':
                $resultado = $this->planeacion->areasByConcepto($this->input->post());
                break;
            case 'UbicacionesByArea':
                $resultado = $this->planeacion->ubicacionesByArea($this->input->post());
                break;
            case 'FormularioNodosUbicacion':
                $resultado = $this->planeacion->formularioNodosUbicacion($this->input->post());
                break;            
            case 'FormularioEditarNodo':
                $resultado = $this->planeacion->formularioEditarNodo($this->input->post());
                break;            
            case 'GuardarNodosUbicacion':
                $resultado = $this->planeacion->guardarNodosUbicacion($this->input->post());
                break;
            case 'CargaUbicacionesProyecto':
                $resultado = $this->planeacion->cargaUbicacionesProyecto($this->input->post());
                break;
            case 'EliminarNodo':
                $resultado = $this->planeacion->eliminarNodo($this->input->post());
                break;
            case 'CargaMaterialTotales':
                $resultado = $this->planeacion->cargaMaterialTotales($this->input->post());
                break;
            case 'CargaDatosTecnicos':
                $resultado = $this->planeacion->cargaDatosTecnicos($this->input->post());
                break;
            case 'GuardaAsistenteProyecto':
                $resultado = $this->planeacion->guardaAsistenteProyecto($this->input->post());
                break;
            case 'FormDetallesAsistente':
                $resultado = $this->planeacion->formDetallesAsistente($this->input->post());
                break;
            case 'EliminarAsistente':
                $resultado = $this->planeacion->eliminarAsistente($this->input->post());
                break;
            case 'FormularioNuevaTarea':
                $resultado = $this->planeacion->formularioNuevaTarea($this->input->post());
                break;
            case 'NuevaTarea':
                $resultado = $this->planeacion->nuevaTarea($this->input->post());
                break;
            case 'CargaTareasProyecto':
                $resultado = $this->planeacion->cargaTareasProyecto($this->input->post());
                break;
            case 'DetallesTarea':
                $resultado = $this->planeacion->detallesTarea($this->input->post());
                break;
            case 'EliminarTarea':
                $resultado = $this->planeacion->eliminarTarea($this->input->post());
                break;
            case 'CargaNodosTarea':
                $resultado = $this->planeacion->cargaNodosTarea($this->input->post());
                break;
            case 'GuardarNodosTarea':
                $resultado = $this->planeacion->guardarNodosTarea($this->input->post());
                break;
            case 'FormularioMaterialesTarea':
                $resultado = $this->planeacion->formularioMaterialesTarea($this->input->post());
                break;
            case 'FormularioEditarMaterialTarea':
                $resultado = $this->planeacion->formularioEditarMaterialTarea($this->input->post());
                break;
            case 'GuardarMaterialTarea':
                $resultado = $this->planeacion->guardarMaterialTarea($this->input->post());
                break;
            case 'GeneraDocumentoInicial':
                $resultado = $this->planeacion->generaDocumentoInicial($this->input->post());
                break;
            case 'GeneraSolicitudMaterial':
                $resultado = $this->planeacion->generaSolicitudMaterial($this->input->post());
                break;
            case 'GeneraSolicitudMaterialFaltante':
                $resultado = $this->planeacion->generaSolicitudMaterialFaltante($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
