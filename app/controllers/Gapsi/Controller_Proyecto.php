<?php

use Librerias\Factorys\FactoryProject as FactoryProyecto;
use Librerias\Factorys\FactorySucursal as FactorySucursal;
use Librerias\Factorys\FactoryServicio as FactoryServicio;
use Librerias\Gapsi\Categoria as Categoria;
use Librerias\Gapsi\Subcategoria as Subcategoria;
use Librerias\Gapsi\Concepto as Concepto;

class Controller_Proyecto extends CI_Controller {

    private $proyecto;
    private $sucursales;
    private $servicios;
    private $categorias;
    private $subcategorias;
    private $conceptos;
    private $factoryProyecto;
    private $factorySucursal;
    private $factoryServicio;
    private $datosFiltro;

    public function __construct() {
        parent::__construct();
        $this->factoryProyecto = new FactoryProyecto();
        $this->factorySucursal = new FactorySucursal();
        $this->factoryServicio = new FactoryServicio();
    }

    public function getDatosProyecto() {
        $datosProyectosInfo = array();
        $this->datosFiltro = $this->input->post();
        $this->proyecto = $this->factoryProyecto->getProject($this->datosFiltro['sistema'], $this->datosFiltro['proyecto']);
        $this->setSucursales();
        $this->setServicios();
        $this->setCategorias();
        $this->setSubcategorias();
        $this->setConceptos();

        $datosProyectosInfo['proyectos'] = $this->proyecto->getDatosGenerales();
        $datosProyectosInfo['servicios'] = $this->servicios;
        $datosProyectosInfo['sucursales'] = $this->sucursales;
        $datosProyectosInfo['categorias'] = $this->categorias;
        $datosProyectosInfo['subcategorias'] = $this->subcategorias;
        $datosProyectosInfo['conceptos'] = $this->conceptos;
        $datosProyectosInfo['gastosCompras'] = $this->setGastoCompra();
        $datosProyectosInfo['proyectos']['moneda'] = $this->datosFiltro['moneda'];

        echo json_encode(array('formulario' => $this->load->view('Generales/Dashboard_Gapsi_Filters', $datosProyectosInfo, TRUE), 'consulta' => $datosProyectosInfo));
    }

    private function setSucursales() {
        $listIdSucursales = null;
        $listIdSucursales = $this->proyecto->getIdSucursales();
        $this->sucursales = array();

        foreach ($listIdSucursales as $key => $idSucursal) {
            $sucursal = $this->factorySucursal->getSucursal($this->datosFiltro['sistema'], $idSucursal['Sucursal']);
            $sucursal->calcularTotalTranferencia($this->datosFiltro);
            array_push($this->sucursales, $sucursal->getDatos());
        }
    }

    private function setServicios() {
        $listServicios = null;
        $listServicios = $this->proyecto->getServicios();
        $this->servicios = array();

        foreach ($listServicios as $key => $servicio) {
            $servicio = $this->factoryServicio->getServicio($this->datosFiltro['sistema'], $servicio['TipoServicio']);
            $servicio->calcularTotalTranferencia($this->datosFiltro);
            array_push($this->servicios, $servicio->getDatos());
        }
    }

    private function setCategorias() {
        $listaCategorias = null;
        $listaCategorias = $this->proyecto->getCategorias();
        $this->categorias = array();

        foreach ($listaCategorias as $key => $categoria) {
            $categoria = new Categoria($categoria['Categoria']);
            $categoria->calcularTotalTranferencia($this->datosFiltro);
            array_push($this->categorias, $categoria->getDatos());
        }
    }

    private function setSubcategorias() {
        $listaSubcategorias = null;
        $listaSubcategorias = $this->proyecto->getSubcategorias();
        $this->subcategorias = array();

        foreach ($listaSubcategorias as $key => $subcategoria) {
            $subcategoria = new Subcategoria($subcategoria['SubCategoria']);
            $subcategoria->calcularTotalTranferencia($this->datosFiltro);
            array_push($this->subcategorias, $subcategoria->getDatos());
        }
    }

    private function setConceptos() {
        $listaConceptos = null;
        $listaConceptos = $this->proyecto->getConceptos();
        $this->conceptos = array();

        foreach ($listaConceptos as $key => $concepto) {
            $concepto = new Concepto($concepto['Concepto']);
            $concepto->calcularTotalTranferencia($this->datosFiltro);
            array_push($this->conceptos, $concepto->getDatos());
        }
    }

    private function setGastoCompra() {
        $gastoCompra = array();

        $gastoCompra[0]['TipoTrans'] = 'COMPRA';
        $gastoCompra[0]['Gasto'] = $this->proyecto->getCompra();
        $gastoCompra[1]['TipoTrans'] = 'GASTO';
        $gastoCompra[1]['Gasto'] = $this->proyecto->getGasto();

        return $gastoCompra;
    }

}
