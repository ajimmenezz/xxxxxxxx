<?php

use Librerias\Gapsi\Proyecto as Proyecto;
use Librerias\Gapsi\Sucursal as Sucursal;
use Librerias\Factorys\FactoryProject as Factory;

class Controller_Proyecto extends CI_Controller {
    
    private $proyecto;
    private $sucursales;
    private $factory;


    public function __construct() {
        parent::__construct();      
        $this->factory = new Factory();
    }
    
    public function getDatosProyecto() {
        
        $datos = $this->input->post(); 
        $this->proyecto = $this->factory->getProject($datos['sistema'], $datos['proyecto']);              
        $this->setSucursales();          
    }
    
    private function setSucursales(){
        $listIdSucursales = null;
        $listIdSucursales = $this->proyecto->getIdSucursales();  
        var_dump($listIdSucursales);
//        $this->sucursales = array();
//        
//        foreach ($listIdSucursales as $key => $idSucursal) {
//            array_push($this->sucursales, new Sucursal($idSucursal));
//        }
        
        
    }

}
