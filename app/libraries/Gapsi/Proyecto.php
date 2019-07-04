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

    private function getSucursales() {
        return $this->sucursales;
    }

    public function getDatos() {
        return array(
            'IdProyecto' => $this->id,
            'Proyecto' => $this->nombre,
            'FCaptura' => $this->fecha,
            'tipo' => $this->tipo,
            'UltimoMovimiento' => $this->ultimoMovimiento,
            'Gasto' => $this->totalTransferencia,
            'sucursales' => $this->sucursales);
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

    private function crearSucursales(array $datosProyecto) {
        $this->sucursales = array();
        $listaSucursales = $this->DBProyecto->getSucursales($datosProyecto);

        foreach ($listaSucursales as $key => $sucursal) {
            var_dump($sucursal);
            $temporal = new \Librerias\Gapsi\Sucursal($sucursal['Sucursal'], $datosProyecto);
            array_push($this->sucursales, $temporal->getDatos());
        }
    }

}
