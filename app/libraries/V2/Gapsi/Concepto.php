<?php

namespace Librerias\V2\Gapsi;

class Concepto {

    private $idConcepto;
    private $nombreConcepto;
    private $totalTransferenciaConcepto;
    private $gastoConcepto;
    private $compraConcepto;
    private $idCategoria;
    private $nombreCategoria;
    private $totalTransferenciaCategoria;
    private $gastoCategoria;
    private $compraCategoria;
    private $idSubcategoria;
    private $nombreSubcategoria;
    private $totalTransferenciaSubcategoria;
    private $gastoSubcategoria;
    private $compraSubcategoria;
    private $DBConcepto;

    public function __construct() {
        $this->DBConcepto = \Modelos\Modelo_Gapsi_Concepto::factory();
    }

    public function setDatosConcepto(string $concepto) {
        $this->nombreConcepto = $concepto;
        $datosConcepto = $this->DBConcepto->getInformacionConcepto($this->nombreConcepto);
        foreach ($datosConcepto as $key => $value) {
            $this->idConcepto = $value['ID'];
        }
    }

    public function calcularTotalTranferenciaConcepto(array $filtros) {
        $this->setGastoConcepto($filtros);
        $this->setCompraConcepto($filtros);
        $this->totalTransferenciaConcepto = $this->compraConcepto + $this->gastoConcepto;
    }

    public function getDatosConcepto() {
        return array(
            'idConcepto' => $this->idConcepto,
            'Concepto' => $this->nombreConcepto,
            'Gasto' => $this->totalTransferenciaConcepto);
    }

    public function setCompraConcepto(array $filtros) {
        $this->compraConcepto = $this->DBConcepto->getCompraConcepto($this->nombreConcepto, $filtros);
        return $this->compraConcepto;
    }

    public function setGastoConcepto(array $filtros) {
        $this->gastoConcepto = $this->DBConcepto->getGastoConcepto($this->nombreConcepto, $filtros);
        return $this->gastoConcepto;
    }

    public function setDatosCategoria(string $categoria) {
        $this->nombreCategoria = $categoria;
        $datosCategoria = $this->DBConcepto->getInformacionCategoria($this->nombreCategoria);
        foreach ($datosCategoria as $key => $value) {
            $this->idCategoria = $value['ID'];
        }
    }

    public function getDatosCategoria() {
        return array(
            'idCategoria' => $this->idCategoria,
            'Categoria' => $this->nombreCategoria,
            'Gasto' => $this->totalTransferenciaCategoria);
    }

    public function setCompraCategoria(array $filtros) {
        $this->compraCategoria = $this->DBConcepto->getCompraCategoria($this->nombreCategoria, $filtros);
    }

    public function setGastoCategoria(array $filtros) {
        $this->gastoCategoria = $this->DBConcepto->getGastoCategoria($this->nombreCategoria, $filtros);
    }

    public function calcularTotalTranferenciaCategoria(array $filtros) {
        $this->setGastoCategoria($filtros);
        $this->setCompraCategoria($filtros);
        $this->totalTransferenciaCategoria = $this->compraCategoria + $this->gastoCategoria;
    }
    
    
        public function setDatosSubcategoria(string $subcategoria) {
        $this->nombreSubcategoria = $subcategoria;
        $datosSubcategoria = $this->DBConcepto->getInformacionSubcategoria($this->nombreSubcategoria);
        foreach ($datosSubcategoria as $key => $value) {
            $this->idSubcategoria = $value['ID'];
        }
    }

    public function getDatosSubcategoria() {
        return array(
            'idSubcategoria' => $this->idSubcategoria,
            'Subcategoria' => $this->nombreSubcategoria,
            'Gasto' => $this->totalTransferenciaSubcategoria);
    }

    public function setCompraSubcategoria(array $filtros) {
        $this->compraSubcategoria = $this->DBConcepto->getCompraSubcategoria($this->nombreSubcategoria, $filtros);
    }

    public function setGastoSubcategoria(array $filtros) {
        $this->gastoSubcategoria = $this->DBConcepto->getGastoSubcategoria($this->nombreSubcategoria, $filtros);
    }

    public function calcularTotalTranferenciaSubcategoria(array $filtros) {
        $this->setGastoSubcategoria($filtros);
        $this->setCompraSubcategoria($filtros);
        $this->totalTransferenciaSubcategoria = $this->compraSubcategoria + $this->gastoSubcategoria;
    }
    

}
