<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GestorProyectos extends General {

    private $DBGestor;
    private $proyectos;
    private $typeProyects;

    public function __construct() {
        parent::__construct();
        $this->DBGestor = \Modelos\Modelo_GestorProyectos::factory();
    }

    public function getDatosGeneralesProyectos(array $datosProyectos) {
        $listaIdProyectos = $this->DBGestor->getListaProyectos($datosProyectos);
        $this->crearProyectos($listaIdProyectos);
        $this->getlistTypeProyects($datosProyectos);
        $listaProyectos = $this->getlistProyects();
        return array('TiposProyectos' => $this->typeProyects, 'Proyectos' => $listaProyectos);
    }

    private function crearProyectos(array $listaIdProyectos) {
        $this->proyectos = array();
        foreach ($listaIdProyectos as $key => $proyecto) {
            $temporal = new \Librerias\Gapsi\Proyecto(array('idProyecto' => $proyecto['IdProyecto'], 'moneda' => 'MN'));
            array_push($this->proyectos, $temporal);
        }
    }

    private function getlistTypeProyects(array $datosProyectos) {
        $temporal = array();
        $this->typeProyects = $this->DBGestor->getListTypeProyects($datosProyectos);

        foreach ($this->typeProyects as $typeProject) {
            $gasto = 0;
            $totalProyectos = 0;
            foreach ($this->proyectos as $proyecto) {
                $type = $proyecto->getType();
                if ($typeProject['Nombre'] === $type) {
                    $gasto += $proyecto->getTotal();
                    $totalProyectos++;
                }
            }

            if ($totalProyectos !== 0) {
                array_push($temporal, array($typeProject['Nombre'] => $gasto, 'Total' => $totalProyectos));
            }
        }

        $this->typeProyects = $temporal;
    }

    private function getlistProyects() {
        $temporal = array();

        foreach ($this->proyectos as $project) {
            array_push($temporal, array($project->getDatosGenerales()));
        }
        return $temporal;
    }

    public function getDatosProyectos() {
        return array();
    }

    public function getInfoProyecto(array $dataProject) {
        $proyecto = $this->crearProyecto(array(
            'idProyecto' => $dataProject['proyecto'],
            'tipoProyecto' => $dataProject['tipoProyecto'],
            'moneda' => $dataProject['moneda'],
            'datosExtra' => TRUE));

        $dataProjectsInfo['proyectos'] = $proyecto->getDatos();
        $dataProjectsInfo['servicios'] = [];
        $dataProjectsInfo['sucursales'] = $proyecto->getDatosGenerales()['sucursales'];
        $dataProjectsInfo['categorias'] = [];
        $dataProjectsInfo['subcategorias'] = [];
        $dataProjectsInfo['concepto'] = [];
        $dataProjectsInfo['gastosCompras'] = [];

        return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Filters', $dataProjectsInfo, TRUE), 'consulta' => $dataProjectsInfo);
    }

    private function crearProyecto(array $datosProyecto) {
        $proyecto = new \Librerias\Gapsi\Proyecto($datosProyecto);
        return $proyecto;
    }

    public function getDatosTipoProyecto(array $datosProyectos) {
        $listaIdProyectos = $this->DBGestor->getListaProyectos($datosProyectos);

        $this->crearProyectos($listaIdProyectos);
        $listaProyectos = $this->getlistProyects();
        var_dump($this->proyectos);

        $dataProjectsInfo['proyectos'] = $listaProyectos;
        $dataProjectsInfo['servicios'] = [];
        $dataProjectsInfo['sucursales'] = $this->proyectos->getDatos()['sucursales'];
        $dataProjectsInfo['categorias'] = [];
        $dataProjectsInfo['subcategorias'] = [];
        $dataProjectsInfo['concepto'] = [];
        $dataProjectsInfo['gastosCompras'] = [];
//
        return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Filters', $dataProjectsInfo, TRUE), 'consulta' => $dataProjectsInfo);
    }

}
