<?php

use Controladores\Controller_Base as Base;

use Librerias\WebServices\ServiceDesk as SD;
/**
 * Description of Controller_Solicitud
 *
 * @author Freddy
 */
class Controller_Tester extends Base {

    private $Solicitud;
    private $SD;

    public function __construct() {
        parent::__construct();        
    }

    public function manejarEvento(string $evento = null) {
        $this->SD = new SD();
        switch ($evento) {
            case 'informacionSD':
                $datos = $this->input->post();
                $resultado = $this->SD->getDetallesFolio($datos['key'], $datos['folio']);                                
//                $resultado = $this->SD->getFoliosTecnico($datos['key'], $datos['folio']);                                
//                $resultado = $this->SD->getResolucionFolio($datos['key'], $datos['folio']);                                
//                $resultado = $this->SD->getTecnicosSD($datos['key']);                                
                break;            
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
