<?php

namespace Librerias\Catalogos;

use Controladores\Controller_Base_General as General;

class ProductosSAE extends General
{
    private $DB;
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Catalogos\ProductosSAE::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getFromAdist(int $id = null)
    {
        $productos = $this->DB->getFromAdist($id);
        return $productos;
    }
}
