<?php

namespace Librerias\Gapsi;

class Subcategoria {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBSubcategoria;

    public function __construct(string $subcategoria) {
        $this->nombre = $subcategoria;
        $this->DBSubcategoria = \Modelos\Modelo_Gapsi_Subcategoria::factory();
        $this->setDatos();
    }

    public function setDatos() {
        $datosSubcategoria = $this->DBSubcategoria->getInformacion($this->nombre);
        foreach ($datosSubcategoria as $key => $value) {
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
            'idSubcategoria' => $this->id,
            'Subcategoria' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function setCompra(array $filtros) {
        $this->compra = $this->DBSubcategoria->getCompra($this->nombre, $filtros);
    }

    public function setGasto(array $filtros) {

        $this->gasto = $this->DBSubcategoria->getGasto($this->nombre, $filtros);
    }

}
