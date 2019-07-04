<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Sucursal extends General {

    private $id;
    private $nombre;
    private $gasto;
    private $compra;
    private $totalTransferencia;
    private $servicios;
    private $dbSucursal;

    public function __construct(string $idSucursal, array $datosProyecto) {
        parent::__construct();
        $this->dbSucursal = \Modelos\Modelo_Sucursal::factory();
        $this->setDatos($idSucursal, $datosProyecto);
    }

    public function setDatos(string $idSucursal, array $datosProyecto) {
        $this->id = $idSucursal;
        $datosSucursal = $this->dbSucursal->getInformacion($idSucursal);
        foreach ($datosSucursal as $key => $value) {
            $this->nombre = $value['Nombre'];
        }

        $this->gasto = $this->dbSucursal->getGasto($idSucursal, $datosProyecto);
        $this->compra = $this->dbSucursal->getCompra($idSucursal, $datosProyecto);
        $this->totalTransferencia = $this->compra + $this->gasto;
//        $this->ultimoMovimiento = $this->DBProyecto->getUltimoMovimiento($idProyecto);
    }

    public function getDatos() {
        return array(
            'idSucursal' => $this->id,
            'Sucursal' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function getGastos() {
        return array();
    }

    public function getCompras() {
        return array();
    }

}
