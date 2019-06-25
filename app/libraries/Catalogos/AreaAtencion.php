<?php

namespace Librerias\Catalogos;

use Controladores\Controller_Base_General as General;

class AreaAtencion extends General
{
    private $DB;
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Catalogos\AreaAtencion::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function get(int $id = null, int $flag = null, int $cliente = null)
    {
        $areas = $this->DB->get($id, $flag, $cliente);
        return $areas;
    }    
}