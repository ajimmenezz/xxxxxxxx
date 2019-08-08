<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;
use Librerias\V2\PaquetesTicket\Material as Material;
use Librerias\V2\PaquetesTicket\Movimiento as Movimiento;
use Librerias\V2\PaquetesTicket\Nodos as Nodos;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $servicio;
    private $datos;
    private $material;
    private $movimiento;
    private $nodo;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->datos = array();
//        $this->material= new Material;
//        $this->nodo= new Nodos;
    }

    public function atenderServicio() {
//        $this->getServicios();
        $datosServicio = $this->input->post();
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id']);
        $this->servicio->setEstatusServicio('enAtencion');
        $folio = $this->servicio->getFolio();
        
        $this->datos['servicio'] = $this->servicio->getDatos();
        $this->datos['sucursales'] = $this->servicio->getSucursales();
        $this->datos['problemas'] = null;
        $this->datos['nodos'] = null;
        $this->datos['material'] = null;
        $this->datos['firmas'] = null;        
        $this->datos['folio'] = $this->servicio->getFolio();
        $this->datos['notasFolio'] = null;
        
        if (!empty($this->datos['folio'])) {
            $this->datos['folio'] = ServiceDesk::getDetallesFolio($folio);
            $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
        }
        echo json_encode($this->datos);
    }

    private function setInformacionFolio(string $folio) {
        $this->datos['Folio'] = ServiceDesk::getDetallesFolio($folio);
        $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
    }

    public function getInformacionFolio() {
        $this->getServicios();
        echo json_encode($this->datos);
    }

    private function getServicios() {
        $datosServicio = $this->input->post();
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id']);
        $this->servicio->setEstatusServicio('atencion');
        $this->datos = $this->servicio->getdatos();
        if (!empty($this->datos['folio'])) {
            $this->setInformacionFolio($this->datos['folio']);
        }
    }

    public function actualizarFolio() {
        $this->getServicios();
        $respuesta = $this->servicio->setFolioServiceDesk($this->input->post('folio'));
        if (!empty($respuesta)) {
            $this->setInformacionFolio($this->input->post('folio'));
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
        $this->setInformacionFolio($datosServicio['folio']);
    }

    public function registrarNodo() {
        $datos = $this->input->post();
        var_dump($datos);
        $nodo = $this->factory->setNodos($datos);
        $this->registrarMaterial($datos);
    }

    public function mostrarMaterial() {
        $idTecnico = 1;
        $this->material = new Material($idTecnico);
    }

    public function mostrarNodos() {
        
    }

    public function registrarMaterial($datos) {
        var_dump($datos);
        $this->material->setMaterial($datos);
    }

    public function registrarMovimiento() {
        $this->movimiento = new Movimiento;
        $this->movimiento->setMovimiento($datos);
    }

    public function editarNodo() {
        $datosNodo = array();
        $datosNodo = $this->input->post();
        $this->nodo->editarNodo($datosNodo);
    }

    public function eliminarMaterialNodo() {
        
    }

    public function eliminarNodo($idNodo) {
        $this->nodo->eliminarNodo($idNodo);
    }

}
