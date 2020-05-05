<?php

namespace Librerias\Catalogos;

use Controladores\Controller_Base_General as General;

class CatalogosGenericos extends General {

    public function __construct() {
        parent::__construct();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioUnidadNegocio(array $datos) {
        return array('formulario' => parent::getCI()->load->view('Poliza/Formularios/formularioUnidadNegocio', '', TRUE));
    }

}
