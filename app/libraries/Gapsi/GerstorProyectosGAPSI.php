<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Gapsi\Proyecto as Proyecto;

class GerstorProyectosGAPSI extends General {

    private $DBGestorProyectoGAPSI;
    private $proyectos;

    public function __construct() {
        parent::__construct();
        $this->DBGestorProyectoGAPSI = \Modelos\Modelo_GapsiGestorProyectos::factory();
    }

    public function getListProjects() {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes() {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjectTypes();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectsInfo(array $filters) {
        $dataProjectsInfo = array();
        $this->proyectos = array();

        $paraemeters = $this->defineParameters($filters);
        $proyectos = $this->DBGestorProyectoGAPSI->getProjectsByType($paraemeters);
        $servicios = $this->DBGestorProyectoGAPSI->getServicesByType($paraemeters);
        $sucursales = $this->DBGestorProyectoGAPSI->getBranchOfficesByType($paraemeters);
        $categorias = $this->DBGestorProyectoGAPSI->getCategoriesByType($paraemeters);
        $subcategorias = $this->DBGestorProyectoGAPSI->getSubcategoryByType($paraemeters);
        $concepto = $this->DBGestorProyectoGAPSI->getConceptByType($paraemeters);
        $gastosCompras = $this->DBGestorProyectoGAPSI->getExpensesAndPurchasesProject($paraemeters);

//        foreach ($proyectos['query'] as $key => $proyecto) {
//            $temporal = new Proyecto($proyecto['IdProyecto']);
//            $temporal->setSucursales();
//            array_push($this->proyectos,$temporal);
//        }
//        var_dump($proyectos);
//        var_dump($this->proyectos);

        $dataProjectsInfo['proyectos'] = $proyectos['query'];
        $dataProjectsInfo['servicios'] = $servicios['query'];
        $dataProjectsInfo['sucursales'] = $sucursales['query'];
        $dataProjectsInfo['categorias'] = $categorias['query'];
        $dataProjectsInfo['subcategorias'] = $subcategorias['query'];
        $dataProjectsInfo['concepto'] = $concepto['query'];
        $dataProjectsInfo['gastosCompras'] = $gastosCompras['query'];
        return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Filters', $dataProjectsInfo, TRUE));
    }

    private function defineParameters(array $filters) {

        if (isset($filters['proyecto'])) {
            $parameters = "AND Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'";
        } elseif (isset($filters['servicio'])) {
            $parameters = "AND Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.ID = '" . $filters['servicio'] . "'";
        } else {
            $parameters = "AND Tipo = '" . $filters['tipoProyecto'] . "'";
        }

        return $parameters;
    }

    public function getProjectInfo(array $filters) {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects();
    }

}
