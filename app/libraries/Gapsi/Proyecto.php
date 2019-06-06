<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Proyecto extends General {

    private $id;
    private $nombre;
    private $tipo;   
    private $totalTransferencia;   
    private $fecha;
    private $gasto;
    private $compra;
    private $sucursales;
    private $DBProyecto;
       
    public function __construct(string $idProyecto) {
        parent::__construct();    
        $this->DBProyecto = \Modelos\Modelo_ProyectoGapsi::factory();
        $this->DBProyecto->getInformacion($idProyecto);
    }        
    
    public function getDatos() {
        return array();
    }
    
    public function getDatosGenerales() {
        return array();
    }
    
    private function getGasto() {
        return double;
    }
    
    private function getCompra() {
        return double;
    }
    
    private function calcularTotalTranferencia() {
        return double;
    }
    
    
}
