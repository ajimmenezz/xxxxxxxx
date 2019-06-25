<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Servicio extends General {
    
    private $id;
    private $nombre;
    private $gasto;
    private $compra;
    private $totalTranferencia;
    private $conceptos;
    private $dbServicio;
    
    public function __construct(string $idServicio) {
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