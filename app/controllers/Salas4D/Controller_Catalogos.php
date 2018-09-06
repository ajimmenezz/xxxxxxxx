<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Catalogos extends Base {

    private $catalogo;
    private $catalogosSalasX4D;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->catalogosSalasX4D = \Librerias\Salas4D\Catalogos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarFormularioTipoSistema':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioTipoSistema($this->input->post());
                break;
            case 'MostrarFormularioEquipo':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioEquipo($this->input->post());
                break;
            case 'MostrarFormularioMarca':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioMarca($this->input->post());
                break;
            case 'MostrarFormularioModelo':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioModelo($this->input->post());
                break;
            case 'MostrarFormularioComponente':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioComponente($this->input->post());
                break;
            case 'MostrarFormularioUbicacion':
                $resultado = $this->catalogosSalasX4D->mostrarFormularioUbicacion($this->input->post());
                break;
            case 'GuardarTipoSistema':
                $resultado = $this->catalogo->catX4DTiposSistema($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarEquipo':
                $resultado = $this->catalogo->catX4DEquipos($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarMarca':
                $resultado = $this->catalogo->catX4DMarcas($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarModelo':
                $resultado = $this->catalogo->catX4DModelos($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarComponente':
                $resultado = $this->catalogo->catX4DComponentes($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarUbicacion':
                $resultado = $this->catalogo->catX4DUbicaciones($this->input->post('operacion'), $this->input->post());
                break;
            case 'SelectEquipos':
                $resultado = $this->catalogosSalasX4D->multiselectX4D('1', array('IdSistema' => $this->input->post('opcion'), 'Flag' => '1'));
                break;
            case 'SelectMarcas':
                $resultado = $this->catalogosSalasX4D->multiselectX4D('2', array('IdEquipo' => $this->input->post('opcion'), 'Flag' => '1'));
                break;
            case 'BorrarActividadMantenimiento':
                $resultado = $this->catalogosSalasX4D->borrarActividadesMantenimientoX4D($this->input->post());
                break;
            case 'GuardarActividadMantenimiento':
                $resultado = $this->catalogo->catX4DActividadesMantenimiento('1', $this->input->post());
                break;
            case 'ActualizarActividadMantenimiento':
                $resultado = $this->catalogo->catX4DActividadesMantenimiento('2', $this->input->post());
                break;
            case 'SelectModelos':
                $resultado = $this->catalogosSalasX4D->multiselectX4D('3', array('IdMarca' => $this->input->post('opcion'), 'Flag' => '1'));
                break;
            case 'SelectinfoBd':
                $resultado = $this->catalogo->SelectinfoBd('3', array('id' => $this->input->post('opcion'), 'Flag' => '1'));
                break;
            case 'ActividadesMantenimientoJson':
                $resultado = $this->catalogosSalasX4D->obtenerActividadesMantenimientoJson();
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
