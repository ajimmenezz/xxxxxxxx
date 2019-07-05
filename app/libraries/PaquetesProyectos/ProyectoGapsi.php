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
        $this->totalTransferencia = $this->compra + $this->gasto;
        $this->ultimoMovimiento = $this->DBProyecto->getUltimoMovimiento($idProyecto);
    }

    public function getIdSucursales() {
        $this->idSucursales = $this->DBProyecto->getIdSucursales($this->id);
        return $this->idSucursales;
    }

    public function calcularTotalTranferencia() {
        
    }

    public function getCompra() {
        
    }

    public function getDatosGenerales() {
        
    }

    public function getGasto() {
        
    }

    public function getType() {
        
    }

}
