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
        $this->serviceDesk= new ServiceDesk;
    }

    public function atenderServicio() {
        $this->getServicios();
        $this->datos['sucursales'] = $this->servicio->getSucursales();
        echo json_encode($this->datos);
        
    }

    private function setInformacionFolio(string $folio) {
        $this->datos['detallesFolio'] = ServiceDesk::getDetallesFolio($folio);
        $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
    }

    public function getInformacionFolio() {
        $this->getServicios();
        echo json_encode($this->datos);
    }

    private function getServicios() {
        $this->datos = array();
        $datosServicio = $this->input->post();
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id']);
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
    public function guardarFolio()
    {
       $idServicio="1";
       $folio=55;
//       $folio=$this->post('folio');$
//       $idServicio=$this->post('idServicio');
        $this->servicio=$this->factory->getServicio('GeneralRedes',$idServicio);//3
        $this->servicio->setFolioServiceDesk($folio);
        $this->datos['detallesFolio'] = ServiceDesk::getDetallesFolio($folio);
        $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
        var_dump($this->datos);
    }
    

}
