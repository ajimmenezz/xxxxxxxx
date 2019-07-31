<?php

namespace Controladores\V2;

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;

class Controller_ServicioTicket extends \CI_Controller{

    private $factory;
    private $servicio;
    private $serviceDesk;
    
    public function index()
    {
        var_dump("Estoy en controller");
    }

    public function atenderServicio() {
        var_dump("Estoy en controller");
        
//        $this->factory = new FactoryServiciosTicket();
//        $this->serviceDesk = new ServiceDesk();
//        
//        $this->servicio= $this->factory->getServicio($tipo, $idServicio);
    }

    public function guardarFolio(array $datos) {
        
    }

}
