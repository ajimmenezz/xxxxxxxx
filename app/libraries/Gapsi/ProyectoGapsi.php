<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class ProyectoGapsi extends General {

    private $DBG;

    public function __construct() {
        parent::__construct();
        $this->DBG = \Modelos\Modelo_Proyecto_Gapsi::factory();
    }

    public function showAllProjects() {
        $dataProjects = $this->DBG->showAllProjects();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function showProjectTypes() {
        $dataProjects = $this->DBG->showProjectTypes();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

}
