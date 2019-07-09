<?php

namespace Librerias\PaquetesProyectos;

use Librerias\PaquetesProyectos\Proyecto as Proyecto;

class ProyectoGapsi implements Proyecto {

    private $id;
    private $nombre;
    private $tipo;
    private $totalTransferencia;
    private $fecha;
    private $gasto;
    private $compra;
    private $idSucursales;
    private $servicios;
    private $categorias;
    private $subcategorias;
    private $conceptos;
    private $DBProyecto;
    private $ultimoMovimiento;

    public function __construct(string $idProyecto) {
        $this->DBProyecto = \Modelos\Modelo_ProyectoGapsi::factory();
        $this->setDatos($idProyecto);
    }

    public function setDatos(string $idProyecto) {
        $this->id = $idProyecto;
        $datosProyecto = $this->DBProyecto->getInformacion($idProyecto);
        foreach ($datosProyecto as $key => $value) {
            $this->nombre = $value['Nombre'];
            $this->tipo = $value['TipoProyecto'];
            $this->fecha = $value['Fecha'];
        }

        $this->gasto = $this->DBProyecto->getGasto($idProyecto, 'MN');
        $this->compra = $this->DBProyecto->getCompra($idProyecto, 'MN');
        $this->ultimoMovimiento = $this->DBProyecto->getUltimoMovimiento($idProyecto);
        $this->calcularTotalTranferencia();
    }

    public function getIdSucursales() {
        $this->idSucursales = $this->DBProyecto->getIdSucursales($this->id);
        return $this->idSucursales;
    }

    public function calcularTotalTranferencia() {
        $this->totalTransferencia = $this->compra + $this->gasto;
        return $this->totalTransferencia;
    }

    public function getCompra() {
        return $this->compra;
    }

    public function getGasto() {
        return $this->gasto;
    }

    public function getDatosGenerales() {
        return array(
            'idProyecto' => $this->id,
            'proyecto' => $this->nombre,
            'fechaCreacion' => $this->fecha,
            'tipo' => $this->tipo,
            'ultimoMovimiento' => $this->ultimoMovimiento,
            'gasto' => $this->totalTransferencia);
    }

    public function getType() {
        
    }

    public function getServicios() {
        $this->servicios = $this->DBProyecto->getServicios($this->id);
        return $this->servicios;
    }

    public function getCategorias() {
        $this->categorias = $this->DBProyecto->getCategorias($this->id);
        return $this->categorias;
    }

    public function getSubcategorias() {
        $this->subcategorias = $this->DBProyecto->getSubcategorias($this->id);
        return $this->subcategorias;
    }

    public function getConceptos() {
        $this->conceptos = $this->DBProyecto->getConceptos($this->id);
        return $this->conceptos;
    }

}
