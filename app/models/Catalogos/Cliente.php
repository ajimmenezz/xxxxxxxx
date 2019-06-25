<?php

namespace Modelos\Catalogos;

use Librerias\Modelos\Base as Modelo_Base;

class Cliente extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function get(int $id = null)
    {

        $this->iniciaTransaccion();

        $condicion = " where 1 = 1 ";
        if (!is_null($id)) {
            $condicion .= " and Id = '" . $id . "' ";
        }

        $consulta = $this->consulta("
        select
        cc.*,
        (select Nombre from cat_v3_paises where Id = cc.IdPais) as Pais,
        (select Nombre from cat_v3_estados where Id = cc.IdEstado) as Estado,
        (select Nombre from cat_v3_municipios where Id = cc.IdMunicipio) as Municipio,
        (select Nombre from cat_v3_colonias where Id = cc.IdColonia) as Colonia        
        from cat_v3_clientes cc " . $condicion . " order by Nombre");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError(),
                'result' => []
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => 'Success',
                'result' => $consulta
            ];
        }
    }
}
