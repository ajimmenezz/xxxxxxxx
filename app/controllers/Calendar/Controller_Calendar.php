<?php

use Controladores\Controller_Base as Base;
use Librerias\RH\CalendarioPermisos as Calendario;
class Controller_Calendar extends Base {

    private $calendario;

    public function __construct() {
        parent::__construct();
        $this->calendario = new Calendario;
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'datosPermiso':         
                $fecha = mdate('%Y-%m-%d', now('America/Mexico_City'));
                $resultado = $this->calendario->PermisosUsuario($fecha);
                break;
            case 'peticionCancelar':
                $resultado = $this->calendario->peticionCancelarPermiso($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }
}
