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
                try {
                    $datos = $this->input->post();
//                    $resultado = $this->SD->getDetallesFolio($datos['key'], $datos['folio']);
//                $resultado = $this->SD->getFoliosTecnico($datos['key']);                                
//                $resultado = $this->SD->getResolucionFolio($datos['key'], $datos['folio']);                                
//                $resultado = $this->SD->getTecnicosSD($datos['key']);                                
//                    $resultado = $this->SD->setResolucionServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->getTecnicosSD($datos['key']);
//                    $resultado = $this->SD->getFolios($datos['key']);
//                    $resultado = $this->SD->setNoteServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->setWorkLogServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->cambiarEstatusServiceDesk($datos['key'], 'Problema', $datos['folio']);
//                    $resultado = $this->SD->consultarDepartamentoTI($datos['key']);
                    $resultado = $this->SD->validarAPIKey($datos['key']);
                } catch (\Exception $ex) {
                    $resultado = $ex->getMessage();
                }
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
