<?php

use Controladores\Controller_Base as Base;
use Librerias\WebServices\ServiceDesk as SD;
use Librerias\Generales\Servicio as Servicio;
use Librerias\Generales\Solicitud as Solicitud;
use Librerias\Generales\Tester as Tester;

/**
 * Description of Controller_Solicitud
 *
 * @author Freddy
 */
class Controller_Tester extends Base {

    private $Servicio;
    private $SD;
    private $Solicitud;
    private $Tester;

    public function __construct() {
        parent::__construct();
    }

    public function manejarEvento(string $evento = null) {
        $this->SD = new SD();
        $this->Servicio = new Servicio();
        $this->Solicitud = new Solicitud();
        $this->Tester = new Tester();
        switch ($evento) {
            case 'informacionSD':
                try {
                    $datos = $this->input->post();
//                    $resultado = $this->SD->getDetallesFolio($datos['key'], $datos['folio']);
//                $resultado = $this->SD->getFoliosTecnico($datos['key']);                                
//                $resultado = $this->SD->getResolucionFolio($datos['key'], $datos['folio']);                                
//                $resultado = $this->SD->getTecnicosSD($datos['key']);                                
//                    $resultado = $this->SD->setResolucionServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->getFolios($datos['key']);
//                    $resultado = $this->SD->setNoteServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->setWorkLogServiceDesk($datos['key'], $datos['folio'], 'prueba');
//                    $resultado = $this->SD->cambiarEstatusServiceDesk($datos['key'], 'Problema', $datos['folio']);
//                    $resultado = $this->SD->consultarDepartamentoTI($datos['key']);
                    $resultado = $this->SD->validarAPIKey($datos['key']);
//                    $resultado = $this->SD->cambiarEstatusServiceDesk($datos['key'], 'Problema', $datos['folio']);
                } catch (\Exception $ex) {
                    $resultado = $ex->getMessage();
                }
                break;
            case 'generarPdfVuelta':
                $datos = $this->input->post();
                $resultado = $this->Servicio->crearPdfVuelta($datos);
                break;
            case 'concluirSolicitudesAbiertas':
                $resultado = $this->Solicitud->concluirSolicitudesAbiertas();
                break;
            case 'solicitarFolios':
                $resultado = $this->Solicitud->getFolios();
                break;
            case 'solicitarFoliosAnterior':
                $resultado = $this->Solicitud->getFoliosAnterior();
                break;
            case 'solicitudSemanal':
                $resultado = $this->Solicitud->getFoliosSemanal();
                break;
            case 'solicitudAnual':
                $resultado = $this->Solicitud->getFoliosAnual();
                break;
            case 'solicitarValidacion':
                $resultado = $this->Tester->actualizarValidadoresSD();
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
