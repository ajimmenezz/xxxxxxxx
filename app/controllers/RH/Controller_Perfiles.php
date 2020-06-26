<?php

use Controladores\Controller_Base as Base;

class Controller_Perfiles extends Base {

    private $catalogo;
    private $perfiles;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->perfiles = \Librerias\RH\Perfiles::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarPerfilActualizar':
                $resultado = $this->perfiles->mostrarModal($this->input->post());
                break;
            case 'Actualizar_Perfil':
                $resultado = $this->catalogo->catPerfiles($this->input->post('operacion'), $this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
