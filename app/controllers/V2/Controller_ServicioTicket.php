<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $servicio;
    private $serviceDesk;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->serviceDesk = new ServiceDesk();
    }

    public function atenderServicio() {
        $datosServicio = $this->input->post(['id']); //Descomentar cuando se manden datos
        $this->servicio = $this->factory->getServicio('GeneralRedes', $datosServicio['id'] = 3);
        $resultado = $this->servicio->getdatos();
        echo '<pre>';
        echo json_encode($resultado);
        echo '</pre>';
    }
    public function guardarFolio(array $datos) {
        
    }

}
