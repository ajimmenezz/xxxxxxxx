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
    private $ultimoMovimiento;

    public function __construct(string $idProyecto) {
        parent::__construct();
        $this->DBProyecto = \Modelos\Modelo_ProyectoGapsi::factory();
        $this->setDatos($idProyecto);
    }

    private function setDatos(string $idProyecto) {
        $this->id = $idProyecto;
        $datosProyecto = $this->DBProyecto->getInformacion($idProyecto);
        foreach ($datosProyecto as $key => $value) {
            $this->nombre = $value['Nombre'];
            $this->tipo = $value['TipoProyecto'];
            $this->fecha = $value['Fecha'];
        }

        $this->gasto = $this->DBProyecto->getGasto($idProyecto, 'MN');
        $this->compra = $this->DBProyecto->getCompra($idProyecto, 'MN');
        $this->totalTransferencia = $this->compra + $this->gasto;
        $this->ultimoMovimiento = $this->DBProyecto->getUltimoMovimiento($idProyecto);
    }

    public function getType() {
        return $this->tipo;
    }

    public function getTotal() {
        return $this->totalTransferencia;
    }

    private function setSucursales() {
        
    }

    public function getDatos() {
        return array(
            'id' => $this->id,  
            'nombre' => $this->nombre, 
            'fechaCreacion' => $this->fecha, 
            'tipo' => $this->tipo, 
            'ultimoMovimiento' => $this->ultimoMovimiento, 
            'totalTransferencia' => $this->totalTransferencia);
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
