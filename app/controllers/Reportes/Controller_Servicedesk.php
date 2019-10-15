<?php

use Controladores\Controller_Base as Base;

class Controller_Servicedesk extends Base
{

    private $serviceDesk;

    public function __construct()
    {
        parent::__construct();
        $this->serviceDesk = new \Librerias\Reportes\Servicedesk();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'Reporte_Redes':
                $resultado = $this->serviceDesk->sendRedesReport();
                break;
            case 'Reporte_Redes_Revision':
                $resultado = $this->serviceDesk->sendRedesReviewReport();
                break;
            default:
                $resultado = ['code' => 404, 'message' => "Not found"];
                break;
        }
        echo json_encode($resultado);
    }
}
