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
//        $this->crearSucursales(array('idProyecto' => $idProyecto, 'moneda' => 'MN'));
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
            'idProyecto' => $this->id,
            'proyecto' => $this->nombre,
            'fechaCreacion' => $this->fecha,
            'tipo' => $this->tipo,
            'ultimoMovimiento' => $this->ultimoMovimiento,
            'gasto' => $this->totalTransferencia,
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
        $listaSucursales = $this->DBProyecto->getIdSucursales(array('idProyecto' => $datosProyecto['idProyecto'], 'moneda' => $datosProyecto['moneda']));

        foreach ($listaSucursales as $key => $sucursal) {
            $temporal = new \Librerias\Gapsi\Sucursal($sucursal['Sucursal']);
            array_push($this->sucursales, $temporal->getDatos());
        }

    }

}
