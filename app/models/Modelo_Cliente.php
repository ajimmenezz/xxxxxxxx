<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Cliente extends Base {
    
    public function setClientes() {
        $consulta = $this->consulta('SELECT * FROM cat_v3_clientes');
        return $consulta;
    }

}
