<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Departamentos extends General {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioDepartamentos(array $datos) {
        $data = array();
        $data['areas'] = $this->catalogo->catAreas('3', array('Flag' => '1'));
        if (!empty($datos['Departamento'])) {
            $data['idArea'] = $this->catalogo->catConsultaGeneral('SELECT IdArea FROM cat_v3_departamentos_siccob WHERE Id = ' . $datos['Departamento']);
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_departamentos_siccob WHERE Id = ' . $datos['Departamento']);
        }
        return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioDepartamentos', $data, TRUE), 'datos' => $data);
    }

}
