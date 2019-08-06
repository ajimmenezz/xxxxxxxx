<?php

use Controladores\Controller_Base as Base;

class Controller_RH extends Base {

    private $personal;

    public function __construct() {
        parent::__construct();
        $this->personal = \Librerias\Generales\Usuario::factory();
        $this->perfilUsuario = \Librerias\RH\Perfil_Usuario::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Personal':
                $resultado = $this->personal->AltaPersonal('1', $this->input->post());
                break;
            case 'MostrarPersonalActualizar':
                $resultado = $this->personal->AltaPersonal('4', $this->input->post());
                break;
            case 'Actualizar_Personal':
                $resultado = $this->personal->AltaPersonal('2', $this->input->post());
                break;
            case 'EliminarFoto':
                $resultado = $this->personal->eliminarFoto($this->input->post());
                break;
            case 'ActualizarDatosPersonal':
                $resultado = $this->perfilUsuario->guardarDatosPersonal($this->input->post());
                break;
            case 'BajaPersonal':
                $resultado = $this->personal->bajaPersonal($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
