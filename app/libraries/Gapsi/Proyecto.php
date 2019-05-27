<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Proyecto extends General {

    private $DBG;

    public function __construct() {
        parent::__construct();
        $this->DBG = \Modelos\Modelo_Gapsi_Proyecto::factory();
    }

    public function getProjects() {
        $dataProjects = $this->DBG->getProjects();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes() {
        $dataProjects = $this->DBG->getProjectTypes();

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

}
