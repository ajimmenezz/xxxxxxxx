<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;
use Librerias\V2\PaquetesSucursales\GestorSucursales as GestorSucursal;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $gestorSucursales;
    private $servicio;
    private $datos;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->gestorSucursales = new GestorSucursal();
        $this->datos = array();
    }

    public function atenderServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $this->servicio->setEstatus('2');
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales($this->servicio->getCliente());
            $this->datos['solucion'] = null;
            $this->datos['problemas'] = null;
            $this->datos['firmas'] = null;
            $this->getInformacionFolio();
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            
        }
    }

    private function getInformacionFolio() {
        try {
            $this->datos['folio'] = null;
            $this->datos['notasFolio'] = null;

            if (!empty($this->servicio->getFolio())) {
                ServiceDesk::setEstatus('En Atención', $this->servicio->getFolio());
                $this->datos['folio'] = ServiceDesk::getDatos($this->servicio->getFolio());
                $this->datos['notasFolio'] = ServiceDesk::getNotas($this->servicio->getFolio());
            }
        } catch (Exception $ex) {
            $this->datos['folio'] = array('Error' => $ex->getMessage());
            $this->datos['notasFolio'] = array('Error' => $ex->getMessage());
        }
    }

    private function getServicios() {
        $datosServicio = $this->input->post();
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id']);
        $this->servicio->setEstatusServicio('atencion');
        $this->datos = $this->servicio->getdatos();
        if (!empty($this->datos['folio'])) {
            $this->getInformacionFolio($this->datos['folio']);
        }
    }

    public function actualizarFolio() {
        $this->getServicios();
        $respuesta = $this->servicio->setFolioServiceDesk($this->input->post('folio'));
        if (!empty($respuesta)) {
            $this->getInformacionFolio($this->input->post('folio'));
            return $this->datos;
        } else {
            return FALSE;
        }
    }

    public function guardarFolio() {
        $datosServicio = $this->input->post();

        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['idServicio']);
        $this->datos = $this->servicio->getDatos();
        $this->servicio->setFolioServiceDesk($datosServicio['folio']);
        $this->getInformacionFolio($datosServicio['folio']);
    }

}
