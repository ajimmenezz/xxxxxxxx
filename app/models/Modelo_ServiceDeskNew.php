<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_ServiceDeskNew extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getApiKeyDefault() {
        $consulta = $this->consulta('select SDKey from cat_v3_usuarios where Id = 2;');
        foreach ($consulta as $value) {
            $key = $value['SDKey'];
        }     
        return $key;
    }

}
