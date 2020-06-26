<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Cliente extends Base {
    
    public function setClientes(string $where = '') {
        $consulta = $this->consulta('SELECT * FROM cat_v3_clientes ' . $where . ' ORDER BY Nombre');
        return $consulta;
    }

}
