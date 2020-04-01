<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;

class Rehabilitacion extends General {

    private $DBI;

    public function __construct() {
        parent::__construct();
        $this->DBI = \Modelos\Modelo_InventarioConsignacion::factory();
        parent::getCI()->load->helper('date');
    }

    public function getAlmacenUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $this->DBI->getAlmacenUsuario($usuario['Id']);
    }

}
