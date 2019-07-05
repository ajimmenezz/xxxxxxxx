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
    private $idSucursales;
    private $DBProyecto;
    private $ultimoMovimiento;

    public function __construct(array $datosProyecto) {
        parent::__construct();
        $this->DBProyecto = \Modelos\Modelo_ProyectoGapsi::factory();
        $this->setDatos($datosProyecto);
    }

    private function setDatos(array $datosProyecto) {
        $this->id = $datosProyecto['idProyecto'];
        $proyecto = $this->DBProyecto->getInformacion($datosProyecto);
        foreach ($proyecto as $key => $value) {
            $this->nombre = $value['Nombre'];
            $this->tipo = $value['TipoProyecto'];
            $this->fecha = $value['Fecha'];
        }

        $this->gasto = $this->DBProyecto->getGasto($datosProyecto['idProyecto'], $datosProyecto['moneda']);
        $this->compra = $this->DBProyecto->getCompra($datosProyecto['idProyecto'], $datosProyecto['moneda']);
        $this->totalTransferencia = $this->compra + $this->gasto;
        $this->ultimoMovimiento = $this->DBProyecto->getUltimoMovimiento($datosProyecto['idProyecto']);

        if (isset($datosProyecto['datosExtra'])) {
            $this->crearSucursales($datosProyecto);
        }
    }

    public function getType() {
        return $this->tipo;
    }

    public function getTotal() {
        return $this->totalTransferencia;
    }

    public function getIdSucursalesGapsi() {        
        $this->idSucursales = $this->DBProyecto->getIdSucursales($this->id);
        return $this->idSucursales;
    }

    public function getDatosGenerales() {
        return array(
            'idProyecto' => $this->id,
            'proyecto' => $this->nombre,
            'fechaCreacion' => $this->fecha,
            'tipo' => $this->tipo,
            'ultimoMovimiento' => $this->ultimoMovimiento,
            'gasto' => $this->totalTransferencia,
            'sucursales' => $this->idSucursales);
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

    private function crearSucursales(array $datosProyecto) {
        $this->idSucursales = array();
        $listaSucursales = $this->DBProyecto->getIdSucursales(array('idProyecto' => $datosProyecto['idProyecto'], 'moneda' => $datosProyecto['moneda']));

        foreach ($listaSucursales as $key => $sucursal) {
            $temporal = new \Librerias\Gapsi\Sucursal($sucursal['Sucursal']);
            array_push($this->idSucursales, $temporal->getDatos());
        }
    }

}
