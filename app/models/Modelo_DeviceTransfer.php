<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_DeviceTransfer extends Modelo_Base
{
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getUsers()
    {
        return $this->consulta("select * from cat_v3_usuarios");
    }
}
