<?php

namespace Librerias\V2\PaquetesServicios;

use Librerias\V2\PaquetesServicios\Servicio as Servicio;

class ServicioGapsi implements Servicio {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBServicio;

    public function __construct(string $servicio) {
        $this->nombre = $servicio;
        $this->DBServicio = \Modelos\Modelo_Gapsi_Servicio::factory();
        $this->setDatos();
    }

    public function setDatos() {
        $datosServicio = $this->DBServicio->getInformacion($this->nombre);
        foreach ($datosServicio as $key => $value) {
            $this->id = $value['ID'];
        }
    }

    public function calcularTotalTranferencia(array $filtros) {
        $this->setGasto($filtros);
        $this->setCompra($filtros);
        $this->totalTransferencia = $this->compra + $this->gasto;
    }

    public function getDatos() {
        return array(
            'idServicio' => $this->id,
            'Servicio' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function setCompra(array $filtros) {
        $this->compra = $this->DBServicio->getCompra($this->nombre, $filtros);
    }

    public function setGasto(array $filtros) {
        $this->gasto = $this->DBServicio->getGasto($this->nombre, $filtros);
    }

}
