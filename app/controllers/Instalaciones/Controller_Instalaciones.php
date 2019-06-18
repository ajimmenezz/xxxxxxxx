<?php

use Controladores\Controller_Base as Base;

class Controller_Instalaciones extends Base
{

    private $instalaciones;

    public function __construct()
    {
        parent::__construct();
        $this->instalaciones = new \Librerias\Instalaciones\Instalaciones();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'SeguimientoInstalacion':
                $resultado = $this->instalaciones->formularioSeguimientoInstalacion($this->input->post());
                break;
            case 'IniciarInstalacion':
                $resultado = $this->instalaciones->iniciarInstalacion($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
