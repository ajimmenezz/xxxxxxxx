<?php

namespace Modelos\Catalogos;

use Modelos\Modelo_SAE7;

class ProductosSAE extends Modelo_SAE7
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getFromAdist(int $id = null)
    {
        $condicion = '';
        if (!is_null($id)) {
            $condicion = " and Clave = '" . $id . "'";
        }

        $consulta = $this->consulta("
        select 
        Clave, 
        Nombre 
        from cat_v3_equipos_sae 
        where Flag = 1 " . $condicion . "
        order by Nombre");
        return $consulta;
    }
}
