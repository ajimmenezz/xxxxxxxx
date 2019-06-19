<?php

namespace Librerias\Catalogos;

use Controladores\Controller_Base_General as General;

class Sucursal extends General
{
    private $DB;
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Catalogos\Sucursal::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function get(int $id = null, int $flag = null, int $cliente = null)
    {
        $sucursales = $this->DB->get($id, $flag, $cliente);
        return $sucursales;
    }

    public function ubicacionesCenso(int $sucursal)
    {
        $ubicaciones = $this->DB->ubicacionesCenso($sucursal);
        return $ubicaciones;
    }
}
