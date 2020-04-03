<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Equipo extends Modelo_Base {
    
    public function __construct() {
        parent::__construct();
    }

    public function getRefaccionesEquipo(string $idModelo) {
        $consulta = $this->consulta("SELECT 
                                        *
                                        FROM cat_v3_componentes_equipo
                                        WHERE IdModelo = '" . $idModelo . "'
                                        AND Flag = 1");
        return $consulta;
    }

}
