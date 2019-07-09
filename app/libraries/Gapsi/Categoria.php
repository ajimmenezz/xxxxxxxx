<?php

namespace Librerias\Gapsi;

class Categoria {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBCategoria;

    public function __construct(string $categoria) {
        $this->nombre = $categoria;
        $this->DBCategoria = \Modelos\Modelo_Gapsi_Categoria::factory();
        $this->setDatos();
    }

    public function setDatos() {
        $datosSucursal = $this->DBCategoria->getInformacion($this->nombre);
        foreach ($datosSucursal as $key => $value) {
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
            'idCategoria' => $this->id,
            'Categoria' => $this->nombre,
            'Gasto' => $this->totalTransferencia);
    }

    public function setCompra(array $filtros) {
        $this->compra = $this->DBCategoria->getCompra($this->nombre, $filtros);
    }

    public function setGasto(array $filtros) {

        $this->gasto = $this->DBCategoria->getGasto($this->nombre, $filtros);
    }

}
