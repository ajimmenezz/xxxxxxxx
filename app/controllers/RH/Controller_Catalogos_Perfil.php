<?php

use Controladores\Controller_Base as Base;

class Controller_Catalogos_Perfil extends Base {

    private $catalogosPerfiles;

    public function __construct() {
        parent::__construct();
        $this->catalogosPerfiles = new \Librerias\RH\Catalogos_Perfil();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'GuardarCatalogosPerfil':
                $resultado = $this->catalogosPerfiles->guardarCatalogosPerfil($this->input->post());
                break;
            case 'ActualizarCatalogoPerfil':
                $resultado = $this->catalogosPerfiles->actualizarCatalogosPerfil($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
