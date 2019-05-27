<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GerstorProyectosGAPSI extends General {
    
    private $DBProyectoGAPSI;
    
    public function __construct() {
        parent::__construct();
        $this->DBProyectoGAPSI = \Modelos\Modelo_GapsiProyecto::factory();
    }

    public function getListProjects() {
        $dataProjects = $this->DBProyectoGAPSI->getProjects();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes() {
        $dataProjects = $this->DBProyectoGAPSI->getProjectTypes();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

}
