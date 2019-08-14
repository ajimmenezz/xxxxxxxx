<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;
use Librerias\V2\PaquetesSucursales\GestorSucursales as GestorSucursal;
use Librerias\V2\PaquetesTicket\GestorServicios as GestorServicio;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $gestorSucursales;
    private $gestorServicios;
    private $servicio;
    private $datos;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->gestorSucursales = new GestorSucursal();
        $this->gestorServicios = new GestorServicio();
        $this->datos = array();
    }

    public function atenderServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $this->servicio->setEstatus('2');
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales($this->servicio->getCliente());
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = null;
            $this->datos['firmas'] = null;
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($tipoServicio, array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio();
            $this->setEstatusServiceDesk();
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function seguimientoServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales($this->servicio->getCliente());
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = null;
            $this->datos['firmas'] = null;
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($tipoServicio, array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio();            
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function getInformacionFolio() {
        try {
            $this->datos['folio'] = null;
            $this->datos['notasFolio'] = null;

            if (!empty($this->servicio->getFolio())) {
                $this->datos['folio'] = ServiceDesk::getDatos($this->servicio->getFolio());
                $this->datos['notasFolio'] = ServiceDesk::getNotas($this->servicio->getFolio());
            }
        } catch (Exception $ex) {
            $this->datos['folio'] = array('Error' => $ex->getMessage());
            $this->datos['notasFolio'] = array('Error' => $ex->getMessage());
        }
    }

    private function setEstatusServiceDesk() {
        $estatusFolio = null;
        try {

            if (!empty($this->datos['folio'] && property_exists($this->datos['folio'], 'STATUS'))) {
                $estatusFolio = $this->datos['folio']->STATUS;
            }

            if ($estatusFolio === 'Abierto') {
                ServiceDesk::setEstatus('En Atención', $this->servicio->getFolio());
            }
        } catch (Exception $ex) {
            
        }
    }

    public function setFolio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->setFolioServiceDesk($datosServicio['folio']);
            $this->getInformacionFolio();
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function runEvento(string $evento) {
        try {
            $datosServicio = $this->input->post();           
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            echo json_encode($this->servicio->runAccion($evento, $datosServicio));
        } catch (Exception $ex) {
            $this->datos['ERROR'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

}
