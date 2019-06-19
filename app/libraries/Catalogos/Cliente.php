<?php

namespace Librerias\Catalogos;

use Controladores\Controller_Base_General as General;

class Cliente extends General
{
    private $DB;
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Catalogos\Cliente::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function get(int $id = null)
    {
        $clientes = $this->DB->get($id);
        return $clientes;
    }
}
