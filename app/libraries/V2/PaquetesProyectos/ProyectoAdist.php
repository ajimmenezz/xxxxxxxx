<?php

namespace Librerias\V2\PaquetesProyectos;

use Librerias\V2\PaquetesProyectos\Proyecto as Proyecto;

class ProyectoAdist implements Proyecto {

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
        echo 'Hola soy Adist mi id es ' . $idProyecto;
    }

    public function calcularTotalTranferencia() {
        
    }

    public function getCompra() {
        
    }

    public function getDatosGenerales() {
        
    }

    public function getGasto() {
        
    }

    public function getIdSucursales() {
        return array(1, 2, 3, 4, 5);
    }

    public function getType() {
        
    }

    public function setDatos(string $idProyecto) {
        
    }

    public function getServicios() {
        
    }

    public function getCategorias() {
        
    }

    public function getSubcategorias() {
        
    }

    public function getConceptos() {
        
    }

}
