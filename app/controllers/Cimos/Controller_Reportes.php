<?php

use Controladores\Controller_Base as Base;

class Controller_Reportes extends Base {

    private $Cimos;

    public function __construct() {
        parent::__construct();
        $this->Cimos = \Librerias\WebServices\Cimos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'GetReporteCimos':
                $resultado = $this->Cimos->getReporteCimos($this->input->post());
                break;
            case 'GetReporteCimosExcel':
                $resultado = $this->Cimos->getReporteCimosExcel($this->input->post());
                break;
            case 'MakeContractPdf':
                $resultado = $this->Cimos->makeContractPdf($this->input->post());
                break;
            default :
                $resultado = [];
                break;
        }
        echo json_encode($resultado);
    }

}
