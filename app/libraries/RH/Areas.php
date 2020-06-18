<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Areas extends General {

    public function __construct() {
        parent::__construct();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioArea(array $datos) {
        return array('formulario' => parent::getCI()->load->view('RH/Modal/formularioArea', '', TRUE));
    }

}
