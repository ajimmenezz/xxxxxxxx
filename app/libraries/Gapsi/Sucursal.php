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

    public function __construct(string $idSucursal) {
        parent::__construct();
        $this->dbSucursal = \Modelos\Modelo_Sucursal::factory();
        $this->setDatos($idSucursal);
    }

    public function setDatos(string $idSucursal) {
        $this->id = $idSucursal;
        $datosSucursal = $this->dbSucursal->getInformacion($idSucursal);
        foreach ($datosSucursal as $key => $value) {
            $this->nombre = $value['Nombre'];
        }

        $this->gasto = $this->dbSucursal->getGasto($idSucursal, 'MN');
        $this->compra = $this->dbSucursal->getCompra($idSucursal, 'MN');
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
