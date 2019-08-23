<?php

use Controladores\Controller_Base as Base;
use Librerias\RH\CalendarioPermisos as Calendario;
use Librerias\RH\Autorizar_permisos as autorizar;
class Controller_Calendar extends Base {

    private $calendario;
    private $autorizar;

    public function __construct() {
        parent::__construct();
        $this->calendario = new Calendario;
        $this->autorizar = new autorizar;

    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'datosPermiso':         
                $fecha = mdate('%Y-%m-%d', now('America/Mexico_City'));
                $resultado = $this->calendario->PermisosUsuario($this->input->post(),$fecha);
                break;
            case 'cancelarPermiso':
                var_dump($this->input->post());
               // $resultado = $this->autorizar->cancelarPermiso();
                break;
        }
        echo json_encode($resultado);
    }
}
