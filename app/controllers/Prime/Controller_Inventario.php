<?php

use Controladores\Controller_Base as Base;

class Controller_Inventario extends Base
{

    private $inventario;

    public function __construct()
    {
        parent::__construct();
        $this->inventario = new \Librerias\Prime\Inventario();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'InventarioSucursal':
                $resultado = $this->inventario->getInventarioSucursal($this->input->post());
                break;
            case 'ExportarInventario':
                $resultado = $this->inventario->exportarInventario($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
