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
        return $dataProjectsInfo;
    }

    private function defineParameters(array $filters) {
        if (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio']) && isset($filters['sucursal']) && isset($filters['categoria']) && isset($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'
                            AND dsc.ID = '" . $filters['subcategoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio']) && isset($filters['sucursal']) && isset($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio']) && isset($filters['categoria']) && isset($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'
                            AND dsc.ID = '" . $filters['subcategoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['categoria']) && isset($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'
                            AND dsc.ID = '" . $filters['subcategoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio']) && isset($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio']) && isset($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['categoria']) && isset($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'
                            AND dsc.ID = '" . $filters['subcategoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto']) && isset($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['servicio']) && isset($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'
                            AND dr.Sucursal= '" . $filters['sucursal'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND dc.ID = '" . $filters['categoria'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND dt.ID = '" . $filters['servicio'] . "'";
        } elseif (isset($filters['tipoProyecto']) && isset($filters['proyecto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'";
        } else {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dti.ID = '" . $filters['tipoProyecto'] . "'";
        }

        return $parameters;
    }

    public function getProjectInfo(array $filters) {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects();
    }

}
