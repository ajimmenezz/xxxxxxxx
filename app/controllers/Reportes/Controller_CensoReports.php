<?php

use Controladores\Controller_Base as Base;

class Controller_CensoReports extends Base
{

    private $censoLibrary;

    public function __construct()
    {
        parent::__construct();
        $this->censoLibrary = new \Librerias\Reportes\Censos();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'GetInventories':
                $resultado = $this->censoLibrary->getInventories($this->input->get());
                break;
            default:
                $resultado = ['code' => 404, 'message' => "Not found"];
                break;
        }
        echo json_encode($resultado);
    }
}
