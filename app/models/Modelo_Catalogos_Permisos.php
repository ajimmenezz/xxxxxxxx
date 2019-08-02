<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Catalogos_Permisos extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getRegistros(string $tabla) {
        try {
            $consulta = $this->consulta('select
                                       *
                                    from ' . $tabla . '');
            return $consulta;
        } catch (\Exception $ex) {
            throw new \Exception('Error con la base de datos : '.$ex->getMessage());
        }
    }

}
