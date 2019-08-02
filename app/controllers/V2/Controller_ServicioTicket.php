<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $servicio;
    private $datos;

//    private $serviceDesk;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
    }

    public function atenderServicio() {
        $this->datos = array();
        $datosServicio = $this->input->post();
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id']);
        $this->datos = $this->servicio->getdatos();
        $this->setDetallesFolio();
//        echo '<pre>';
//        var_dump($this->datos);
//        echo '</pre>';
        echo json_encode($this->datos);
    }

    private function setDetallesFolio() {
        if (!empty($this->datos['folio'])) {
            $this->datos['detallesFolio'] = ServiceDesk::getDetallesFolio($this->datos['folio']);
        }
    }

    public function guardarFolio(array $datos) {
        
    }

}
