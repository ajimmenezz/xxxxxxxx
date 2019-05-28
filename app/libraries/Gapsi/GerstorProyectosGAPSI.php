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
        $proyectos = $this->DBGestorProyectoGAPSI->getProjectsByType($filters['tipoProyecto']);
        $servicios = $this->DBGestorProyectoGAPSI->getServicesByType($filters['tipoProyecto']);
        $sucursales = $this->DBGestorProyectoGAPSI->getBranchOfficesByType($filters['tipoProyecto']);
        $categorias = $this->DBGestorProyectoGAPSI->getCategoriesByType($filters['tipoProyecto']);
        $subcategorias = $this->DBGestorProyectoGAPSI->getSubcategoryByType($filters['tipoProyecto']);
        $concepto = $this->DBGestorProyectoGAPSI->getConceptByType($filters['tipoProyecto']);
        $gastosCompras = $this->DBGestorProyectoGAPSI->getExpensesAndPurchasesProject($filters['tipoProyecto']);
        
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

    public function getProjectInfo(array $filters) {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects();
    }

}
