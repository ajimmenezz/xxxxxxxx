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
        $this->proyectos = array();        
        $proyectos = $this->DBGestorProyectoGAPSI->getProjectsTypo($filters['tipoProyecto']);
        
//        foreach ($proyectos['query'] as $key => $proyecto) {
//            $temporal = new Proyecto($proyecto['IdProyecto']);
//            $temporal->setSucursales();
//            array_push($this->proyectos,$temporal);
//        }
        var_dump($proyectos);
        
//        var_dump($this->proyectos);
        $gastosProyecto = $this->DBGestorProyectoGAPSI->getGastosYComprasProyecto($filters['tipoProyecto']);
        return $proyectos;
    }

    public function getProjectInfo(array $filters) {
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects();
    }

}
