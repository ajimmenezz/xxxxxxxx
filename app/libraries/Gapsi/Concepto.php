<?php

namespace Librerias\Gapsi;

class Concepto {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBConcepto;

    public function __construct(string $subcategoria) {
        $this->nombre = $subcategoria;
        $this->DBConcepto = \Modelos\Modelo_Gapsi_Concepto::factory();
        $this->setDatos();
    }

    public function setDatos() {
        $datosConcepto = $this->DBConcepto->getInformacion($this->nombre);
        foreach ($datosConcepto as $key => $value) {
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
            'idConcepto' => $this->id,
            'Concepto' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function setCompra(array $filtros) {
        $this->compra = $this->DBConcepto->getCompra($this->nombre, $filtros);
    }

    public function setGasto(array $filtros) {

        $this->gasto = $this->DBConcepto->getGasto($this->nombre, $filtros);
    }

}
