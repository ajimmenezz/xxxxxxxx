<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Administrador extends Base {

    private $catalogo;
    private $administrador;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->administrador = \Librerias\Administrador\Administrador::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Actualizar_Personal':
                $resultado = $this->catalogo->catClientes('4', $this->input->post());
            case 'MostrarUsuarioActualizar':
                $resultado = $this->administrador->mostrarFormularioUsuarios(array($this->input->post('Usuario')));
                break;
            case 'Actualizar_Usuario':
                $resultado = $this->catalogo->catusuarios('2', $this->input->post());
                break;
            case 'SelectEstados':
                $resultado = $this->catalogo->catLocalidades('2', array('IdPais' => $this->input->post('opcion')));
                break;
            case 'SelectMunicipios':
                $resultado = $this->catalogo->catLocalidades('3', array('IdEstado' => $this->input->post('opcion')));
                break;
            case 'SelectColonias':
                $resultado = $this->catalogo->catLocalidades('4', array('IdMunicipio' => $this->input->post('opcion')));
                break;
            case 'Actualizar_Cliente':
                $resultado = $this->catalogo->catClientes($this->input->post('operacion'), $this->input->post());
                break;
            case 'BuscarCP':
                $resultado = $this->catalogo->catLocalidades('5', array($this->input->post('cp')));
                break;
            case 'Actualizar_Sucursal':
                $resultado = $this->catalogo->catSucursales($this->input->post('operacion'), $this->input->post());
                break;
            case 'Actualizar_Proveedor':
                $resultado = $this->catalogo->catProveedores($this->input->post('operacion'), $this->input->post());
                break;
            case 'Actualizar_AreaAtencion':
                $resultado = $this->catalogo->catAreasAtencion($this->input->post('operacion'), $this->input->post());
                break;
            case 'MostrarFormularioSucursales':
                $resultado = $this->administrador->mostrarFormularioSucursales($this->input->post());
                break;
            case 'MostrarFormularioClientes':
                $resultado = $this->administrador->mostrarFormularioClientes($this->input->post());
                break;
            case 'MostrarFormularioProveedor':
                $resultado = $this->administrador->mostrarFormularioProveedor($this->input->post());
                break;
            case 'MostrarFormularioAreaAtencion':
                $resultado = $this->administrador->mostrarFormularioAreasAtencion($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
