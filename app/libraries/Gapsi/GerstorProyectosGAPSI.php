<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GerstorProyectosGAPSI extends General {

    private $DBGestorProyectoGAPSI;

    public function __construct() {
        parent::__construct();
        $this->DBGestorProyectoGAPSI = \Modelos\Modelo_GapsiGestorProyectos::factory();
//        parent::getCI()->load->helper('date');
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
        $parametersDate = $this->parametersDate($filters);
        $parameters = $this->defineParameters($filters);
        $parameters = $parameters . $parametersDate;
        $proyectos = $this->DBGestorProyectoGAPSI->getProjectsByType($parameters);
        $servicios = $this->DBGestorProyectoGAPSI->getServicesByType($parameters);
        $sucursales = $this->DBGestorProyectoGAPSI->getBranchOfficesByType($parameters);
        $categorias = $this->DBGestorProyectoGAPSI->getCategoriesByType($parameters);
        $subcategorias = $this->DBGestorProyectoGAPSI->getSubcategoryByType($parameters);
        $concepto = $this->DBGestorProyectoGAPSI->getConceptByType($parameters);
        $gastosCompras = $this->DBGestorProyectoGAPSI->getExpensesAndPurchasesProject($parameters);

        $dataProjectsInfo['proyectos'] = $proyectos['query'];
        $dataProjectsInfo['servicios'] = $servicios['query'];
        $dataProjectsInfo['sucursales'] = $sucursales['query'];
        $dataProjectsInfo['categorias'] = $categorias['query'];
        $dataProjectsInfo['subcategorias'] = $subcategorias['query'];
        $dataProjectsInfo['concepto'] = $concepto['query'];
        $dataProjectsInfo['gastosCompras'] = $gastosCompras['query'];

        foreach ($dataProjectsInfo['proyectos'] as $key => $value) {
            array_push($dataProjectsInfo['proyectos'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['servicios'] as $key => $value) {
            array_push($dataProjectsInfo['servicios'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['sucursales'] as $key => $value) {
            array_push($dataProjectsInfo['sucursales'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['categorias'] as $key => $value) {
            array_push($dataProjectsInfo['categorias'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['subcategorias'] as $key => $value) {
            array_push($dataProjectsInfo['subcategorias'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['gastosCompras'] as $key => $value) {
            array_push($dataProjectsInfo['gastosCompras'][$key], $filters['moneda']);
        }

        return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Filters', $dataProjectsInfo, TRUE), 'consulta' => $dataProjectsInfo);
    }

    private function parametersDate(array $filters) {
        if (isset($filters['fechaInicio']) && isset($filters['fechaFinal'])) {
            $parameters = " AND FCaptura BETWEEN '" . $filters['fechaInicio'] . "' AND '" . $filters['fechaFinal'] . "'";
        } else {
            $parameters = '';
        }

        return $parameters;
    }

    private function defineParameters(array $filters) {
        if (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal= '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['concepto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Concepto = '" . $filters['concepto'] . "'";
        } else {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'";
        }

        return $parameters;
    }

}
