<?php

use Controladores\Controller_Base as Base;

class Controller_Pruebas extends Base
{

    private $inventario;

    public function __construct()
    {
        parent::__construct();
        $this->pruebas = new \Librerias\Pruebas\Pruebas();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'UpdateBranchesGeocode':
                $resultado = $this->pruebas->updateBranchesGeocode();
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
