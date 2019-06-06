<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Sucursal extends General {
    
    private $id;
    private $nombre;
    private $gasto;
    private $compra;
    private $totalTranferencia;
    private $servicios;
    private $dbSucursal;
    
    public function __construct(string $idSucursal) {
        parent::__construct();
    }
    
    public function getDatos() {
        return array();
    }
      
    public function getGastos() {
        return array();
    }
    
    public function getCompras() {
        return array();
    }
    
       
}