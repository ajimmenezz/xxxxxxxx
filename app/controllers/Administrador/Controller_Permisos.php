<?php

use Controladores\Controller_Base as Base;

class Controller_Permisos extends Base {

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
            case 'Nuevo_Permiso':
                $resultado = $this->catalogo->catPermisos('1', array($this->input->post('nombre'), $this->input->post('permiso'), $this->input->post('descripcion')));
                break;
            case 'Actualizar_Permiso':
                $resultado = $this->catalogo->catPermisos('2', array($this->input->post('id'), $this->input->post('nombre'), $this->input->post('permiso'), $this->input->post('descripcion')));
                break;
            case 'MostrarFormularioPermisos':
                $resultado = $this->administrador->mostrarFormularioPermisos($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
