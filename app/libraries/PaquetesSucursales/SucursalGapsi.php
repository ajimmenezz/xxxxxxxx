<?php

namespace Librerias\PaquetesSucursales;

use Librerias\PaquetesSucursales\Sucursal as Sucursal;

class SucursalGapsi implements Sucursal {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBSucursal;

    public function __construct(string $idSucursal) {
        $this->id = $idSucursal;
        $this->DBSucursal = \Modelos\Modelo_Sucursal::factory();
        $this->setDatos();
    }

    public function setDatos() {
        $datosSucursal = $this->DBSucursal->getInformacion($this->id);
        foreach ($datosSucursal as $key => $value) {
            $this->nombre = $value['Nombre'];
        }
    }

    public function calcularTotalTranferencia(array $filtros) {
        $this->setGasto($filtros);
        $this->setCompra($filtros);
        $this->totalTransferencia = $this->compra + $this->gasto;
    }

    public function getDatos() {
        return array(
            'idSucursal' => $this->id,
            'Sucursal' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function setCompra(array $filtros) {
        $this->compra = $this->DBSucursal->getCompra($this->id, $filtros);
    }

    public function setGasto(array $filtros) {

        $this->gasto = $this->DBSucursal->getGasto($this->id, $filtros);
    }

}
